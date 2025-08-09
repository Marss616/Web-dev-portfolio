// get elements
const formEl = document.querySelector('.contact-form');
const csrfEl = document.querySelector('#csrf');
const submitBtn = formEl?.querySelector('button[type="submit"]');

// disable submit until token ready
if (submitBtn) {
  submitBtn.disabled = true;
  submitBtn.textContent = 'Loading…';
}

// fetch CSRF token from same origin
(async () => {
  try {
    const r = await fetch('/server/csrf.php', { credentials: 'same-origin' });
    const j = await r.json();
    if (csrfEl && j?.csrf) {
      csrfEl.value = j.csrf;
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Send Message';
      }
    } else {
      console.error('No CSRF token returned');
    }
  } catch (e) {
    console.error('CSRF fetch failed', e);
  }
})();

// submit handler
formEl?.addEventListener('submit', async (e) => {
  e.preventDefault();

  // ensure token exists (fallback grab)
  if (!csrfEl?.value) {
    try {
      const r = await fetch('/server/csrf.php', { credentials: 'same-origin' });
      const j = await r.json();
      if (csrfEl && j?.csrf) csrfEl.value = j.csrf;
    } catch {}
    if (!csrfEl?.value) {
      alert('Could not initialise security token. Please refresh and try again.');
      return;
    }
  }

  if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Sending…'; }

  try {
    const r = await fetch('/server/contact.php', {
      method: 'POST',
      body: new FormData(formEl),
      credentials: 'same-origin'
    });
    const j = await r.json().catch(() => ({ ok:false, error:'Bad response' }));
    alert(j.ok ? 'Thanks! Your message was sent.' : `Error: ${j.error}`);
    if (j.ok) formEl.reset();
  } catch {
    alert('Network error. Please try again.');
  } finally {
    if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = 'Send Message'; }
  }
});
