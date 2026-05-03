document.addEventListener('DOMContentLoaded', async () => {
  const year = document.getElementById('year');
  if (year) year.textContent = new Date().getFullYear();

  const menuButton = document.querySelector('.menu-toggle');
  const nav = document.getElementById('site-nav');

  if (menuButton && nav) {
    menuButton.addEventListener('click', () => {
      const isOpen = nav.classList.toggle('is-open');
      menuButton.setAttribute('aria-expanded', String(isOpen));
    });
  }

  const sections = [...document.querySelectorAll('main section[id]')];
  const navLinks = [...document.querySelectorAll('.site-nav a[href^="#"]')];

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const id = entry.target.getAttribute('id');
        navLinks.forEach((link) => {
          link.classList.toggle('active', link.getAttribute('href') === `#${id}`);
        });
      }
    });
  }, { rootMargin: '-40% 0px -45% 0px', threshold: 0 });

  sections.forEach((section) => observer.observe(section));

  const revealObserver = new IntersectionObserver((entries, obs) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        obs.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15 });

  document.querySelectorAll('.reveal').forEach((el) => revealObserver.observe(el));

  try {
    const response = await fetch('csrf.php', { headers: { 'Accept': 'application/json' } });
    const data = await response.json();
    const csrfInput = document.getElementById('csrf');
    if (csrfInput && data.csrf) csrfInput.value = data.csrf;
  } catch (error) {
    console.error('Failed to fetch CSRF token', error);
  }

  const form = document.querySelector('.contact-form');
  const status = document.getElementById('form-status');

  if (form && status) {
    form.addEventListener('submit', async (event) => {
      event.preventDefault();
      status.textContent = 'Sending...';

      try {
        const response = await fetch(form.action, {
          method: 'POST',
          body: new FormData(form),
        });

        const data = await response.json();

        if (response.ok && data.ok) {
          form.reset();
          status.textContent = 'Message sent successfully.';
          const csrfResponse = await fetch('csrf.php');
          const csrfData = await csrfResponse.json();
          const csrfInput = document.getElementById('csrf');
          if (csrfInput && csrfData.csrf) csrfInput.value = csrfData.csrf;
        } else {
          status.textContent = data.error || 'Something went wrong.';
        }
      } catch (error) {
        status.textContent = 'Unable to send message right now.';
      }
    });
  }
});
