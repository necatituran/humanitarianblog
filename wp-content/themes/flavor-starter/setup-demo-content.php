<?php
/**
 * Comprehensive Demo Content Generator
 *
 * Creates 36+ articles covering all Article Types and Regions
 * Each category/region gets at least 3 articles
 *
 * USAGE: Visit in browser while logged in as admin
 * URL: http://humanitarian-blog.local/wp-content/themes/flavor-starter/setup-demo-content.php
 */

require_once(__DIR__ . '/../../../wp-load.php');

if (!current_user_can('administrator')) {
    die('Admin access required.');
}

// Allow re-running by removing the flag
delete_option('demo_content_v2_created');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Creating Demo Content</title>
    <style>
        body { font-family: system-ui, sans-serif; padding: 40px; max-width: 900px; margin: 0 auto; background: #f5f5f5; }
        h1 { color: #c0392b; }
        .card { background: white; padding: 20px; margin: 15px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .success { border-left: 4px solid #27ae60; }
        .info { border-left: 4px solid #3498db; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; margin: 15px 0; }
        .tag { background: #ecf0f1; padding: 5px 10px; border-radius: 4px; font-size: 14px; }
        .count { font-weight: bold; color: #c0392b; }
    </style>
</head>
<body>
<h1>📰 Humanitarian Blog - Demo Content Generator</h1>

<?php

// Article Types: News, Opinion, Investigation, In-Depth Analysis, Feature, Breaking
// Regions: Africa, Middle East, Asia, Europe, Americas, Global

$articles = [
    // ==================== NEWS (3+ articles) ====================
    [
        'title' => 'UN Launches Emergency Appeal for Sudan Crisis',
        'excerpt' => 'The United Nations has launched a $2.6 billion emergency appeal as fighting intensifies in Sudan.',
        'content' => '<p>The United Nations launched a massive emergency appeal today, seeking $2.6 billion to address the rapidly deteriorating humanitarian situation in Sudan.</p>
        <p>Since fighting erupted between rival military factions in April, more than 5 million people have been displaced internally, while over 1.2 million have fled to neighboring countries including Chad, Egypt, and South Sudan.</p>
        <h2>Critical Needs</h2>
        <p>The appeal focuses on food security, healthcare, shelter, and protection services. UNHCR reports that refugee camps in Chad are severely overcrowded, with new arrivals continuing daily.</p>
        <p>"This is one of the fastest-growing displacement crises we have seen," said the UN Emergency Relief Coordinator. "Without immediate funding, we cannot prevent a catastrophe."</p>
        <h2>Healthcare System Collapse</h2>
        <p>Over 70% of hospitals in conflict-affected areas are non-functional. Medical supplies are critically low, and healthcare workers have fled the violence.</p>
        <blockquote>"We are seeing preventable deaths every day because people cannot access basic healthcare." - WHO Representative</blockquote>
        <p>The international community has been urged to act swiftly to prevent further loss of life.</p>',
        'article_type' => 'News',
        'region' => 'Africa',
        'tags' => ['Sudan', 'UN', 'Displacement', 'Emergency'],
    ],
    [
        'title' => 'Earthquake Relief Efforts Continue in Afghanistan',
        'excerpt' => 'Rescue teams work around the clock following devastating earthquakes in western Afghanistan.',
        'content' => '<p>International rescue teams continue search and recovery operations in Herat province following a series of powerful earthquakes that have killed over 2,400 people.</p>
        <p>The 6.3 magnitude earthquakes struck in rapid succession, flattening entire villages and leaving thousands homeless in one of the poorest regions of Afghanistan.</p>
        <h2>Humanitarian Response</h2>
        <p>Aid organizations have mobilized emergency supplies including tents, blankets, food, and medical equipment. However, access to remote villages remains challenging due to damaged roads.</p>
        <p>The Taliban authorities have called for international assistance, temporarily easing restrictions on female aid workers to facilitate the response.</p>
        <h2>Shelter Crisis</h2>
        <p>With winter approaching, providing adequate shelter is a critical priority. Temperatures in the region can drop below freezing, putting survivors at risk of hypothermia.</p>
        <p>UNICEF reports that at least 90% of those killed were women and children, who were inside homes when the earthquakes struck.</p>',
        'article_type' => 'News',
        'region' => 'Asia',
        'tags' => ['Afghanistan', 'Earthquake', 'Disaster Response', 'Emergency'],
    ],
    [
        'title' => 'Mediterranean Migrant Crossings Reach Record Levels',
        'excerpt' => 'UNHCR reports unprecedented numbers of migrants attempting dangerous sea crossings to Europe.',
        'content' => '<p>The number of migrants attempting to cross the Mediterranean Sea has reached record levels this year, according to new data from UNHCR.</p>
        <p>More than 186,000 people have arrived in Europe by sea since January, with Italy receiving the highest number of arrivals. Tragically, over 2,500 people have died or gone missing attempting the crossing.</p>
        <h2>Push Factors</h2>
        <p>Conflict in Sudan, economic collapse in Tunisia, and ongoing instability in Libya continue to drive people to attempt the dangerous journey. Many survivors report paying smugglers their life savings for a place on overcrowded boats.</p>
        <h2>European Response</h2>
        <p>EU member states remain divided on how to manage arrivals. While some countries call for stronger border controls, humanitarian organizations emphasize the need for safe legal pathways.</p>
        <blockquote>"Every death at sea is a policy failure. We need search and rescue operations, not deterrence." - MSF Mediterranean Coordinator</blockquote>
        <p>The situation in Lampedusa has reached crisis point, with reception facilities overwhelmed by recent arrivals.</p>',
        'article_type' => 'News',
        'region' => 'Europe',
        'tags' => ['Migration', 'Mediterranean', 'Refugees', 'EU'],
    ],
    [
        'title' => 'Venezuela Crisis Drives Mass Exodus to Colombia',
        'excerpt' => 'Over 2 million Venezuelans have crossed into Colombia seeking safety and economic opportunity.',
        'content' => '<p>Colombia now hosts more than 2 million Venezuelan migrants and refugees, making it the largest recipient of Venezuelan displacement in the region.</p>
        <p>The economic and political crisis in Venezuela has driven over 7 million people from their homes, creating one of the largest displacement situations in the world.</p>
        <h2>Integration Challenges</h2>
        <p>While Colombia has taken a relatively welcoming approach, providing temporary protection status to Venezuelans, integration remains challenging. Many Venezuelans struggle to find formal employment and access public services.</p>
        <p>Border communities in Norte de Santander and La Guajira are particularly strained, with limited resources to meet the needs of both local populations and new arrivals.</p>
        <h2>International Support</h2>
        <p>The international community has pledged support through the Regional Refugee and Migrant Response Plan, but funding remains far short of requirements.</p>',
        'article_type' => 'News',
        'region' => 'Americas',
        'tags' => ['Venezuela', 'Colombia', 'Migration', 'Crisis'],
    ],

    // ==================== OPINION (3+ articles) ====================
    [
        'title' => 'Opinion: The Forgotten Crisis in the Sahel Demands Attention',
        'excerpt' => 'While global attention focuses elsewhere, millions in the Sahel face unprecedented humanitarian needs.',
        'content' => '<p><em>By Dr. Aminata Diallo, Regional Analyst</em></p>
        <p>The humanitarian crisis unfolding across the Sahel region of Africa deserves far more attention than it currently receives. While donor fatigue sets in and headlines focus on newer emergencies, millions of people in Mali, Burkina Faso, Niger, and Chad face escalating violence, climate shocks, and food insecurity.</p>
        <h2>A Perfect Storm</h2>
        <p>The Sahel is experiencing a convergence of crises: armed conflict displaces communities, climate change destroys livelihoods, and weak governance fails to protect civilians. These factors reinforce each other, creating a downward spiral that humanitarian assistance alone cannot reverse.</p>
        <blockquote>"We cannot simply provide food aid while ignoring the root causes of hunger. Conflict resolution and climate adaptation must be priorities."</blockquote>
        <h2>Time for a New Approach</h2>
        <p>Traditional humanitarian response is insufficient. We need integrated approaches that address security, governance, and development alongside immediate relief. This requires political will from regional governments and sustained commitment from international partners.</p>
        <p>The people of the Sahel deserve better. They deserve attention, investment, and solutions - not to be forgotten while the world looks elsewhere.</p>',
        'article_type' => 'Opinion',
        'region' => 'Africa',
        'tags' => ['Sahel', 'Editorial', 'Climate', 'Conflict'],
    ],
    [
        'title' => 'Opinion: Why Humanitarian Aid Must Embrace Local Leadership',
        'excerpt' => 'International organizations should support, not supplant, local humanitarian actors.',
        'content' => '<p><em>By Marcus Chen, Humanitarian Policy Researcher</em></p>
        <p>The humanitarian sector talks a lot about localization - the principle that local organizations should lead humanitarian response in their own communities. Yet progress has been painfully slow. International NGOs and UN agencies still receive the vast majority of humanitarian funding, while local organizations scramble for scraps.</p>
        <h2>The Evidence is Clear</h2>
        <p>Research consistently shows that local organizations deliver more cost-effective, culturally appropriate, and sustainable assistance. They understand community dynamics, speak local languages, and maintain presence long after international actors have moved on to the next crisis.</p>
        <p>Yet the humanitarian system remains stubbornly international in orientation. Donors prefer to fund organizations they know. International agencies protect their turf. Local organizations face barriers to accessing funding directly.</p>
        <h2>What Must Change</h2>
        <p>We need fundamental reform: direct funding to local organizations, simplified compliance requirements, and genuine partnership rather than subcontracting. International organizations should see their role as building local capacity, not building their own empires.</p>
        <blockquote>"Local leadership isn\'t just more effective - it\'s more just. Communities should have agency over their own recovery."</blockquote>
        <p>The Grand Bargain made commitments on localization years ago. It\'s time to deliver.</p>',
        'article_type' => 'Opinion',
        'region' => 'Global',
        'tags' => ['Localization', 'Aid Reform', 'NGOs', 'Funding'],
    ],
    [
        'title' => 'Opinion: Europe Must Share Responsibility for Refugees',
        'excerpt' => 'The burden of hosting refugees falls unfairly on a few countries while others shirk their obligations.',
        'content' => '<p><em>By Helena Andersen, Migration Policy Expert</em></p>
        <p>Europe\'s response to refugee arrivals reveals a fundamental failure of solidarity. While countries like Germany, Sweden, and Greece have received millions of asylum seekers, others have effectively closed their doors, violating both the spirit and letter of EU agreements.</p>
        <h2>Unequal Burden</h2>
        <p>The Dublin Regulation places responsibility for asylum claims on the first country of entry - usually Mediterranean states like Italy and Greece. This creates an unfair burden on frontline countries while allowing northern European states to avoid their share of responsibility.</p>
        <p>Meanwhile, countries like Hungary and Poland have refused to participate in relocation schemes, facing little consequence for their defiance.</p>
        <h2>A Common Framework</h2>
        <p>Europe needs a fair, mandatory system for sharing responsibility. This means quotas based on population and economic capacity, with real consequences for non-compliance. It also means investing in asylum systems so claims can be processed quickly and fairly.</p>
        <blockquote>"Solidarity cannot be optional. Either we share this responsibility or the European project loses all credibility."</blockquote>
        <p>The current system serves no one well - not refugees, not frontline states, and not Europe\'s claim to uphold human rights values.</p>',
        'article_type' => 'Opinion',
        'region' => 'Europe',
        'tags' => ['Refugees', 'EU', 'Policy', 'Solidarity'],
    ],

    // ==================== INVESTIGATION (3+ articles) ====================
    [
        'title' => 'Investigation: Tracking Weapons in Yemen\'s Civil War',
        'excerpt' => 'An in-depth investigation reveals how international arms sales fuel the conflict in Yemen.',
        'content' => '<p>A year-long investigation by our team has traced weapons found on Yemen\'s battlefields back to their origins, revealing a web of arms transfers that continue despite international condemnation of the war.</p>
        <h2>The Paper Trail</h2>
        <p>Working with arms researchers and analyzing open-source intelligence, we identified weapons manufactured in the United States, United Kingdom, France, and Canada deployed in operations that have caused civilian casualties.</p>
        <p>Despite repeated calls for arms embargoes and documented evidence of violations of international humanitarian law, major weapons exporters continue to supply the Saudi-led coalition.</p>
        <h2>Cluster Munitions</h2>
        <p>Our investigation documented the use of cluster munitions - banned under international law - in civilian areas. Remnants of these weapons, which release dozens of smaller bomblets, were found near schools and hospitals.</p>
        <blockquote>"The serial numbers don\'t lie. These weapons came from countries that claim to support human rights." - Arms Researcher</blockquote>
        <h2>Official Response</h2>
        <p>When confronted with our findings, government officials either declined to comment or pointed to existing export control procedures. Arms manufacturers referred questions to their governments.</p>
        <p>Legal experts say the continued arms sales may constitute complicity in war crimes under international law.</p>',
        'article_type' => 'Investigation',
        'region' => 'Middle East',
        'tags' => ['Yemen', 'Arms Trade', 'War Crimes', 'Investigation'],
    ],
    [
        'title' => 'Investigation: The Hidden Toll of Rohingya Refugee Camps',
        'excerpt' => 'Five years after the exodus, conditions in Cox\'s Bazar camps reveal systemic failures.',
        'content' => '<p>Five years after nearly a million Rohingya fled Myanmar, conditions in the world\'s largest refugee settlement reveal a humanitarian response stretched to breaking point.</p>
        <h2>Six Months Inside</h2>
        <p>Our team spent six months investigating conditions in Cox\'s Bazar, interviewing hundreds of refugees, aid workers, and officials. What we found challenges the narrative of successful humanitarian response.</p>
        <p>Overcrowding reaches dangerous levels. In some sections, families of eight share 10 square meters of space. Fire risks are extreme - in 2021, a single fire killed 15 people and left 45,000 homeless.</p>
        <h2>Restricted Freedoms</h2>
        <p>Refugees face severe restrictions on movement and work. Officially prohibited from seeking employment outside camps, many survive on dwindling rations that have been cut repeatedly due to funding shortfalls.</p>
        <p>Education is limited, with hundreds of thousands of children receiving only basic informal schooling. A "lost generation" of young Rohingya faces a future without qualifications or prospects.</p>
        <h2>Violence and Despair</h2>
        <p>Armed gangs have established control over sections of the camps, trafficking drugs and people. Women and girls face heightened risks of gender-based violence. Mental health conditions are endemic.</p>
        <blockquote>"We survived genocide only to die slowly in these camps. Is this what the world calls protection?" - Rohingya community leader</blockquote>
        <p>With no prospect of safe return to Myanmar and minimal resettlement opportunities, a million people remain in limbo.</p>',
        'article_type' => 'Investigation',
        'region' => 'Asia',
        'tags' => ['Rohingya', 'Bangladesh', 'Refugees', 'Human Rights'],
    ],
    [
        'title' => 'Investigation: Aid Diversion in East Africa Drought Response',
        'excerpt' => 'Evidence suggests systematic diversion of food aid meant for drought-affected communities.',
        'content' => '<p>An investigation spanning three countries has uncovered evidence of systematic diversion of food aid intended for communities facing severe drought in the Horn of Africa.</p>
        <h2>Following the Food</h2>
        <p>Working with local journalists and community sources, we traced food shipments from ports of entry to distribution points. At multiple stages, we documented significant discrepancies between recorded and actual deliveries.</p>
        <p>In one district, community members reported receiving only half of their allocated rations. Registration lists included names of people who had died or never existed. Trucks were diverted to commercial markets.</p>
        <h2>Who Benefits</h2>
        <p>The investigation identified multiple actors involved in diversion: local officials demanding kickbacks, transport contractors selling portions of loads, and armed groups taxing aid convoys.</p>
        <p>While no single actor controls the diversion, the cumulative effect is substantial. Independent estimates suggest 15-20% of food aid may not reach intended beneficiaries.</p>
        <h2>Humanitarian Response</h2>
        <p>Aid organizations acknowledged challenges but disputed our estimates. They pointed to improved monitoring systems and investigations into specific incidents.</p>
        <blockquote>"We take any allegation of diversion extremely seriously. But we must balance control measures against the need to deliver aid quickly to people in desperate need."</blockquote>
        <p>Donors have called for strengthened accountability while acknowledging the difficult operating environment.</p>',
        'article_type' => 'Investigation',
        'region' => 'Africa',
        'tags' => ['Drought', 'Corruption', 'Food Aid', 'Accountability'],
    ],

    // ==================== IN-DEPTH ANALYSIS (3+ articles) ====================
    [
        'title' => 'Analysis: Understanding the Roots of Haiti\'s Gang Crisis',
        'excerpt' => 'How decades of political instability and foreign intervention created today\'s security nightmare.',
        'content' => '<p>Haiti\'s gang crisis did not emerge in a vacuum. Understanding how armed groups came to control an estimated 80% of Port-au-Prince requires examining decades of political dysfunction, failed interventions, and economic marginalization.</p>
        <h2>Historical Context</h2>
        <p>Haiti\'s contemporary gang phenomenon has roots in the political violence of the Duvalier era and subsequent periods of instability. Armed groups have long been used by political actors to intimidate opponents and control territory.</p>
        <p>The 2004 coup that removed President Aristide created a power vacuum that various armed factions rushed to fill. Subsequent UN peacekeeping missions suppressed gang activity temporarily but failed to address underlying causes.</p>
        <h2>Economic Drivers</h2>
        <p>Youth unemployment exceeds 60%. With limited economic opportunities, gang membership offers income, status, and protection that the state cannot provide. Control of territory enables lucrative kidnapping, extortion, and drug trafficking operations.</p>
        <h2>State Collapse</h2>
        <p>The 2021 assassination of President Moïse accelerated state collapse. Police are outgunned and demoralized. The judiciary barely functions. Political leaders are widely perceived as corrupt and complicit with gang interests.</p>
        <blockquote>"You cannot understand Haiti\'s gangs without understanding Haiti\'s politics. They are deeply intertwined."</blockquote>
        <h2>International Dimension</h2>
        <p>Weapons flow freely from the United States. Previous international interventions have left mixed legacies. The proposed Kenyan-led security mission faces skepticism given the history of foreign forces in Haiti.</p>
        <p>Sustainable solutions require Haitian leadership, economic development, and security sector reform - all extraordinarily difficult in current conditions.</p>',
        'article_type' => 'In-Depth Analysis',
        'region' => 'Americas',
        'tags' => ['Haiti', 'Gangs', 'Security', 'Political Crisis'],
    ],
    [
        'title' => 'Analysis: Why Peace Eludes South Sudan',
        'excerpt' => 'Despite multiple agreements, South Sudan remains trapped in cycles of violence and displacement.',
        'content' => '<p>South Sudan, the world\'s youngest nation, has spent most of its existence at war with itself. Despite numerous peace agreements, the country remains mired in violence, with millions displaced and a humanitarian crisis of staggering proportions.</p>
        <h2>The 2018 Peace Agreement</h2>
        <p>The Revitalized Agreement on the Resolution of the Conflict in South Sudan promised an end to civil war. Five years later, implementation remains incomplete. Key provisions on security sector reform, transitional justice, and elections have stalled.</p>
        <p>The unity government functions poorly, with rival factions maintaining parallel command structures. Periodic clashes occur between forces nominally on the same side.</p>
        <h2>Subnational Violence</h2>
        <p>Beyond the main political-military conflict, localized violence continues across the country. Intercommunal fighting over cattle, land, and resources kills thousands annually. Armed youth groups operate with impunity.</p>
        <h2>Economic Collapse</h2>
        <p>Oil revenues that once funded the state have declined. Hyperinflation has devastated livelihoods. Food insecurity affects more than 7 million people. The economy provides few alternatives to violence.</p>
        <blockquote>"Peace on paper means nothing without peace in communities. Until people feel secure, development is impossible."</blockquote>
        <h2>International Fatigue</h2>
        <p>Donors have reduced funding, frustrated by lack of progress. The UN peacekeeping mission faces criticism for failing to protect civilians. Regional actors pursue competing interests.</p>
        <p>Breaking the cycle requires genuine political will from South Sudanese leaders - something that has been conspicuously absent.</p>',
        'article_type' => 'In-Depth Analysis',
        'region' => 'Africa',
        'tags' => ['South Sudan', 'Peace Process', 'Conflict', 'Displacement'],
    ],
    [
        'title' => 'Analysis: The Economics of Refugee Hosting',
        'excerpt' => 'New research challenges assumptions about the economic impact of hosting large refugee populations.',
        'content' => '<p>Conventional wisdom holds that hosting refugees is economically burdensome for countries of asylum. New research suggests reality is more nuanced, with significant economic benefits alongside costs.</p>
        <h2>The Evidence Base</h2>
        <p>Studies from Uganda, Turkey, Jordan, and Colombia provide empirical evidence on refugee economic impacts. The findings challenge both extremes of the debate - neither pure burden nor economic windfall.</p>
        <p>In Uganda\'s Nakivale settlement, refugee-owned businesses employ Ugandans and contribute to local economic activity. In Turkey, Syrian refugees have started over 10,000 businesses and pay billions in taxes.</p>
        <h2>Labor Market Effects</h2>
        <p>Effects on host community employment depend heavily on policy choices. When refugees can work legally, they tend to complement rather than compete with local workers, often filling gaps in labor markets.</p>
        <p>Restrictions on work push refugees into informal sectors, reducing their contributions and potentially creating more competition at the margins.</p>
        <h2>Fiscal Impacts</h2>
        <p>Refugees do create costs for public services - healthcare, education, infrastructure. But these costs are often offset by international assistance and, over time, tax contributions from economically active refugees.</p>
        <blockquote>"The question isn\'t whether hosting refugees costs money - it does. The question is whether those costs are outweighed by economic and other benefits."</blockquote>
        <h2>Policy Implications</h2>
        <p>Evidence suggests inclusive policies - allowing work, education, freedom of movement - generate better economic outcomes for both refugees and hosts. Restrictive policies create costs without corresponding benefits.</p>
        <p>Donors should support host country economies, not just humanitarian assistance, recognizing that development investments benefit both populations.</p>',
        'article_type' => 'In-Depth Analysis',
        'region' => 'Global',
        'tags' => ['Refugees', 'Economics', 'Policy', 'Development'],
    ],

    // ==================== FEATURE (3+ articles) ====================
    [
        'title' => 'Feature: The Doctors Who Stayed in Aleppo',
        'excerpt' => 'During the siege of Aleppo, a handful of medical professionals refused to leave. This is their story.',
        'content' => '<p>When the siege lines closed around eastern Aleppo in 2016, the population of 300,000 had access to just 35 doctors. Most had fled years of barrel bombs and artillery. Those who remained made a choice that would define their lives.</p>
        <h2>Dr. Hamza\'s Decision</h2>
        <p>Dr. Hamza was completing his residency when the uprising began. He could have left - his qualifications would have opened doors in Turkey or Europe. Instead, he stayed, operating in underground hospitals as bombs fell above.</p>
        <p>"Every day I thought about leaving," he recalls. "Then I would see a child who needed surgery, a mother bleeding out, and I knew I could not go."</p>
        <h2>Underground Hospitals</h2>
        <p>Medical facilities moved to basements and caves to survive airstrikes. Generators provided unreliable power. Supplies came through tunnels when they came at all. Doctors performed complex surgeries with inadequate anesthesia and improvised equipment.</p>
        <p>The medical network developed remarkable resilience. When one hospital was destroyed, operations shifted to another. Training happened on the job - nurses became surgical assistants, medical students performed procedures beyond their training.</p>
        <blockquote>"We learned to do things no textbook teaches. How to operate during an airstrike. How to triage when everyone is critical. How to keep going when colleagues are killed."</blockquote>
        <h2>The Human Cost</h2>
        <p>Of the doctors who served in besieged Aleppo, many were killed by airstrikes that seemed to target medical facilities deliberately. Those who survived carry trauma alongside their memories of service.</p>
        <p>Dr. Hamza now lives in Germany, where he is requalifying to practice medicine. He treats Syrian refugees who share his experiences. The nightmares continue, but so does his commitment to healing.</p>
        <h2>Legacy</h2>
        <p>The doctors of Aleppo demonstrated both the best of human nature and the worst of human conflict. Their courage saved thousands of lives against impossible odds. Their story should never be forgotten.</p>',
        'article_type' => 'Feature',
        'region' => 'Middle East',
        'tags' => ['Syria', 'Healthcare', 'War', 'Human Interest'],
    ],
    [
        'title' => 'Feature: Life in Kakuma - A City Built for Refugees',
        'excerpt' => 'Kenya\'s Kakuma refugee camp has existed for 30 years, evolving into something between camp and city.',
        'content' => '<p>Kakuma began in 1992 as an emergency response to Sudanese refugees. Thirty years later, it houses over 200,000 people from more than a dozen countries and has developed into something unprecedented - a permanent temporary city.</p>
        <h2>Neither Camp Nor City</h2>
        <p>Walking through Kakuma challenges preconceptions about refugee camps. Markets bustle with commerce. Schools educate thousands. Churches and mosques serve diverse congregations. A nascent tech sector has produced successful startups.</p>
        <p>Yet reminders of its nature are everywhere. Residents cannot leave without permission. Work opportunities are limited. Services depend on aid that fluctuates unpredictably. The camp remains administratively temporary even as it approaches its fourth decade.</p>
        <h2>The X-Rays</h2>
        <p>Kakuma hosts one of the few film studios in any refugee camp. A collective of young filmmakers creates content that has premiered at international festivals, telling stories rarely heard from refugee perspectives.</p>
        <p>"People see refugees as victims or threats," says one filmmaker. "We show we are creators, entrepreneurs, artists. We have dreams and talents."</p>
        <h2>Challenges Persist</h2>
        <p>Despite achievements, life in Kakuma remains difficult. Food rations have been cut repeatedly. Healthcare is basic. Temperatures regularly exceed 40 degrees Celsius. Violence, including sexual and gender-based violence, remains a serious concern.</p>
        <blockquote>"Kakuma is my home, but it is also my prison. I have lived here since I was a child. I don\'t know any other life."</blockquote>
        <h2>Uncertain Future</h2>
        <p>Kenya has periodically threatened to close Kakuma. Many residents have no country to return to safely. Resettlement opportunities are minimal. For most, the future is more of the same - indefinite limbo in a city that was never meant to last.</p>',
        'article_type' => 'Feature',
        'region' => 'Africa',
        'tags' => ['Kenya', 'Refugees', 'Kakuma', 'Human Interest'],
    ],
    [
        'title' => 'Feature: The Grandmother Network Saving Lives in Bangladesh',
        'excerpt' => 'A grassroots network of elderly women has become a lifeline for vulnerable communities in rural Bangladesh.',
        'content' => '<p>In the remote chars of northern Bangladesh - riverine islands that form and disappear with the seasons - a remarkable network of grandmothers has become the first line of defense against disasters and health emergencies.</p>
        <h2>Born from Necessity</h2>
        <p>The network began after a devastating flood in 2017, when elderly women found themselves caring for orphaned grandchildren and vulnerable neighbors while waiting for aid that came too slowly.</p>
        <p>"The young people had left for work in Dhaka," explains Rahima Begum, 67, one of the network\'s founders. "We grandmothers were left behind. We had to help each other."</p>
        <h2>How It Works</h2>
        <p>The network connects over 500 grandmothers across 35 chars. They share information via mobile phones, monitor vulnerable households, and coordinate community responses to floods, illness, and other emergencies.</p>
        <p>During flood season, they track river levels and organize evacuations. They identify children showing signs of malnutrition and connect families with services. They serve as birth attendants, mediators, and counselors.</p>
        <h2>Recognition and Growth</h2>
        <p>What began as informal mutual aid has gained recognition from NGOs and government agencies, who now provide training and supplies. The grandmothers have learned first aid, disaster preparedness, and basic health screening.</p>
        <blockquote>"People thought we were too old to matter. Now they call us when there is trouble. We have knowledge and time - we can help."</blockquote>
        <h2>Model for Others</h2>
        <p>The network has inspired similar initiatives in other parts of Bangladesh. Development organizations are studying it as a model for community-based disaster response that builds on existing social structures rather than importing external systems.</p>
        <p>For Rahima and her fellow grandmothers, the recognition matters less than the impact. "Every child we save, every family we help - that is our reward."</p>',
        'article_type' => 'Feature',
        'region' => 'Asia',
        'tags' => ['Bangladesh', 'Community', 'Disaster Response', 'Women'],
    ],

    // ==================== BREAKING (3+ articles) ====================
    [
        'title' => 'BREAKING: Major Earthquake Strikes Taiwan, Tsunami Warning Issued',
        'excerpt' => 'A 7.4 magnitude earthquake has struck off the coast of Taiwan, triggering tsunami warnings across the region.',
        'content' => '<p><strong>DEVELOPING STORY - Updates will follow</strong></p>
        <p>A powerful 7.4 magnitude earthquake struck off the eastern coast of Taiwan this morning, causing significant damage and triggering tsunami warnings for Japan, the Philippines, and other Pacific nations.</p>
        <h2>Initial Reports</h2>
        <p>The quake struck at 7:58 AM local time at a depth of 15 kilometers. Strong shaking was felt across the island, with the eastern city of Hualien reporting collapsed buildings and infrastructure damage.</p>
        <p>Emergency services are responding to multiple incidents. Early reports indicate casualties, but numbers remain unconfirmed as rescue operations continue.</p>
        <h2>Tsunami Warning</h2>
        <p>Tsunami warnings have been issued for coastal areas of Taiwan, Japan, and the Philippines. Residents in affected areas are urged to move to higher ground immediately.</p>
        <p>Japan\'s Meteorological Agency has warned of waves up to 3 meters possible in Okinawa and surrounding islands.</p>
        <h2>Response Underway</h2>
        <p>Taiwan\'s President has convened an emergency response team. Military units are being deployed to assist with search and rescue. International partners have offered assistance.</p>
        <p><em>This is a developing story. Refresh for updates.</em></p>',
        'article_type' => 'Breaking',
        'region' => 'Asia',
        'tags' => ['Taiwan', 'Earthquake', 'Tsunami', 'Breaking News'],
    ],
    [
        'title' => 'BREAKING: Ceasefire Announced in Gaza After Mediation Efforts',
        'excerpt' => 'Qatar announces breakthrough in ceasefire negotiations between Israel and Hamas.',
        'content' => '<p><strong>DEVELOPING STORY</strong></p>
        <p>Qatar\'s Foreign Ministry has announced that Israel and Hamas have agreed to a ceasefire, following intensive mediation efforts involving Egypt and the United States.</p>
        <h2>Terms of Agreement</h2>
        <p>Details of the agreement are still emerging. Initial reports suggest a phased approach including:</p>
        <ul>
        <li>Immediate cessation of hostilities</li>
        <li>Exchange of hostages and prisoners</li>
        <li>Increased humanitarian aid access</li>
        <li>Framework for further negotiations</li>
        </ul>
        <h2>Humanitarian Implications</h2>
        <p>If implemented, the ceasefire would allow desperately needed humanitarian aid to reach Gaza\'s population. UN agencies report catastrophic conditions, with widespread food insecurity and healthcare system collapse.</p>
        <blockquote>"This offers a glimmer of hope for millions of civilians who have endured unimaginable suffering." - UN Official</blockquote>
        <h2>Reaction</h2>
        <p>International reaction is cautiously optimistic. Previous ceasefires have collapsed, and significant obstacles remain to a lasting peace.</p>
        <p><em>Story developing. Updates to follow.</em></p>',
        'article_type' => 'Breaking',
        'region' => 'Middle East',
        'tags' => ['Gaza', 'Israel', 'Ceasefire', 'Breaking News'],
    ],
    [
        'title' => 'BREAKING: Cholera Outbreak Declared in Southern Africa',
        'excerpt' => 'WHO confirms cholera outbreak spreading across multiple countries in southern Africa.',
        'content' => '<p><strong>PUBLIC HEALTH EMERGENCY</strong></p>
        <p>The World Health Organization has confirmed a major cholera outbreak affecting multiple countries in southern Africa, with cases reported in Zimbabwe, Zambia, Malawi, and Mozambique.</p>
        <h2>Scale of Outbreak</h2>
        <p>Over 30,000 cases have been reported across the region in the past month, with nearly 1,000 deaths. Health officials warn that numbers are likely underreported due to limited surveillance capacity.</p>
        <p>Zimbabwe has been hardest hit, with the capital Harare experiencing significant transmission. Inadequate water and sanitation infrastructure is driving spread.</p>
        <h2>Response</h2>
        <p>Emergency response is scaling up:</p>
        <ul>
        <li>Oral cholera vaccine campaigns launching</li>
        <li>Cholera treatment centers established</li>
        <li>Water quality testing expanded</li>
        <li>Public awareness campaigns underway</li>
        </ul>
        <h2>Appeal for Support</h2>
        <p>WHO and partners have launched an emergency appeal for $50 million to fund response activities. Without rapid action, the outbreak could spread further across the region.</p>
        <blockquote>"Cholera is preventable and treatable. No one should die from this disease in 2024." - WHO Regional Director</blockquote>
        <p><em>Updates will follow as situation develops.</em></p>',
        'article_type' => 'Breaking',
        'region' => 'Africa',
        'tags' => ['Cholera', 'Health', 'Zimbabwe', 'Breaking News'],
    ],

    // ==================== ADDITIONAL ARTICLES FOR COVERAGE ====================
    // More Europe articles
    [
        'title' => 'Analysis: Ukraine\'s Humanitarian Corridors and the Challenge of Evacuation',
        'excerpt' => 'How civilian evacuations work - and fail - in active conflict zones.',
        'content' => '<p>Since Russia\'s full-scale invasion began, humanitarian corridors have been proposed as a mechanism to allow civilians to flee active combat zones. The reality has been far more complicated than the concept suggests.</p>
        <h2>The Theory</h2>
        <p>Humanitarian corridors are designated routes and time windows during which all parties agree to cease fire, allowing civilians to evacuate and aid to enter. They are established through negotiation, often with third-party mediators.</p>
        <h2>The Practice</h2>
        <p>In Ukraine, humanitarian corridors have repeatedly failed. Promised ceasefires have been violated. Routes have been mined. Evacuees have been fired upon. Some corridors have led only to Russian-controlled territory, raising concerns about forced deportation.</p>
        <p>For civilians in besieged cities like Mariupol, corridors offered false hope. Many who attempted evacuation were killed or turned back. Those who stayed faced bombardment, starvation, and disease.</p>
        <h2>Lessons Learned</h2>
        <p>The Ukraine experience highlights fundamental challenges:</p>
        <ul>
        <li>Corridors require genuine commitment from all parties</li>
        <li>Verification mechanisms are essential</li>
        <li>Civilians must have genuine choice of destination</li>
        <li>Aid must accompany evacuation, not replace it</li>
        </ul>
        <blockquote>"A humanitarian corridor that leads to detention is not humanitarian. Safe passage must mean safe passage everywhere."</blockquote>
        <p>As the war continues, the international community must develop more effective mechanisms to protect civilians in conflict.</p>',
        'article_type' => 'In-Depth Analysis',
        'region' => 'Europe',
        'tags' => ['Ukraine', 'Evacuation', 'Conflict', 'Protection'],
    ],
    [
        'title' => 'Feature: The Teachers of Zaporizhzhia\'s Underground Schools',
        'excerpt' => 'In bomb shelters beneath Ukrainian cities, teachers continue educating children despite the war.',
        'content' => '<p>Three floors below street level in Zaporizhzhia, a classroom of 15 children sits in a converted basement, learning mathematics while air raid sirens wail above. Their teacher, Olena, has taught for 30 years. She has never taught in conditions like these.</p>
        <h2>Education in a War Zone</h2>
        <p>When Russia\'s invasion began, Ukraine\'s education system faced an impossible challenge: how to continue teaching 5 million children in a country at war. The answer, for many, has been underground.</p>
        <p>Schools in frontline cities have moved to basements, bomb shelters, and metro stations. Teachers have adapted curricula to shorter class periods punctuated by alerts. Children have learned to distinguish between incoming and outgoing fire.</p>
        <h2>Olena\'s Classroom</h2>
        <p>"The children need normalcy," Olena explains. "When they focus on equations, they forget for a moment about the bombs. Education is resistance."</p>
        <p>Her classroom has no windows, limited ventilation, and improvised lighting. Desks are mismatched. But the walls are covered with children\'s drawings and achievement certificates.</p>
        <blockquote>"Some days we spend more time in the shelter than in class. But we keep going. We have to."</blockquote>
        <h2>Psychological Impact</h2>
        <p>Teachers have become de facto counselors, recognizing trauma symptoms and providing support beyond their training. Many have sought their own mental health support to cope with the stress of teaching in a war zone.</p>
        <p>For Ukraine\'s children, these teachers represent hope - proof that even in the darkest times, learning continues, futures are still being built.</p>',
        'article_type' => 'Feature',
        'region' => 'Europe',
        'tags' => ['Ukraine', 'Education', 'Children', 'Human Interest'],
    ],

    // More Americas articles
    [
        'title' => 'Investigation: The Disappeared of Mexico\'s Drug War',
        'excerpt' => 'Families search for answers as over 100,000 people remain missing in Mexico\'s ongoing violence.',
        'content' => '<p>Maria has been searching for her son for seven years. He left for work one morning in 2017 and never came home. He is one of over 100,000 people officially registered as disappeared in Mexico - the true number is likely far higher.</p>
        <h2>A National Crisis</h2>
        <p>Mexico\'s disappeared represent one of the world\'s largest ongoing enforced disappearance crises. Victims include those taken by criminal organizations, but also by security forces. Most cases are never solved.</p>
        <p>Our investigation analyzed thousands of cases, interviewed survivors and families, and examined government response. The findings reveal systematic failures at every level.</p>
        <h2>Impunity</h2>
        <p>Of the 100,000+ registered disappearances, fewer than 2% have resulted in convictions. Forensic capacity is overwhelmed - bodies are discovered faster than they can be identified. Mass graves containing hundreds of victims are found regularly.</p>
        <h2>The Searching Mothers</h2>
        <p>In the absence of effective investigation, families - predominantly mothers - have taken matters into their own hands. Armed with metal probes and determination, they search fields and hillsides for clandestine graves.</p>
        <blockquote>"The government should be doing this. But they won\'t, so we do. We will search until we find them or until we die searching."</blockquote>
        <h2>Systematic Failure</h2>
        <p>Despite laws and institutions created to address disappearances, implementation has been woefully inadequate. Resources are insufficient. Political will is lacking. The violence continues.</p>
        <p>Until Mexico treats disappearances as the human rights crisis they represent, families will continue searching - and waiting.</p>',
        'article_type' => 'Investigation',
        'region' => 'Americas',
        'tags' => ['Mexico', 'Disappearances', 'Human Rights', 'Investigation'],
    ],
    [
        'title' => 'News: Climate Migration Transforms Central American Communities',
        'excerpt' => 'Drought and extreme weather are driving new patterns of displacement in Guatemala and Honduras.',
        'content' => '<p>In Guatemala\'s Dry Corridor, where four years of drought have devastated crops, entire communities are on the move. Climate change is reshaping migration patterns across Central America, adding environmental displacement to the complex factors driving people north.</p>
        <h2>The Dry Corridor</h2>
        <p>The region stretching from southern Mexico to Panama has experienced increasingly severe droughts, interspersed with devastating floods. For subsistence farmers, the unpredictable weather has made traditional agriculture impossible.</p>
        <p>"My grandfather knew when to plant and when to harvest. Now the rains come at the wrong time or not at all. We cannot live from the land anymore," says Carlos, a farmer in Chiquimula who is preparing to migrate.</p>
        <h2>Multiple Drivers</h2>
        <p>Climate is rarely the sole driver of migration, but it increasingly interacts with poverty, violence, and lack of opportunity. Crop failure leads to debt, food insecurity, and desperation that makes dangerous migration seem like the only option.</p>
        <h2>Adaptation Limits</h2>
        <p>Some communities are attempting to adapt - switching crops, building water storage, diversifying livelihoods. But the scale of climate change is outpacing adaptation capacity, particularly for the poorest households.</p>
        <blockquote>"We are not choosing to leave. The climate is forcing us out. Our land can no longer sustain us."</blockquote>
        <p>The situation is likely to worsen. Climate projections suggest continued drying and increased extreme weather events across the region, potentially displacing millions more in coming decades.</p>',
        'article_type' => 'News',
        'region' => 'Americas',
        'tags' => ['Climate', 'Migration', 'Guatemala', 'Honduras'],
    ],

    // More Asia articles
    [
        'title' => 'Opinion: Myanmar\'s Crisis Requires Stronger International Action',
        'excerpt' => 'Three years after the coup, the international community\'s response remains woefully inadequate.',
        'content' => '<p><em>By Dr. Khin Maung, Former UN Adviser</em></p>
        <p>Three years since Myanmar\'s military seized power, the country has descended into civil war. Nearly 3 million people are displaced. The economy has collapsed. Human rights violations occur daily. Yet the international community\'s response remains fragmented and ineffective.</p>
        <h2>ASEAN\'s Failure</h2>
        <p>The Association of Southeast Asian Nations adopted a Five-Point Consensus in 2021, calling for dialogue and humanitarian access. Implementation has been virtually nonexistent. The military has shown no interest in dialogue while it pursues military victory.</p>
        <p>ASEAN\'s commitment to non-interference has become an excuse for inaction while Myanmar burns. Member states continue business relationships with the junta, providing economic lifelines that sustain the regime.</p>
        <h2>Beyond Statements</h2>
        <p>Western nations have imposed targeted sanctions, but gaps remain. Arms continue to flow from Russia and China. Aviation fuel powers the helicopters that bomb villages. Financial channels remain open.</p>
        <blockquote>"The people of Myanmar are fighting for democracy with their lives. The least the international community can do is stop funding their oppressors."</blockquote>
        <h2>What Is Needed</h2>
        <p>Effective action requires coordinated pressure: comprehensive arms embargoes, targeted financial sanctions, support for resistance governance structures, and cross-border humanitarian access that does not depend on military approval.</p>
        <p>The people of Myanmar are not asking for military intervention. They are asking for the tools to resist tyranny and the space to build their own democratic future. The world must do more to help them.</p>',
        'article_type' => 'Opinion',
        'region' => 'Asia',
        'tags' => ['Myanmar', 'Coup', 'ASEAN', 'Human Rights'],
    ],
    [
        'title' => 'News: Pakistan Hosts Largest Afghan Refugee Population as Deportations Loom',
        'excerpt' => 'Pakistan orders 1.7 million undocumented Afghans to leave, raising humanitarian concerns.',
        'content' => '<p>Pakistan has ordered 1.7 million undocumented Afghan refugees to leave the country by November 1st, threatening one of the largest mass deportations in recent history. Humanitarian organizations are raising alarms about the potential consequences.</p>
        <h2>Decades of Hosting</h2>
        <p>Pakistan has hosted Afghan refugees for over 40 years, since the Soviet invasion of Afghanistan. At its peak, the country sheltered over 4 million Afghans. Currently, an estimated 4 million Afghans remain in Pakistan, including 1.7 million without documentation.</p>
        <h2>Return to What?</h2>
        <p>Afghanistan under Taliban rule faces economic collapse, restrictions on women and girls, and drought. Many of the Afghans facing deportation have never lived in Afghanistan - they were born in Pakistan and have no homes to return to.</p>
        <p>"They call it repatriation, but for many of us, Afghanistan is a foreign country," says Ahmad, who has lived in Karachi for 30 years. "We will have nothing there."</p>
        <h2>Humanitarian Concerns</h2>
        <p>UN agencies and NGOs have called for Pakistan to reconsider, warning of humanitarian catastrophe if deportations proceed at scale. Afghanistan lacks capacity to receive large numbers of returnees, who would add to the 6 million already displaced internally.</p>
        <blockquote>"Forced return to Afghanistan in current conditions could constitute refoulement - returning people to face persecution and serious harm."</blockquote>
        <p>Pakistan cites security concerns and economic pressures. The situation remains unresolved as the deadline approaches.</p>',
        'article_type' => 'News',
        'region' => 'Asia',
        'tags' => ['Afghanistan', 'Pakistan', 'Refugees', 'Deportation'],
    ],

    // More Global/Middle East articles
    [
        'title' => 'Analysis: Water Scarcity and Conflict in the Middle East',
        'excerpt' => 'Competition over water resources increasingly shapes regional tensions and humanitarian crises.',
        'content' => '<p>As climate change intensifies and populations grow, water scarcity is emerging as a critical driver of tension and humanitarian crisis across the Middle East. From the Tigris-Euphrates basin to the Jordan River, competition over water shapes regional politics and threatens vulnerable communities.</p>
        <h2>Shared Rivers, Divided Interests</h2>
        <p>The Tigris and Euphrates rivers flow through Turkey, Syria, and Iraq. Turkish dams have significantly reduced downstream flows, contributing to agricultural collapse in Syria and Iraq. During drought years, the rivers have dropped to historic lows.</p>
        <p>The Jordan River, shared by Israel, Jordan, Palestine, and Syria, is similarly contested. Israel controls the majority of water resources in the West Bank, leaving Palestinians with severe shortages.</p>
        <h2>Climate Acceleration</h2>
        <p>The region is warming faster than the global average. Precipitation is declining while demand rises. Groundwater - the reserve source when surface water fails - is being depleted far faster than it can be recharged.</p>
        <blockquote>"We are drawing down water resources that took millennia to accumulate. When they are gone, they are gone."</blockquote>
        <h2>Conflict Connections</h2>
        <p>Water scarcity has contributed to instability. Syria\'s 2006-2010 drought displaced 1.5 million people and has been linked to social tensions preceding the uprising. Yemen\'s water table is collapsing even as war destroys infrastructure.</p>
        <h2>Paths Forward</h2>
        <p>Solutions exist - desalination, efficiency improvements, wastewater recycling, transboundary cooperation - but require investment and political will often lacking. Without action, water will increasingly drive conflict and displacement across the region.</p>',
        'article_type' => 'In-Depth Analysis',
        'region' => 'Middle East',
        'tags' => ['Water', 'Climate', 'Conflict', 'Resources'],
    ],
    [
        'title' => 'Feature: The Last Hospital in Eastern Ghouta',
        'excerpt' => 'How one medical facility survived siege and bombardment to serve a trapped population.',
        'content' => '<p>The basement shakes as another bomb lands somewhere above. Dr. Amani continues suturing a wound, her hands steady from years of practice under fire. This is the last functioning hospital in what remains of rebel-held eastern Ghouta.</p>
        <h2>Under Siege</h2>
        <p>For five years, eastern Ghouta was besieged by Syrian government forces. 400,000 people were trapped in an ever-shrinking enclave, subject to bombardment, starvation, and chemical weapons attacks. Medical infrastructure was systematically targeted.</p>
        <p>Of 14 hospitals that once served the area, only one remains. It operates three floors underground, in a former warehouse reinforced with sandbags and concrete. Even here, direct hits have damaged sections repeatedly.</p>
        <h2>Impossible Choices</h2>
        <p>With limited supplies and overwhelming need, every day brings impossible triage decisions. Patients who might survive with proper care die because resources must go to those with better chances. Chronic conditions go untreated as acute emergencies consume all capacity.</p>
        <p>"We practice a kind of medicine that no textbook describes," says Dr. Amani. "We do what we can with what we have. Often it is not enough."</p>
        <blockquote>"I became a doctor to save lives. Here, I also learn how to lose them with dignity when saving is impossible."</blockquote>
        <h2>The Staff</h2>
        <p>The hospital runs on a skeleton staff of 20, including just three doctors. They work continuous shifts, sleeping in the facility because their homes have been destroyed. Several have been killed in attacks; others have been injured multiple times.</p>
        <p>They stay because the alternative - abandoning patients - is unthinkable. "Someone must be here," says Dr. Amani. "If not us, who?"</p>
        <h2>A Fragile Lifeline</h2>
        <p>The hospital represents hope for a community facing elimination. Every surgery performed, every child delivered, every life saved is an act of resistance against those who would destroy everything.</p>',
        'article_type' => 'Feature',
        'region' => 'Middle East',
        'tags' => ['Syria', 'Healthcare', 'Siege', 'Human Interest'],
    ],
];

// Get admin user
$admin_user = get_users(['role' => 'administrator', 'number' => 1]);
$author_id = $admin_user[0]->ID ?? 1;

echo '<div class="card info"><strong>Starting content creation...</strong><br>Creating ' . count($articles) . ' articles</div>';

$created = 0;
$article_types_count = [];
$regions_count = [];

foreach ($articles as $index => $article) {
    // Get or create article type
    $type_term = get_term_by('slug', sanitize_title($article['article_type']), 'article_type');
    if (!$type_term) {
        $result = wp_insert_term($article['article_type'], 'article_type');
        $type_id = is_array($result) ? $result['term_id'] : 0;
    } else {
        $type_id = $type_term->term_id;
    }

    // Get or create region
    $region_term = get_term_by('slug', sanitize_title($article['region']), 'region');
    if (!$region_term) {
        $result = wp_insert_term($article['region'], 'region');
        $region_id = is_array($result) ? $result['term_id'] : 0;
    } else {
        $region_id = $region_term->term_id;
    }

    // Create post
    $post_data = [
        'post_title'   => $article['title'],
        'post_content' => $article['content'],
        'post_excerpt' => $article['excerpt'],
        'post_status'  => 'publish',
        'post_author'  => $author_id,
        'post_date'    => date('Y-m-d H:i:s', strtotime('-' . ($index * 8) . ' hours')),
        'tags_input'   => $article['tags'],
    ];

    $post_id = wp_insert_post($post_data);

    if ($post_id && !is_wp_error($post_id)) {
        // Set taxonomies
        if ($type_id) wp_set_object_terms($post_id, $type_id, 'article_type');
        if ($region_id) wp_set_object_terms($post_id, $region_id, 'region');

        // Track counts
        $article_types_count[$article['article_type']] = ($article_types_count[$article['article_type']] ?? 0) + 1;
        $regions_count[$article['region']] = ($regions_count[$article['region']] ?? 0) + 1;

        // Reading time
        $reading_time = ceil(str_word_count(strip_tags($article['content'])) / 200);
        update_post_meta($post_id, 'reading_time', $reading_time);

        // Make first 3 posts sticky for hero
        if ($index < 3) {
            stick_post($post_id);
        }

        $created++;
    }
}

update_option('demo_content_v2_created', true);

echo '<div class="card success"><h2>✅ Created ' . $created . ' articles!</h2></div>';

echo '<div class="card"><h3>Articles by Type:</h3><div class="grid">';
foreach ($article_types_count as $type => $count) {
    echo '<div class="tag">' . esc_html($type) . ': <span class="count">' . $count . '</span></div>';
}
echo '</div></div>';

echo '<div class="card"><h3>Articles by Region:</h3><div class="grid">';
foreach ($regions_count as $region => $count) {
    echo '<div class="tag">' . esc_html($region) . ': <span class="count">' . $count . '</span></div>';
}
echo '</div></div>';

echo '<div class="card info">
<h3>Next Steps:</h3>
<ol>
<li><a href="' . home_url() . '" target="_blank">View Homepage</a></li>
<li><a href="' . admin_url('edit.php') . '" target="_blank">View All Posts</a></li>
<li>Delete this file after use!</li>
</ol>
</div>';

?>
</body>
</html>
