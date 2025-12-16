<?php
/**
 * Demo Content Generator
 *
 * Creates realistic humanitarian journalism articles for testing
 *
 * USAGE: Visit this file once in browser, then DELETE IT!
 * URL: http://humanitarian-blog.local/wp-content/themes/flavor-starter/create-demo-content.php
 *
 * @package HumanitarianBlog
 */

// Load WordPress
require_once(__DIR__ . '/../../../../../wp-load.php');

// Security check - only admins can run this
if (!current_user_can('administrator')) {
    die('Access denied. Only administrators can create demo content.');
}

// Prevent running multiple times
if (get_option('humanitarian_demo_content_created')) {
    die('Demo content already created! Delete the "humanitarian_demo_content_created" option to run again.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Creating Demo Content...</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding: 40px; max-width: 800px; margin: 0 auto; }
        h1 { color: #e74c3c; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; padding: 12px; margin: 10px 0; border-radius: 4px; color: #155724; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; padding: 12px; margin: 10px 0; border-radius: 4px; color: #0c5460; }
        .warning { background: #fff3cd; border: 1px solid #ffeeba; padding: 12px; margin: 10px 0; border-radius: 4px; color: #856404; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; padding: 12px; margin: 10px 0; border-radius: 4px; color: #721c24; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>🚀 Creating Demo Content for Humanitarian Blog</h1>
    <p>Please wait while we create realistic demo articles...</p>

<?php

/**
 * Demo Articles Data
 */
$demo_articles = [
    [
        'title' => 'Breaking: Humanitarian Crisis Deepens in Northern Syria as Winter Approaches',
        'content' => '<p>As temperatures drop across northern Syria, humanitarian organizations are warning of a potential catastrophe facing displaced populations in makeshift camps along the Turkish border.</p>

<p>More than 2.5 million people remain displaced in northwest Syria, with many living in tents and temporary shelters that offer little protection against harsh winter conditions. Aid workers report critical shortages of heating fuel, winter clothing, and weatherproof shelters.</p>

<h2>Urgent Needs</h2>

<p>"We are in a race against time," said Maria Rodriguez, country director for a major international NGO. "Without immediate intervention, we could see preventable deaths from hypothermia and cold-related illnesses, particularly among children and the elderly."</p>

<p>The United Nations has appealed for $400 million in emergency funding to provide winter assistance, including thermal blankets, heating materials, and insulated tents. However, as of this week, only 23% of the required funding has been secured.</p>

<h2>Infrastructure Challenges</h2>

<p>The situation is compounded by damaged infrastructure from years of conflict. Many camps lack proper drainage systems, leading to flooding during winter rains. Healthcare facilities are overwhelmed and understaffed, making it difficult to treat cold-related illnesses.</p>

<p>Children are particularly vulnerable. UNICEF estimates that over 500,000 children under five are at risk of respiratory infections and other winter-related health complications. Schools in the camps have been closed due to lack of heating, disrupting education for tens of thousands of children.</p>

<blockquote>"My children wake up shivering every morning. We burn whatever we can find to keep warm, but it\'s never enough." - Fatima, mother of four in Idlib camp</blockquote>

<p>International humanitarian law experts stress that all parties to the conflict have obligations to ensure civilian access to humanitarian assistance. Cross-border aid delivery mechanisms remain critical for reaching populations in need.</p>

<p>Weather forecasts predict temperatures will drop below freezing in the coming weeks, adding urgency to the humanitarian response.</p>',
        'category' => 'News',
        'article_type' => 'News',
        'region' => 'Middle East',
        'tags' => ['Syria', 'Displacement', 'Winter Emergency', 'Humanitarian Crisis'],
        'excerpt' => 'More than 2.5 million displaced people face harsh winter conditions in northwest Syria as aid organizations struggle to secure funding for emergency assistance.',
    ],
    [
        'title' => 'Opinion: Why Climate Finance Must Prioritize Frontline Communities',
        'content' => '<p>As world leaders gather for yet another climate summit, the conversation around climate finance continues to miss the mark. The gap between pledges and actual disbursements tells a troubling story about priorities.</p>

<p>By Sarah Johnson, Climate Policy Analyst</p>

<h2>The Broken Promise</h2>

<p>In 2009, wealthy nations promised to mobilize $100 billion annually by 2020 to help developing countries adapt to climate change and transition to clean energy. That target was missed by $17 billion, and the shortfall matters immensely.</p>

<p>But the problem goes deeper than numbers. Even when funding does flow, it rarely reaches the communities on the frontlines of climate impacts. Instead, it gets trapped in bureaucratic processes, channeled through intermediaries who take their cuts, or tied to conditions that serve donor interests rather than recipient needs.</p>

<h2>Who Bears the Cost?</h2>

<p>Small island nations facing existential threats from sea-level rise. Indigenous communities watching their traditional lands transform. Subsistence farmers dealing with unprecedented droughts. These are the people who contributed least to the climate crisis but suffer most from its consequences.</p>

<blockquote>"We don\'t need another conference room promise. We need resources in the hands of people who know their land, their weather patterns, and what adaptations will actually work." - Pacific Islands climate activist</blockquote>

<p>The current system perpetuates colonial dynamics. Northern institutions decide what constitutes "viable" climate projects, often favoring large-scale technological interventions over local knowledge and community-led solutions.</p>

<h2>A Different Approach</h2>

<p>What if we flipped the model? Direct financing to local organizations and community groups. Simplified application processes in local languages. Technical support that builds local capacity rather than creating dependency. Recognition that traditional knowledge about land and weather patterns has value.</p>

<p>Some smaller funds are already showing this works. The Global Environment Facility\'s Small Grants Programme has supported thousands of community-led projects with impressive results. But it remains a drop in the bucket compared to the scale of need.</p>

<p>Climate finance isn\'t charity - it\'s a moral obligation and a down payment on our collective future. We need a fundamental shift in how we think about and deliver this financing. The communities we claim to help should have real power over how resources are allocated and used.</p>

<p>The clock is ticking, and the communities on the frontlines cannot wait for wealthy nations to perfect their bureaucratic processes. Climate finance must become truly just finance, or it will fail in its most critical mission.</p>',
        'category' => 'Opinion',
        'article_type' => 'Opinion',
        'region' => 'Global',
        'tags' => ['Climate Change', 'Climate Finance', 'Environmental Justice', 'Development'],
        'excerpt' => 'Current climate finance mechanisms fail frontline communities. We need direct funding, simplified processes, and recognition of local knowledge.',
    ],
    [
        'title' => 'Analysis: How Social Media Shapes Modern Humanitarian Response',
        'content' => '<p>The relationship between social media and humanitarian action has evolved dramatically over the past decade, fundamentally changing how crises are documented, how aid is mobilized, and how affected populations engage with the international community.</p>

<h2>Real-Time Documentation</h2>

<p>Gone are the days when humanitarian organizations held a monopoly on crisis information. Today, anyone with a smartphone can document and broadcast emergencies as they unfold. This democratization of information has both empowered affected communities and complicated traditional aid models.</p>

<p>During the 2023 Turkey-Syria earthquake, social media platforms became critical tools for coordinating rescue efforts. WhatsApp groups organized volunteer teams. Twitter threads mapped collapsed buildings. Instagram stories showed real-time needs. TikTok videos reached younger donors who might never read a traditional appeal.</p>

<h2>The Verification Challenge</h2>

<p>But this flood of information comes with serious challenges. Misinformation spreads rapidly. Unverified images and videos can trigger inappropriate responses. Well-meaning but uninformed social media campaigns can actually harm relief efforts by creating bottlenecks or directing resources to the wrong places.</p>

<p>Humanitarian organizations now employ dedicated teams to monitor social media, verify information, and combat false narratives. The International Committee of the Red Cross has developed specific protocols for engaging with user-generated content while maintaining neutrality and protecting affected populations.</p>

<h2>Digital Divide Concerns</h2>

<p>Yet relying on social media for humanitarian response raises equity concerns. Not everyone has smartphone access or internet connectivity. Some of the most vulnerable populations - elderly people, those in extreme poverty, people with disabilities - may be systematically excluded from digitally-mediated aid.</p>

<blockquote>"We cannot let social media visibility determine who receives assistance. The loudest voices online are not necessarily the people in greatest need." - Humanitarian aid coordinator</blockquote>

<h2>Changing Donor Behavior</h2>

<p>Social media has transformed fundraising. Viral campaigns can raise millions within hours, but this "clicktivism" often favors photogenic disasters over chronic emergencies. The Syrian refugee crisis generated massive social media engagement and donations, while equally severe situations in Central African Republic barely registered.</p>

<p>Research shows that social media-driven donations tend to spike immediately after a crisis makes headlines, then drop off sharply regardless of ongoing needs. This creates feast-or-famine funding cycles that make long-term programming difficult.</p>

<h2>Privacy and Protection</h2>

<p>Perhaps most concerning is the privacy implications. Refugees fleeing persecution may be identified through social media posts. Children\'s images circulate without consent. Affected communities become unwitting subjects of disaster tourism and exploitation.</p>

<p>New frameworks are emerging to address these challenges. The Signal Code of Conduct provides guidelines for ethical digital communication in humanitarian contexts. UNICEF has developed child safeguarding protocols for social media. But enforcement remains inconsistent.</p>

<h2>Looking Forward</h2>

<p>Social media is now an inescapable part of humanitarian response. The question is not whether to engage with these platforms, but how to do so ethically and effectively. This requires:

<ul>
<li>Investing in digital literacy for both aid workers and affected populations</li>
<li>Developing robust verification systems</li>
<li>Creating accountability mechanisms for social media campaigns</li>
<li>Ensuring traditional communication channels remain viable for those without digital access</li>
<li>Strengthening data protection and privacy safeguards</li>
</ul>

<p>The humanitarian sector must evolve to meet the digital age while maintaining its fundamental principles of humanity, neutrality, impartiality, and independence. Social media is a tool, not a solution - and like any tool, its value depends on how wisely we use it.</p>',
        'category' => 'Analysis',
        'article_type' => 'Analysis',
        'region' => 'Global',
        'tags' => ['Social Media', 'Technology', 'Digital Humanitarianism', 'Communications'],
        'excerpt' => 'Social media has fundamentally transformed humanitarian response, bringing both opportunities and challenges that require careful navigation.',
    ],
    [
        'title' => 'Special Report: Inside Yemen\'s Hidden Hunger Crisis',
        'content' => '<p>In the port city of Hodeidah, Amina cradles her two-year-old daughter, whose skeletal frame tells a story that statistics alone cannot convey. They are among the 17 million Yemenis facing acute food insecurity - a humanitarian catastrophe unfolding largely out of the international spotlight.</p>

<h2>A Perfect Storm</h2>

<p>Yemen\'s hunger crisis is not the result of a single cause but a convergence of factors: nearly a decade of conflict, economic collapse, infrastructure destruction, and climate shocks. Add a global food price surge and declining international aid, and you have what UN officials call "one of the world\'s worst humanitarian disasters."</p>

<p>This report draws on interviews with 50 families across four governorates, aid workers from a dozen organizations, and local health officials documenting malnutrition rates that rival any famine in recent history.</p>

<h2>The Mechanics of Hunger</h2>

<p>Food is available in markets, but prices have increased 400% since 2015 while incomes have collapsed. The Yemeni rial has lost 80% of its value. Government salaries often go unpaid for months. Remittances from family members abroad have decreased as the diaspora struggles with its own economic pressures.</p>

<p>"We are witnessing a slow-motion famine," explains Dr. Ahmed Hassan, who runs a therapeutic feeding center in Aden. "Families reduce meal frequency, switch to less nutritious foods, send children to work instead of school. By the time they reach us, the malnutrition is severe."</p>

<h2>The Blockade Factor</h2>

<p>The Saudi-led coalition\'s air and sea blockade, ostensibly to prevent weapons smuggling, has severely restricted commercial and humanitarian imports to a country that traditionally imported 90% of its food. Clearance delays for aid shipments can stretch to months, and commercial vessels face similar obstacles.</p>

<p>Fuel shortages caused by import restrictions have cascading effects. Hospitals cannot power generators. Water pumps fail. Food spoils without refrigeration. Farmers cannot irrigate crops or transport produce to markets.</p>

<blockquote>"The blockade doesn\'t just stop weapons. It stops the economy. It stops food. It stops life." - Local business owner in Hodeidah</blockquote>

<h2>Children Bear the Burden</h2>

<p>Malnutrition statistics are staggering but abstract until you visit therapeutic feeding centers. Children with stick-thin limbs and distended bellies. Infants too weak to cry. Mothers who cannot produce breast milk because they are starving themselves.</p>

<p>UNICEF estimates that 2.2 million children under five suffer from acute malnutrition, including 538,000 with severe acute malnutrition - a death sentence without treatment. But therapeutic feeding centers can only accommodate a fraction of those in need.</p>

<h2>The Aid Dilemma</h2>

<p>International humanitarian organizations face impossible choices. Aid flows have decreased by 40% in the past two years as donor fatigue sets in and other crises compete for attention. The war in Ukraine, for instance, diverted both funding and global wheat supplies that Yemen depended on.</p>

<p>Organizations must triage - cutting programs, reducing rations, narrowing geographic coverage. Food assistance that once reached 12 million people now reaches 9 million, with rations cut from 100% to 60% of minimum requirements.</p>

<p>Access restrictions compound the challenge. Some areas are controlled by parties that impede aid delivery or divert resources. Bureaucratic requirements for operating permits can take months. Security risks are constant.</p>

<h2>Climate Change Amplifies Crisis</h2>

<p>While conflict dominates headlines, climate change silently intensifies Yemen\'s hunger crisis. Rainfall patterns have become erratic. Droughts last longer. Flash floods destroy crops. Desert locusts devastate what little agriculture remains.</p>

<p>Fishing communities along the Red Sea coast report declining catches as water temperatures rise and marine ecosystems shift. For communities that historically relied on fishing as their primary food and income source, this is catastrophic.</p>

<h2>Long-Term Damage</h2>

<p>Even if the conflict ended tomorrow, Yemen faces generational consequences. Chronic malnutrition stunts physical and cognitive development. Children who survive may never reach their full potential. The workforce of 2040 is being permanently damaged today.</p>

<p>Health systems have collapsed. Education has been disrupted. Social fabric has frayed. Economic infrastructure lies in ruins. Recovery will require not just food assistance but comprehensive reconstruction - a project that will take decades and billions of dollars.</p>

<h2>What Can Be Done</h2>

<p>There are no simple solutions, but clear steps could mitigate the crisis:

<ul>
<li>Immediate ceasefire and peace negotiations</li>
<li>Lifting or loosening blockade restrictions</li>
<li>Increased and predictable humanitarian funding</li>
<li>Paying public sector salaries to restore purchasing power</li>
<li>Supporting local economy and food production</li>
<li>Improving humanitarian access across frontlines</li>
</ul>

<p>Most critically, Yemen needs sustained international attention. The crisis has fallen off media radars and political agendas. Without renewed pressure on parties to the conflict and renewed commitment from donors, Yemen\'s hidden hunger will continue claiming lives in the shadows.</p>

<p>Back in Hodeidah, Amina waits for her daughter\'s name to be called at the feeding center. She is among the fortunate - she has access. Millions more across Yemen face hunger alone, invisible to a world that has largely moved on.</p>',
        'category' => 'Report',
        'article_type' => 'Report',
        'region' => 'Middle East',
        'tags' => ['Yemen', 'Hunger', 'Famine', 'Conflict', 'Food Security'],
        'excerpt' => 'An in-depth investigation into Yemen\'s severe hunger crisis, where 17 million people face acute food insecurity amid conflict, economic collapse, and climate shocks.',
    ],
    [
        'title' => 'Interview: UNHCR Chief on the Global Refugee Crisis at Record Levels',
        'content' => '<p>Filippo Grandi, the United Nations High Commissioner for Refugees, sat down with us in Geneva to discuss the unprecedented displacement crisis facing the world. With more than 110 million people forcibly displaced globally, he explains why traditional responses are failing and what needs to change.</p>

<h2>The New Normal</h2>

<p><strong>Humanitarian Blog:</strong> We\'ve seen record displacement numbers year after year. What\'s driving this trend?</p>

<p><strong>Filippo Grandi:</strong> We\'re dealing with a fundamental shift in the nature of displacement. Conflicts are lasting longer - the average refugee spends 20 years in displacement. New conflicts erupt before old ones are resolved. Syria, Afghanistan, South Sudan, Myanmar - these situations persist year after year.</p>

<p>But it\'s not just conflict. Climate change is a massive displacement driver that we\'re only beginning to understand. Drought, floods, sea-level rise - these are pushing people from their homes. Then you have governance failures, human rights violations, gang violence. The drivers are multiplying and interconnecting.</p>

<p><strong>HB:</strong> How is UNHCR adapting to this new reality?</p>

<p><strong>FG:</strong> We have to be honest - the traditional refugee response model of camps and relief assistance cannot be the long-term answer for 110 million people. We need to think differently about solutions.</p>

<p>That means working much more on development alongside humanitarian assistance. Refugees should have the right to work, to access education, to move freely. They bring skills, energy, entrepreneurship. Countries that have adopted more inclusive approaches - like Uganda\'s settlement model or Colombia\'s regularization of Venezuelan migrants - show better outcomes.</p>

<h2>Burden Sharing</h2>

<p><strong>HB:</strong> There\'s a huge imbalance - low-income countries host 70% of refugees while wealthy nations build walls. How do we fix this?</p>

<p><strong>FG:</strong> This is perhaps our greatest challenge. The Refugee Convention was built on the principle of international cooperation, but we see that eroding. Some of the world\'s wealthiest countries host negligible numbers of refugees while their neighbors - countries like Lebanon, Jordan, Turkey - carry enormous burdens.</p>

<p>We need genuine burden and responsibility sharing. That means wealthy countries must do more - whether through resettlement, financial support to host countries, or removing barriers to integration. The Global Refugee Forum created a framework, but implementation has been inconsistent.</p>

<blockquote>"Every time I visit a refugee camp, I meet doctors, engineers, teachers who just want the chance to rebuild their lives. We\'re wasting human potential on a massive scale."</blockquote>

<p><strong>HB:</strong> What about the political backlash against refugees in many countries?</p>

<p><strong>FG:</strong> The rhetoric around refugees and migrants has become toxic in many places. Politicians exploit fears, spread misinformation, scapegoat vulnerable people for complex problems. This is not only morally wrong but also practically counterproductive.</p>

<p>Research consistently shows that refugees contribute to host economies when given the opportunity. But fear drives policy more than evidence. We need political leaders with courage to stand up for refugee rights and explain to their constituencies why protection matters.</p>

<h2>Climate Displacement</h2>

<p><strong>HB:</strong> You mentioned climate change. How significant will this become?</p>

<p><strong>FG:</strong> It\'s already significant and will only grow. Internal displacement from weather-related disasters already affects tens of millions annually. Looking ahead, we could see entire island nations become uninhabitable. Coastal cities worldwide face flooding. Agricultural regions face drought.</p>

<p>The challenge is that current international refugee law doesn\'t clearly cover climate displacement, especially across borders. We need new legal frameworks, new funding mechanisms, new approaches to prevention and adaptation. This is existential for some countries.</p>

<p><strong>HB:</strong> What about the funding situation?</p>

<p><strong>FG:</strong> Chronic underfunding forces us to make impossible choices. We cut food rations, reduce education programs, leave gaps in healthcare. And the need keeps growing while funding doesn\'t keep pace.</p>

<p>It\'s not just about money though. It\'s about political will. The international community can mobilize billions for military interventions or economic bailouts, but struggles to fund refugee protection. These are choices, not inevitabilities.</p>

<h2>Reasons for Hope</h2>

<p><strong>HB:</strong> After years in this role, dealing with crisis after crisis, how do you maintain hope?</p>

<p><strong>FG:</strong> I meet extraordinary people every day - refugees showing incredible resilience, aid workers risking everything to help, local communities sharing what little they have. These people give me hope.</p>

<p>And there are success stories. Countries finding durable solutions. Refugees returning home to rebuild. Third countries opening resettlement spaces. Progress is possible when political will exists.</p>

<p>But I won\'t sugarcoat it - we\'re facing one of the greatest humanitarian challenges in history. Business as usual won\'t work. We need transformative change in how the world responds to displacement. The question is whether we\'ll rise to that challenge or let it overwhelm us.</p>

<p><strong>HB:</strong> What\'s your message to readers?</p>

<p><strong>FG:</strong> Stay informed, stay engaged, hold your governments accountable. Refugees are not statistics - they\'re people with dreams, talents, and rights. Every one of us can make a difference, whether through advocacy, donations, or simply changing how we talk about displacement.</p>

<p>This is a defining issue of our time. History will judge how we responded when millions of people needed protection. We must do better.</p>',
        'category' => 'Interview',
        'article_type' => 'Interview',
        'region' => 'Global',
        'tags' => ['Refugees', 'UNHCR', 'Migration', 'Displacement', 'Interview'],
        'excerpt' => 'UNHCR Chief Filippo Grandi discusses the unprecedented global displacement crisis, burden sharing, climate migration, and why traditional responses must evolve.',
    ],
    [
        'title' => 'Feature: The Women Rebuilding Healthcare in Post-Conflict Liberia',
        'content' => '<p>In a small clinic on the outskirts of Monrovia, Dr. Grace Williams examines a pregnant woman while training three young medical students. This scene would be unremarkable anywhere else, but in Liberia, it represents something extraordinary: the rebuilding of a healthcare system that was decimated by civil war and then nearly destroyed again by Ebola.</p>

<p>What makes it even more remarkable is that Dr. Williams and her entire team are women - part of a generation that has taken on the monumental task of reconstructing Liberia\'s health infrastructure from the ground up.</p>

<h2>Starting from Ruins</h2>

<p>When Liberia\'s civil wars ended in 2003, the healthcare system was in shambles. Most facilities had been looted or destroyed. Medical professionals had fled the country. Basic supplies were nonexistent. Then in 2014, the Ebola epidemic killed many of the health workers who had returned.</p>

<p>"When I came back from medical school in Ghana in 2005, I found hospitals without electricity, without running water, without basic medicines," Dr. Williams recalls. "But what struck me most was the absence of women in healthcare leadership. We had to change that."</p>

<h2>Breaking Barriers</h2>

<p>In Liberian culture, as in many societies, medicine has traditionally been a male domain. Women faced systemic barriers to education and professional advancement. The war, paradoxically, created an opening - with so many men dead or displaced, women stepped into roles they had been denied.</p>

<p>Mary Johnson was 19 when she began training as a community health worker in a refugee camp. Today, at 38, she manages a health center serving 50,000 people. "During the war, we had no choice but to help each other survive. Women became healers, organizers, leaders. After the war, we refused to give up those roles."</p>

<h2>Building Systems</h2>

<p>Dr. Williams and others like her didn\'t just want to rebuild what existed before - they wanted to build something better. Working with international partners but maintaining local leadership, they developed community-based healthcare models that prioritize prevention, education, and accessibility.</p>

<p>The model trains community health workers - mostly women from the communities they serve - who provide basic healthcare, health education, and connections to higher-level facilities. These networks reach remote villages that had never seen a health worker before the war.</p>

<blockquote>"You can\'t parachute in healthcare from outside. It has to be rooted in the community, delivered by people who understand the culture and speak the language." - Dr. Grace Williams</blockquote>

<h2>Maternal Health Priority</h2>

<p>One of their first priorities was maternal health. Liberia had one of the world\'s highest maternal mortality rates - a staggering 725 deaths per 100,000 live births in 2007. Many women died from preventable complications because they had no access to skilled birth attendants or emergency obstetric care.</p>

<p>The network of women health workers made maternal health their flagship initiative. They trained traditional birth attendants, established birthing centers, created referral systems for complicated deliveries. The results have been dramatic - maternal mortality has dropped by more than half.</p>

<p>Patience Cooper, a traditional birth attendant who received formal training, explains: "Before, I delivered babies the way my grandmother taught me. Sometimes mothers or babies died and I didn\'t know why. Now I understand the warning signs, I know when to refer to the clinic, I can save lives."</p>

<h2>The Ebola Test</h2>

<p>The 2014 Ebola outbreak nearly broke the health system they were building. Healthcare workers were infected at higher rates than any other group. Fear kept patients away from facilities. Years of progress seemed to unravel in months.</p>

<p>But the women-led community health network proved crucial to Ebola response. Community health workers tracked cases, traced contacts, delivered health messages, and identified traditional practices that needed to change. Their connections to communities made them trusted messengers during a time of fear and misinformation.</p>

<p>"Ebola taught us that our investment in community health wasn\'t just about routine care - it was building resilience for future crises," Dr. Williams reflects. "The relationships we had built saved lives."</p>

<h2>Training the Next Generation</h2>

<p>Perhaps their most important work is training the next generation of health professionals. Dr. Williams runs a mentorship program pairing young women medical students with practicing doctors and health workers. Mary Johnson trains community health workers who will become the backbone of rural healthcare.</p>

<p>In a country where only 3% of rural health facilities have a doctor, these mid-level health workers are essential. And they\'re overwhelmingly women.</p>

<p>"Every young woman we train multiplies our impact," says Dr. Williams. "She doesn\'t just provide healthcare - she becomes a role model showing girls in her community what\'s possible."</p>

<h2>Ongoing Challenges</h2>

<p>Progress is real but fragile. Liberia\'s health system still faces enormous challenges: chronic underfunding, brain drain as trained professionals seek better opportunities abroad, weak supply chains, and infrastructure gaps.</p>

<p>Gender discrimination persists. Women health workers often earn less than male counterparts. They face skepticism from some patients who don\'t believe women can be doctors. They juggle professional responsibilities with cultural expectations around childcare and domestic work.</p>

<p>Mary Johnson often works 12-hour days at the health center, then goes home to cook for her family and help children with homework. "People think we can\'t do both - be mothers and professionals. We prove them wrong every day, but it\'s exhausting."</p>

<h2>Looking Forward</h2>

<p>Despite challenges, the women rebuilding Liberia\'s health system are optimistic. They\'ve seen what determination and community-based approaches can achieve. They\'ve survived war and epidemic. They\'re not backing down now.</p>

<p>Dr. Williams dreams of a day when every Liberian has access to quality healthcare, when maternal and child deaths are rare rather than common, when her country exports health workers rather than losing them to emigration. It\'s an ambitious vision, but then, rebuilding an entire health system from ruins was ambitious too - and they\'re doing it.</p>

<p>"We didn\'t survive war and Ebola to settle for a broken system," she says, preparing for her next patient. "We\'re building the healthcare system Liberia deserves. It will take time, but we\'ll get there - one patient, one health worker, one community at a time."</p>

<p>As evening falls over Monrovia, lights flicker on in clinics across the country - many of them powered by generators, some by solar panels, a few by unreliable grid electricity. In each one, women are working to heal, to teach, to build. They are, quite literally, the future of healthcare in Liberia.</p>',
        'category' => 'Feature',
        'article_type' => 'Feature',
        'region' => 'Africa',
        'tags' => ['Liberia', 'Healthcare', 'Women', 'Post-Conflict', 'Development'],
        'excerpt' => 'In post-conflict Liberia, women health workers are rebuilding a shattered healthcare system from the ground up, transforming maternal health and training the next generation.',
    ],
];

// Get or create admin user for author
$admin_user = get_users(['role' => 'administrator', 'number' => 1]);
$author_id = $admin_user[0]->ID;

// Download and save a sample image (placeholder)
function download_sample_image($filename) {
    // Use a humanitarian-themed placeholder service
    $image_url = "https://picsum.photos/1200/800?random=" . rand(1, 1000);

    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($image_url);

    if ($image_data === false) {
        return false;
    }

    $filepath = $upload_dir['path'] . '/' . $filename;
    file_put_contents($filepath, $image_data);

    $filetype = wp_check_filetype($filename, null);

    $attachment = array(
        'post_mime_type' => $filetype['type'],
        'post_title'     => sanitize_file_name($filename),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    $attach_id = wp_insert_attachment($attachment, $filepath);
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $filepath);
    wp_update_attachment_metadata($attach_id, $attach_data);

    return $attach_id;
}

echo '<div class="info"><strong>Step 1:</strong> Creating demo articles...</div>';

$created_count = 0;

foreach ($demo_articles as $index => $article) {

    // Get category ID
    $category = get_category_by_slug(sanitize_title($article['category']));
    if (!$category) {
        echo '<div class="warning">Category "' . esc_html($article['category']) . '" not found. Creating it...</div>';
        $category_id = wp_create_category($article['category']);
    } else {
        $category_id = $category->term_id;
    }

    // Get or create article type
    $article_type_term = get_term_by('slug', sanitize_title($article['article_type']), 'article_type');
    if (!$article_type_term) {
        echo '<div class="warning">Article Type "' . esc_html($article['article_type']) . '" not found. Creating it...</div>';
        $article_type_result = wp_insert_term($article['article_type'], 'article_type');
        $article_type_id = $article_type_result['term_id'];
    } else {
        $article_type_id = $article_type_term->term_id;
    }

    // Get or create region
    $region_term = get_term_by('slug', sanitize_title($article['region']), 'region');
    if (!$region_term) {
        echo '<div class="warning">Region "' . esc_html($article['region']) . '" not found. Creating it...</div>';
        $region_result = wp_insert_term($article['region'], 'region');
        $region_id = $region_result['term_id'];
    } else {
        $region_id = $region_term->term_id;
    }

    // Create post
    $post_data = array(
        'post_title'    => $article['title'],
        'post_content'  => $article['content'],
        'post_excerpt'  => $article['excerpt'],
        'post_status'   => 'publish',
        'post_author'   => $author_id,
        'post_category' => array($category_id),
        'post_date'     => date('Y-m-d H:i:s', strtotime('-' . ($index * 2) . ' days')),
        'tags_input'    => $article['tags'],
    );

    $post_id = wp_insert_post($post_data);

    if ($post_id) {
        // Set article type and region taxonomies
        wp_set_object_terms($post_id, $article_type_id, 'article_type');
        wp_set_object_terms($post_id, $region_id, 'region');

        // Download and set featured image
        echo '<div class="info">Creating article ' . ($index + 1) . ': "' . esc_html($article['title']) . '"...</div>';

        $image_id = download_sample_image('featured-' . $post_id . '.jpg');
        if ($image_id) {
            set_post_thumbnail($post_id, $image_id);
            echo '<div class="success">✓ Added featured image</div>';
        }

        // Add reading time meta
        $word_count = str_word_count(strip_tags($article['content']));
        $reading_time = ceil($word_count / 200);
        update_post_meta($post_id, 'reading_time', $reading_time);

        echo '<div class="success">✓ Article created successfully! (ID: ' . $post_id . ')</div>';
        $created_count++;

        // Flush output buffer to show progress
        if (ob_get_level() > 0) {
            ob_flush();
            flush();
        }
    } else {
        echo '<div class="error">✗ Failed to create article: "' . esc_html($article['title']) . '"</div>';
    }
}

echo '<div class="success"><strong>✓ Created ' . $created_count . ' demo articles!</strong></div>';

// Mark as complete
update_option('humanitarian_demo_content_created', true);

?>

<h2>✅ Demo Content Created Successfully!</h2>

<div class="success">
    <p><strong>Created:</strong></p>
    <ul>
        <li><?php echo $created_count; ?> demo articles with featured images</li>
        <li>Categories: News, Opinion, Analysis, Report, Interview, Feature</li>
        <li>Article Types and Regions taxonomies</li>
        <li>Realistic content and metadata</li>
    </ul>
</div>

<div class="warning">
    <h3>⚠️ Important: Security Notice</h3>
    <p><strong>DELETE THIS FILE IMMEDIATELY!</strong></p>
    <p>This script should only be run once for demo purposes. For security reasons, delete:</p>
    <code><?php echo __FILE__; ?></code>
</div>

<div class="info">
    <h3>Next Steps:</h3>
    <ol>
        <li>Visit your homepage: <a href="<?php echo home_url(); ?>" target="_blank"><?php echo home_url(); ?></a></li>
        <li>Check that articles display correctly</li>
        <li>Test the bookmarks page</li>
        <li>Test search functionality</li>
        <li>Make a few posts "sticky" for the hero section: Posts → Edit → Quick Edit → Make this post sticky</li>
    </ol>
</div>

</body>
</html>
