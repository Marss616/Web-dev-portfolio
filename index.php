<?php
declare(strict_types=1);
require __DIR__ . '/config.php';

$stmt = $pdo->query('SELECT id, title, description, tags, link, img1, img2, img3 FROM projects ORDER BY created_at DESC');
$projects = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];

$experience = [
    [
        'title' => 'Security Engineer / Level 2 SOC',
        'org' => 'ADF',
        'period' => '2025 – Current',
        'summary' => [
            'Develop detection rules and automated response playbooks.',
            'Conduct digital forensics and incident response on true positives.',
            'Implement security controls aligned to ISM expectations.',
            'Deliver incident reports and stakeholder briefings.',
            'Provide recommendations aligned with PSPF, ISM, and DSM.'
        ]
    ],
    [
        'title' => 'Junior Security Analyst / Level 1 SOC',
        'org' => 'ADF',
        'period' => '2023 – 2024',
        'summary' => [
            'Triaged security events in SOC workflows.',
            'Filtered false positives from confirmed events.',
            'Supported analyst uplift and internal training.'
        ]
    ],
    [
        'title' => 'ICT Field Technician / Network Administrator',
        'org' => 'ADF',
        'period' => '2020 – 2023',
        'summary' => [
            'Configured Windows Server environments and core services including Active Directory, Exchange, DNS, DHCP, Group Policy, and file sharing.',
            'Configured and troubleshot network infrastructure including VLANs, IP addressing, subnetting, switching, routing, and network diagnostics.'
        ]
    ],
    [
        'title' => 'IT Support Technician',
        'org' => 'NPE',
        'period' => '2018 – 2020',
        'summary' => [
            'Provided end-user support and practical troubleshooting across hardware, software, and desktop environments.'
        ]
    ],
    [
        'title' => 'Junior Security Engineer',
        'org' => 'Baidam',
        'period' => '2017 – 2018',
        'summary' => [
            'Supported security engineering activities and foundational monitoring workflows.'
        ]
    ],
];

$education = [
    ['name' => 'Bachelor of Cyber Security', 'org' => 'La Trobe University', 'period' => '2023 – 2026'],
    ['name' => 'Diploma of Blockchain Technology', 'org' => 'TAFE QLD', 'period' => '2025 – 2026'],
    ['name' => 'Cert IV Training and Assessment', 'org' => 'Federation University', 'period' => '2026 – 2027'],
    ['name' => 'Cert IV Cyber Security', 'org' => 'TAFE QLD', 'period' => '2022 – 2023'],
    ['name' => 'Cert III ICT', 'org' => 'TAFE QLD', 'period' => '2021 – 2022'],
];

$certs = [
    ['name' => 'OffSec Certified Professional (OSCP)', 'org' => 'OffSec', 'period' => '2025'],
    ['name' => 'CompTIA Security+ and A+', 'org' => 'BHI', 'period' => '2024'],
    ['name' => 'Cisco Certified Network Associate (CCNA)', 'org' => 'Cisco', 'period' => '2024'],
    ['name' => 'Joint Cyber Incident Analyst (JCE - JIA)', 'org' => 'DFSS / ADF', 'period' => '2023'],
    ['name' => 'CWSP', 'org' => 'Cisco', 'period' => '2025'],
];

$skillGroups = [
    'Security Operations' => ['SIEM – Splunk, ELK', 'IOC detection and response', 'Incident reporting', 'Event triage', 'Event automation'],
    'Forensics & Investigation' => ['The Sleuth Kit', 'Velociraptor', 'Network forensics', 'Windows / Linux forensics', 'Mobile phone forensics'],
    'Systems & Infrastructure' => ['Windows Server 2019', 'Active Directory', 'VMware ESXi', 'Remote desktop support', 'Network setup and configuration', 'IDS / IPS'],
    'Governance & Frameworks' => ['PSPF', 'ISM', 'DSM', 'ISO 27001', 'Essential Eight'],
    'Development & Scripting' => ['Python', 'React', 'SQL', 'PowerShell', 'SPL'],
];

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Jack Bell | Cyber Security Portfolio</title>
  <meta name="description" content="Portfolio website for Jack Anthony Bell, Security Engineer, SOC analyst, systems specialist, and developer.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <script defer src="main.js"></script>
</head>
<body>
  <div class="page-shell">
    <header class="site-header">
      <div class="container nav-wrap">
        <a class="brand" href="#top">Jack Bell</a>
        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-nav">Menu</button>
        <nav id="site-nav" class="site-nav" aria-label="Main navigation">
          <a href="#about">About</a>
          <a href="#experience">Experience</a>
          <a href="#skills">Skills</a>
          <a href="#projects">Projects</a>
          <a href="#contact">Contact</a>
        </nav>
      </div>
    </header>

    <main id="top">
      <section class="hero-section">
        <div class="container hero-grid">
          <div class="hero-copy reveal">
            <p class="eyebrow">Security Engineer · SOC Analyst · Systems Specialist</p>
            <h1>Jack Anthony Bell</h1>
            <p class="hero-text">
              Cyber security professional with hands-on experience across SOC operations, incident response,
              Windows infrastructure, network administration, and practical engineering work.
            </p>

            <div class="hero-actions">
              <a class="button" href="#contact">Contact Me</a>
              <a class="button button-secondary" href="#projects">View Projects</a>
            </div>

            <dl class="quick-facts">
              <div><dt>Location</dt><dd>Melbourne</dd></div>
              <div><dt>Clearance</dt><dd>Negative Vetting Level 2</dd></div>
              <div><dt>Website</dt><dd><a href="https://jackbellportfolio.com" target="_blank" rel="noopener">jackbellportfolio.com</a></dd></div>
              <div><dt>GitHub</dt><dd><a href="https://github.com/marss616" target="_blank" rel="noopener">github.com/marss616</a></dd></div>
              <div><dt>LinkedIn</dt><dd><a href="https://linkedin.com/in/jack-bell-90351a399" target="_blank" rel="noopener">linkedin.com/in/jack-bell-90351a399</a></dd></div>
              <div><dt>Email</dt><dd><a href="mailto:jack.bell.work@outlook.com">jack.bell.work@outlook.com</a></dd></div>
            </dl>
          </div>

          <div class="hero-visual reveal">
            <div class="visual-card">
              <img src="gif.gif" alt="Abstract cyber style geometric graphic">
            </div>
          </div>
        </div>
      </section>

      <section id="about" class="section">
        <div class="container about-grid">
          <div class="section-heading reveal">
            <p class="kicker">Profile</p>
            <h2>About</h2>
          </div>
          <div class="card reveal">
            <p>
              I work across security operations, detection engineering, incident response, systems administration,
              and practical infrastructure support. My background combines cyber security work inside structured
              environments with hands-on delivery in networks, servers, and end-user technology.
            </p>
            <p>
              This redesign keeps your PHP project system in place, but gives the site a more polished layout,
              stronger information hierarchy, and a more professional cyber-focused visual style.
            </p>
          </div>
        </div>
      </section>

      <section id="experience" class="section section-alt">
        <div class="container">
          <div class="section-heading reveal">
            <p class="kicker">Career</p>
            <h2>Experience</h2>
          </div>

          <div class="timeline">
            <?php foreach ($experience as $item): ?>
              <article class="timeline-item reveal">
                <div class="timeline-meta">
                  <span class="timeline-period"><?= e($item['period']) ?></span>
                  <span class="timeline-org"><?= e($item['org']) ?></span>
                </div>
                <div class="timeline-content card">
                  <h3><?= e($item['title']) ?></h3>
                  <ul class="bullet-list">
                    <?php foreach ($item['summary'] as $point): ?>
                      <li><?= e($point) ?></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <section id="skills" class="section">
        <div class="container">
          <div class="section-heading reveal">
            <p class="kicker">Capability</p>
            <h2>Skills</h2>
          </div>

          <div class="skill-grid">
            <?php foreach ($skillGroups as $group => $items): ?>
              <article class="card reveal">
                <h3><?= e($group) ?></h3>
                <ul class="tag-list">
                  <?php foreach ($items as $skill): ?>
                    <li><?= e($skill) ?></li>
                  <?php endforeach; ?>
                </ul>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <section class="section section-alt">
        <div class="container two-col">
          <div>
            <div class="section-heading reveal">
              <p class="kicker">Study</p>
              <h2>Education</h2>
            </div>
            <div class="stack-list">
              <?php foreach ($education as $item): ?>
                <article class="card reveal mini-card">
                  <h3><?= e($item['name']) ?></h3>
                  <p><?= e($item['org']) ?></p>
                  <span><?= e($item['period']) ?></span>
                </article>
              <?php endforeach; ?>
            </div>
          </div>

          <div>
            <div class="section-heading reveal">
              <p class="kicker">Credentials</p>
              <h2>Certificates</h2>
            </div>
            <div class="stack-list">
              <?php foreach ($certs as $item): ?>
                <article class="card reveal mini-card">
                  <h3><?= e($item['name']) ?></h3>
                  <p><?= e($item['org']) ?></p>
                  <span><?= e($item['period']) ?></span>
                </article>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </section>

      <section id="projects" class="section">
        <div class="container">
          <div class="section-heading reveal">
            <p class="kicker">Work</p>
            <h2>Projects</h2>
          </div>

          <?php if (!$projects): ?>
            <div class="card reveal empty-state">
              <p>No projects have been added yet. Use <code>admin.php</code> to add your project content.</p>
            </div>
          <?php else: ?>
            <div class="projects-grid">
              <?php foreach ($projects as $project): ?>
                <?php
                  $tagsRaw = $project['tags'] ?? '';
                  $tagList = array_filter(array_map('trim', explode(',', $tagsRaw)));
                ?>
                <article class="project-card card reveal">
                  <div class="project-media">
                    <?php if (!empty($project['img1'])): ?>
                      <img src="<?= e((string)$project['img1']) ?>" alt="<?= e((string)$project['title']) ?> image 1">
                    <?php endif; ?>
                    <?php if (!empty($project['img2'])): ?>
                      <img src="<?= e((string)$project['img2']) ?>" alt="<?= e((string)$project['title']) ?> image 2">
                    <?php endif; ?>
                    <?php if (!empty($project['img3'])): ?>
                      <img src="<?= e((string)$project['img3']) ?>" alt="<?= e((string)$project['title']) ?> image 3">
                    <?php endif; ?>
                  </div>
                  <div class="project-body">
                    <h3><?= e((string)$project['title']) ?></h3>
                    <p><?= e((string)$project['description']) ?></p>

                    <?php if ($tagList): ?>
                      <ul class="tag-list">
                        <?php foreach ($tagList as $tag): ?>
                          <li><?= e((string)$tag) ?></li>
                        <?php endforeach; ?>
                      </ul>
                    <?php endif; ?>

                    <?php if (!empty($project['link'])): ?>
                      <a class="text-link" href="<?= e((string)$project['link']) ?>" target="_blank" rel="noopener">View project</a>
                    <?php endif; ?>
                  </div>
                </article>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </section>

      <section id="contact" class="section section-alt">
        <div class="container contact-grid">
          <div class="section-heading reveal">
            <p class="kicker">Connect</p>
            <h2>Contact</h2>
            <p class="contact-copy">
              Open to cyber security, SOC, systems, and technical support opportunities.
            </p>
          </div>

          <div class="card reveal">
            <form class="contact-form" method="POST" action="contact.php" novalidate>
              <label for="name">Name</label>
              <input id="name" type="text" name="name" required>

              <label for="email">Email</label>
              <input id="email" type="email" name="email" required>

              <label for="message">Message</label>
              <textarea id="message" name="message" rows="6" required></textarea>

              <input type="hidden" name="csrf" id="csrf">
              <input type="text" name="website" class="honeypot" tabindex="-1" autocomplete="off" aria-hidden="true">

              <button class="button" type="submit">Send Message</button>
              <p id="form-status" class="form-status" aria-live="polite"></p>
            </form>
          </div>
        </div>
      </section>
    </main>

    <footer class="site-footer">
      <div class="container footer-wrap">
        <p>© <span id="year"></span> Jack Anthony Bell</p>
        <a href="mailto:jack.bell.work@outlook.com">jack.bell.work@outlook.com</a>
      </div>
    </footer>
  </div>
</body>
</html>
