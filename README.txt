# Jack Bell portfolio redesign

This folder contains a refreshed PHP portfolio site that keeps your dynamic `projects` section working.

## Included files
- `index.php` — redesigned homepage
- `style.css` — new cyber-style UI
- `main.js` — menu, scroll reveal, contact form AJAX, CSRF fetch
- `contact.php` — simplified contact handler
- `config.php` — safe environment-variable based DB config
- `gif.gif` — reused uploaded visual
- `drawing.svg` — reused uploaded graphic

## Important
Do not deploy the old `config.php` or `secrets.php` with hard-coded credentials.
Use environment variables instead:

- `PORTFOLIO_DB_HOST`
- `PORTFOLIO_DB_NAME`
- `PORTFOLIO_DB_USER`
- `PORTFOLIO_DB_PASS`
- `PORTFOLIO_TO_EMAIL`

## Suggested deploy steps
1. Back up your current site.
2. Upload these files.
3. Keep your existing `admin.php`, `csrf.php`, and project uploads folder.
4. Point the homepage to this `index.php`.
5. Set environment variables in your hosting panel.
6. Test database connection and contact form.
