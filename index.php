<?php
declare(strict_types=1);
require __DIR__ . '/config.php';

$stmt = $pdo->query('SELECT id, title, description, tags, link, img1, img2, img3 FROM projects ORDER BY created_at DESC');
$projects = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Developer Portfolio – Jack Bell</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="style.css"/>
  <script defer src="main.js"></script>
</head>
<body class="dark">
<header class="site-header">
  <div class="container nav">
    <a class="logo" href="/index.php"><span>&lt;c/&gt;</span> Jack Bell</a>

    <nav id="main-nav" aria-label="Main navigation">
      <a href="#skills">Skills</a>
      <a href="#contact">Contact</a>
      <a href="https://github.com/Marss616">GitHub</a>
      <a href="https://www.linkedin.com/in/jack-bell-90351a399">LinkedIn</a>
      <a href="Jack Bell Resume 2025 2.pdf">Download CV</a>
    </nav>
  </div>
</header>

<main>
  <!-- HERO -->
  <section class="hero">
    <div class="container hero-grid">
      <aside class="profile-card">
        <img src="OwfB.gif" alt="Portrait of Jack Bell"/>
        <div class="profile-meta">
          <h3>Jack</h3>
          <p>Full-Stack Developer</p>
          <div class="profile-links">
            <a href="https://github.com/Marss616" class="pill" target="_blank" rel="noopener">GitHub</a>
            <a href="https://www.linkedin.com/in/jack-bell-90351a399" class="pill" target="_blank" rel="noopener">LinkedIn</a>
            <a href="Jack Bell Resume 2025 2.pdf" download class="pill">Download CV</a>
          </div>
        </div>
      </aside>

      <div class="hero-copy">
        <p class="eyebrow">Developer</p>
        <h1>Hey, I’m <span class="accent">Jack</span>,<br/>Full-Stack Developer & Cyber Security Specialist</h1>
        My work spans <strong>full-stack development</strong>, <strong>cloud engineering</strong>, 
    <strong>digital forensics</strong>, and <strong>Cyber Security</strong>.
        <div class="cta-row">
          <a class="btn" href="#contact">Let’s Talk</a>
          <a class="btn ghost" href="/blogs.html">Read about my work experience</a>
        </div>
      </div>

      <div class="stats-bubble" aria-label="Experience stats">
        <div><span>4</span><small>Years</small></div>
        <div><span>20</span><small>Projects</small></div>
        <div><span>6</span><small>Certs</small></div>
        <div><span>16</span><small>Stack</small></div>
        <div><span>4</span><small>Years Dev</small></div>
        <div><span>3</span><small>Years Cyber</small></div>
      </div>
    </div>

    <div class="topo" aria-hidden="true"></div>
  </section>

  <!-- ABOUT -->
  <section id="about" class="about">
    <div class="container about-grid">
      <h2 class="section-title">
  <img src="drawing.svg" alt="Animated graphic" class="title-gif" width="100%" height="100%"/>
</h2>
<div class="about-card">
  <p>
    Open to <strong>full-time roles</strong>, <strong>contract work</strong>, and 
    <strong>freelance development or security projects</strong>. I work across both 
    software engineering and cyber operations, building practical tools and solving 
    real security problems.
  </p>

  <p>
    I build secure web apps, automate workflows, analyse logs, investigate incidents, 
    and design small-scale systems that blend development with defensive capability. 
    I regularly work with SIEMs, packet captures, threat detections.
  </p>

  <p>
    Whether it's building a tool, analysing an environment, or securing a system, 
    my focus is always on <strong>clarity, reliability, and real-world impact</strong>.  
    Explore my skills and projects below, and feel free to reach out any time through 
    the contact form.
  </p>
</div>

</section>

  <!-- SKILLS -->
<section id="skills" class="skills">
  <div class="container">
    <h2 class="section-title">Skills</h2>

    <div class="skill-cards">

      <!-- Full Stack -->
      <article class="skill-card">
        <h3>Full-Stack Web Development</h3>
        <p>Designing and building responsive, accessible, and secure applications.</p>
        <ul class="tags">
          <li>HTML</li><li>CSS</li><li>JavaScript</li><li>TypeScript</li>
          <li>React</li><li>Django</li>
        </ul>
      </article>

      <!-- App Development -->
      <article class="skill-card">
        <h3>Application Development</h3>
        <p>Building reliable tools, backend services, automation scripts, and secure system utilities.</p>
        <ul class="tags">
          <li>Python</li>
          <li>Django</li>
          <li>Bash</li>
          <li>PowerShell</li>
          <li>APIs</li>
          <li>Automation</li>
        </ul>
      </article>

      <!-- Digital Forensics / Blue Team -->
      <article class="skill-card">
        <h3>Digital Forensics & Blue Teaming</h3>
        <p>Detection engineering, incident response, forensic analysis, and SOC tooling.</p>
        <ul class="tags">
          <li>SIEM</li><li>ELK</li><li>Splunk</li>
          <li>Velociraptor</li><li>KAPE</li><li>Wireshark</li>
        </ul>
      </article>

      <!-- Cloud + DevOps -->
      <article class="skill-card">
        <h3>Cloud & DevOps</h3>
        <p>Deploying secure, scalable infrastructure with automation and monitoring.</p>
        <ul class="tags">
          <li>AWS</li><li>Azure</li><li>Linux</li>
          <li>Nginx</li><li>Docker</li><li>Terraform</li>
        </ul>
      </article>

      <!-- Pentesting -->
      <article class="skill-card">
        <h3>Pentesting & Offensive Security</h3>
        <p>Practical exploitation, enumeration, privilege escalation, and OSCP-style labs.</p>
        <ul class="tags">
          <li>Nmap</li><li>Burp Suite</li><li>Hydra</li>
          <li>LLMNR</li><li>SMB</li><li>Payload Crafting</li>
        </ul>
      </article>

      <!-- System Admin -->
      <article class="skill-card">
        <h3>Systems Administration</h3>
        <p>Managing and hardening servers, networking, and enterprise environments.</p>
        <ul class="tags">
          <li>Windows Server</li><li>Active Directory</li>
          <li>Linux Admin</li><li>Bash</li><li>Powershell</li>
        </ul>
      </article>

    </div>
  </div>
</section>


    <!-- projects with cms -->
  <section id="projects" class="projects">
    <div class="container">
      <h2 class="section-title">Projects</h2>

      <?php if (!$projects): ?>
        <p>No projects yet – log in to <code>admin.php</code> to add some.</p>
      <?php else: ?>
        <?php foreach ($projects as $project): ?>
          <div class="project-row">

            <div class="project-images">
              <?php if (!empty($project['img1'])): ?>
                <img
                  src="<?= htmlspecialchars($project['img1'], ENT_QUOTES, 'UTF-8') ?>"
                  alt="<?= htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8') ?> screenshot 1">
              <?php endif; ?>

              <?php if (!empty($project['img2'])): ?>
                <img
                  src="<?= htmlspecialchars($project['img2'], ENT_QUOTES, 'UTF-8') ?>"
                  alt="<?= htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8') ?> screenshot 2">
              <?php endif; ?>

              <?php if (!empty($project['img3'])): ?>
                <img
                  src="<?= htmlspecialchars($project['img3'], ENT_QUOTES, 'UTF-8') ?>"
                  alt="<?= htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8') ?> screenshot 3">
              <?php endif; ?>
            </div>

            <div class="project-info">
              <h3><?= htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8') ?></h3>
              <p><?= htmlspecialchars($project['description'], ENT_QUOTES, 'UTF-8') ?></p>

              <?php
                $tagsRaw = $project['tags'] ?? '';
                $tagList = array_filter(array_map('trim', explode(',', $tagsRaw)));
              ?>
              <?php if (!empty($tagList)): ?>
                <ul class="tags">
                  <?php foreach ($tagList as $tag): ?>
                    <li><?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') ?></li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>

              <?php if (!empty($project['link'])): ?>
                <a class="project-link"
                   href="<?= htmlspecialchars($project['link'], ENT_QUOTES, 'UTF-8') ?>"
                   target="_blank" rel="noopener">
                  View project →
                </a>
              <?php endif; ?>
            </div>

          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <!-- CONTACT -->
  <section id="contact" class="contact">
    <div class="container">
      <h2 class="section-title">Let’s Work Together</h2>
      <form class="contact-form" method="POST" action="/server/contact.php" novalidate>
        <label class="sr-only" for="contact-name">Your name</label>
        <input id="contact-name" type="text" name="name" placeholder="Your name" required>

        <label class="sr-only" for="contact-email">Email address</label>
        <input id="contact-email" type="email" name="email" placeholder="Email address" required>

        <label class="sr-only" for="contact-message">Message</label>
        <textarea id="contact-message" name="message" rows="4" placeholder="Message" required></textarea>

        <!-- security -->
        <input type="hidden" name="csrf" id="csrf">
        <input
          type="text"
          name="website"
          tabindex="-1"
          autocomplete="off"
          style="position:absolute;left:-9999px"
          aria-hidden="true"
        >

        <button class="btn" type="submit">Send Message</button>
      </form>
    </div>
  </section>
</main>

<footer class="site-footer">
  <div class="container">
    <p><span id="year"></span>Jack Bell 2025</p>
  </div>
</footer>
</body>
</html>
