#!/usr/bin/env python3
"""
DeepSeek Translation Script for HumanitarianBlog
GÖREV 15: Translate posts to Arabic and French using DeepSeek API

Usage:
    python translate_posts.py --api-key YOUR_DEEPSEEK_API_KEY
    python translate_posts.py --api-key YOUR_KEY --post-id 123
    python translate_posts.py --api-key YOUR_KEY --limit 10

Requirements:
    pip install requests mysql-connector-python python-dotenv
"""

import os
import sys
import argparse
import json
import time
import re
from typing import Optional, Dict, List, Tuple

try:
    import requests
    import mysql.connector
    from dotenv import load_dotenv
except ImportError:
    print("Please install required packages:")
    print("pip install requests mysql-connector-python python-dotenv")
    sys.exit(1)

# Load environment variables
load_dotenv()

# Configuration
DEEPSEEK_API_URL = "https://api.deepseek.com/v1/chat/completions"
LANGUAGES = {
    'ar': 'Arabic',
    'fr': 'French'
}

# WordPress database configuration (Local by Flywheel defaults)
DB_CONFIG = {
    'host': os.getenv('DB_HOST', 'localhost'),
    'user': os.getenv('DB_USER', 'root'),
    'password': os.getenv('DB_PASSWORD', 'root'),
    'database': os.getenv('DB_NAME', 'local'),
    'port': int(os.getenv('DB_PORT', 10018))  # Local by Flywheel MySQL port
}


class DeepSeekTranslator:
    """Handles translation using DeepSeek API"""

    def __init__(self, api_key: str):
        self.api_key = api_key
        self.headers = {
            'Content-Type': 'application/json',
            'Authorization': f'Bearer {api_key}'
        }

    def translate(self, text: str, target_lang: str) -> Optional[str]:
        """Translate text to target language"""
        if not text or not text.strip():
            return text

        lang_name = LANGUAGES.get(target_lang, target_lang)

        prompt = f"""Translate the following text to {lang_name}.
Keep all HTML tags intact. Do not translate proper nouns, organization names, or technical terms that should remain in English.
Only return the translated text, nothing else.

Text to translate:
{text}"""

        payload = {
            'model': 'deepseek-chat',
            'messages': [
                {
                    'role': 'system',
                    'content': f'You are a professional translator specializing in humanitarian and development content. Translate accurately to {lang_name} while preserving HTML formatting.'
                },
                {
                    'role': 'user',
                    'content': prompt
                }
            ],
            'temperature': 0.3,
            'max_tokens': 4096
        }

        try:
            response = requests.post(
                DEEPSEEK_API_URL,
                headers=self.headers,
                json=payload,
                timeout=60
            )
            response.raise_for_status()

            result = response.json()
            translated = result['choices'][0]['message']['content']
            return translated.strip()

        except requests.exceptions.RequestException as e:
            print(f"  Error translating: {e}")
            return None
        except (KeyError, IndexError) as e:
            print(f"  Error parsing response: {e}")
            return None


class WordPressDB:
    """Handles WordPress database operations"""

    def __init__(self):
        self.conn = None
        self.cursor = None
        self.prefix = 'wp_'

    def connect(self) -> bool:
        """Connect to WordPress database"""
        try:
            self.conn = mysql.connector.connect(**DB_CONFIG)
            self.cursor = self.conn.cursor(dictionary=True)
            print(f"Connected to database: {DB_CONFIG['database']}")
            return True
        except mysql.connector.Error as e:
            print(f"Database connection error: {e}")
            return False

    def close(self):
        """Close database connection"""
        if self.cursor:
            self.cursor.close()
        if self.conn:
            self.conn.close()

    def get_posts(self, limit: int = 10, post_id: Optional[int] = None) -> List[Dict]:
        """Get posts that haven't been translated yet"""
        query = f"""
            SELECT p.ID, p.post_title, p.post_content, p.post_excerpt,
                   p.post_name, p.post_status, p.post_author
            FROM {self.prefix}posts p
            WHERE p.post_type = 'post'
            AND p.post_status = 'publish'
        """

        if post_id:
            query += f" AND p.ID = {post_id}"

        # Exclude already translated posts (check if translation exists)
        query += f"""
            AND p.ID NOT IN (
                SELECT meta_value FROM {self.prefix}postmeta
                WHERE meta_key = '_original_post_id'
            )
        """

        query += f" ORDER BY p.post_date DESC LIMIT {limit}"

        self.cursor.execute(query)
        return self.cursor.fetchall()

    def get_post_meta(self, post_id: int) -> Dict:
        """Get post meta data"""
        query = f"""
            SELECT meta_key, meta_value
            FROM {self.prefix}postmeta
            WHERE post_id = %s
        """
        self.cursor.execute(query, (post_id,))
        return {row['meta_key']: row['meta_value'] for row in self.cursor.fetchall()}

    def get_post_categories(self, post_id: int) -> List[int]:
        """Get post category IDs"""
        query = f"""
            SELECT tt.term_id
            FROM {self.prefix}term_relationships tr
            JOIN {self.prefix}term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
            WHERE tr.object_id = %s AND tt.taxonomy = 'category'
        """
        self.cursor.execute(query, (post_id,))
        return [row['term_id'] for row in self.cursor.fetchall()]

    def get_post_tags(self, post_id: int) -> List[int]:
        """Get post tag IDs"""
        query = f"""
            SELECT tt.term_id
            FROM {self.prefix}term_relationships tr
            JOIN {self.prefix}term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
            WHERE tr.object_id = %s AND tt.taxonomy = 'post_tag'
        """
        self.cursor.execute(query, (post_id,))
        return [row['term_id'] for row in self.cursor.fetchall()]

    def create_translated_post(self, original: Dict, translated_title: str,
                                translated_content: str, translated_excerpt: str,
                                lang: str, original_id: int) -> Optional[int]:
        """Create a new translated post"""
        # Generate unique slug
        slug = f"{original['post_name']}-{lang}"

        query = f"""
            INSERT INTO {self.prefix}posts
            (post_author, post_date, post_date_gmt, post_content, post_title,
             post_excerpt, post_status, post_name, post_type, post_modified, post_modified_gmt)
            VALUES (%s, NOW(), UTC_TIMESTAMP(), %s, %s, %s, 'publish', %s, 'post', NOW(), UTC_TIMESTAMP())
        """

        try:
            self.cursor.execute(query, (
                original['post_author'],
                translated_content,
                translated_title,
                translated_excerpt or '',
                slug
            ))
            self.conn.commit()
            new_post_id = self.cursor.lastrowid

            # Add meta data
            self.add_post_meta(new_post_id, '_original_post_id', str(original_id))
            self.add_post_meta(new_post_id, '_translation_language', lang)

            # Set language taxonomy
            self.set_post_language(new_post_id, lang)

            # Copy categories and tags
            categories = self.get_post_categories(original_id)
            for cat_id in categories:
                self.add_term_relationship(new_post_id, cat_id, 'category')

            tags = self.get_post_tags(original_id)
            for tag_id in tags:
                self.add_term_relationship(new_post_id, tag_id, 'post_tag')

            # Copy featured image
            thumbnail_id = self.get_post_meta(original_id).get('_thumbnail_id')
            if thumbnail_id:
                self.add_post_meta(new_post_id, '_thumbnail_id', thumbnail_id)

            return new_post_id

        except mysql.connector.Error as e:
            print(f"  Error creating post: {e}")
            self.conn.rollback()
            return None

    def add_post_meta(self, post_id: int, key: str, value: str):
        """Add post meta data"""
        query = f"""
            INSERT INTO {self.prefix}postmeta (post_id, meta_key, meta_value)
            VALUES (%s, %s, %s)
        """
        try:
            self.cursor.execute(query, (post_id, key, value))
            self.conn.commit()
        except mysql.connector.Error as e:
            print(f"  Error adding meta: {e}")

    def set_post_language(self, post_id: int, lang: str):
        """Set post language using the language taxonomy"""
        # Get or create language term
        query = f"""
            SELECT t.term_id, tt.term_taxonomy_id
            FROM {self.prefix}terms t
            JOIN {self.prefix}term_taxonomy tt ON t.term_id = tt.term_id
            WHERE t.slug = %s AND tt.taxonomy = 'language'
        """
        self.cursor.execute(query, (lang,))
        result = self.cursor.fetchone()

        if result:
            term_taxonomy_id = result['term_taxonomy_id']
        else:
            # Create language term if it doesn't exist
            lang_name = LANGUAGES.get(lang, lang.upper())
            self.cursor.execute(
                f"INSERT INTO {self.prefix}terms (name, slug) VALUES (%s, %s)",
                (lang_name, lang)
            )
            term_id = self.cursor.lastrowid

            self.cursor.execute(
                f"INSERT INTO {self.prefix}term_taxonomy (term_id, taxonomy) VALUES (%s, 'language')",
                (term_id,)
            )
            term_taxonomy_id = self.cursor.lastrowid
            self.conn.commit()

        # Add relationship
        self.cursor.execute(
            f"INSERT IGNORE INTO {self.prefix}term_relationships (object_id, term_taxonomy_id) VALUES (%s, %s)",
            (post_id, term_taxonomy_id)
        )
        self.conn.commit()

    def add_term_relationship(self, post_id: int, term_id: int, taxonomy: str):
        """Add term relationship to post"""
        query = f"""
            SELECT term_taxonomy_id FROM {self.prefix}term_taxonomy
            WHERE term_id = %s AND taxonomy = %s
        """
        self.cursor.execute(query, (term_id, taxonomy))
        result = self.cursor.fetchone()

        if result:
            self.cursor.execute(
                f"INSERT IGNORE INTO {self.prefix}term_relationships (object_id, term_taxonomy_id) VALUES (%s, %s)",
                (post_id, result['term_taxonomy_id'])
            )
            self.conn.commit()


def main():
    parser = argparse.ArgumentParser(description='Translate WordPress posts using DeepSeek API')
    parser.add_argument('--api-key', required=True, help='DeepSeek API key')
    parser.add_argument('--post-id', type=int, help='Translate specific post ID')
    parser.add_argument('--limit', type=int, default=5, help='Number of posts to translate (default: 5)')
    parser.add_argument('--lang', choices=['ar', 'fr', 'both'], default='both',
                        help='Target language(s) (default: both)')
    parser.add_argument('--dry-run', action='store_true', help='Show what would be translated without saving')

    args = parser.parse_args()

    # Initialize
    translator = DeepSeekTranslator(args.api_key)
    db = WordPressDB()

    if not db.connect():
        sys.exit(1)

    try:
        # Get posts to translate
        posts = db.get_posts(limit=args.limit, post_id=args.post_id)

        if not posts:
            print("No posts found to translate.")
            return

        print(f"\nFound {len(posts)} post(s) to translate\n")

        # Determine languages
        target_langs = ['ar', 'fr'] if args.lang == 'both' else [args.lang]

        for post in posts:
            print(f"=" * 60)
            print(f"Post #{post['ID']}: {post['post_title'][:50]}...")

            for lang in target_langs:
                print(f"\n  Translating to {LANGUAGES[lang]}...")

                if args.dry_run:
                    print(f"  [DRY RUN] Would translate and create new post")
                    continue

                # Translate title
                translated_title = translator.translate(post['post_title'], lang)
                if not translated_title:
                    print(f"  Failed to translate title, skipping...")
                    continue

                # Translate content
                translated_content = translator.translate(post['post_content'], lang)
                if not translated_content:
                    print(f"  Failed to translate content, skipping...")
                    continue

                # Translate excerpt if exists
                translated_excerpt = ''
                if post['post_excerpt']:
                    translated_excerpt = translator.translate(post['post_excerpt'], lang) or ''

                # Create translated post
                new_id = db.create_translated_post(
                    original=post,
                    translated_title=translated_title,
                    translated_content=translated_content,
                    translated_excerpt=translated_excerpt,
                    lang=lang,
                    original_id=post['ID']
                )

                if new_id:
                    print(f"  ✓ Created translated post #{new_id}")
                else:
                    print(f"  ✗ Failed to create translated post")

                # Rate limiting - wait between API calls
                time.sleep(1)

        print(f"\n{'=' * 60}")
        print("Translation complete!")

    finally:
        db.close()


if __name__ == '__main__':
    main()
