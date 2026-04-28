# Genzee Jewels WordPress Theme Rules

## Project Goal
Build a custom WordPress + WooCommerce theme for Genzee Jewels, a budget fashion jewellery brand selling mostly under ₹500. The theme must feel bold, youthful, impulse-driven, mobile-first, and conversion-focused.

## Architecture Rules
- This is a WordPress/WooCommerce PHP theme, not a React SPA.
- Do not create page-builder dependency.
- Do not use Elementor, Gutenberg-only layouts, or heavy plugins unless explicitly requested.
- Use PHP template parts for reusable sections and components.
- Use WooCommerce product data wherever product cards are shown.
- Do not hardcode real product cards unless used as fallback demo content.
- Keep homepage sections split into separate files under template-parts/sections/.
- Keep reusable UI pieces under template-parts/components/.
- Keep CSS modular: base, layout, components, homepage, woocommerce.
- Keep JavaScript minimal. Only use JS for mobile nav, cart drawer, countdowns, search interactions, and small UX enhancements.

## Brand Rules
- Do not make strong quality claims like anti-tarnish, waterproof, sweatproof, lifetime plating, hypoallergenic, or premium gold unless the product data explicitly says so.
- Main messaging: affordable, trendy, giftable, under ₹500, new drops, pink packaging, impulse-friendly.
- Tone can be playful and Gen Z-friendly, but not cringe.
- Design should be bold, colorful, clean, fast, and mobile-first.

## Code Quality Rules
- Escape all output using esc_html(), esc_url(), esc_attr(), or wp_kses_post().
- Sanitize all incoming data.
- Use wp_enqueue_style() and wp_enqueue_script(); never hardcode asset links directly.
- Use get_template_part() for reusable sections.
- Use semantic HTML: header, nav, main, section, footer.
- Buttons and interactive elements must be keyboard accessible.
- Images must use proper alt text, lazy loading where appropriate, and responsive sizing.
- No duplicate CSS blocks.
- No inline styles unless absolutely unavoidable.
- Do not modify unrelated pages while working on one page.

## Workflow Rules
- Before coding, inspect existing files and produce a short implementation plan.
- Work one page at a time.
- For each task, list changed files before editing.
- After implementation, verify:
  - no PHP fatal errors
  - homepage loads
  - mobile layout works at 360px and 390px
  - tablet layout works at 768px
  - desktop layout works at 1280px and 1440px
  - no obvious overflow
  - product sections use WooCommerce data when products exist
- Commit only after the page passes the checklist.

## Phase Tracking

| Phase | Status | Scope |
|-------|--------|-------|
| Phase 1 | Complete | Theme scaffold — style.css, functions.php, index.php, header.php, footer.php, front-page.php (placeholders), assets/css/base.css, header.css, footer.css, front-page.css, assets/js/main.js |
| Phase 2 | Complete | Header + footer design — announcement bar marquee, logo, primary nav, mobile hamburger drawer, footer columns, social icons |
| Phase 3 | Complete | Homepage hero section + trust strip + category grid (PHP template parts + CSS) |
| Phase 4 | Complete | Hero full implementation — editorial tile, deal tile, mini category tiles, countdown JS |
| Phase 5 | Complete | Reusable product card — template-parts/components/product-card.php, price.php, stars.php, assets/css/components.css |
| Phase 6 | Next | Homepage product sections — Bestsellers grid + New Drops horizontal scroll (WooCommerce loops with demo fallback, using Phase 5 card) |

## File Map (Phase 1)

```
genzee-wordpress-2/
├── style.css              ← WordPress theme header only (no styles)
├── functions.php          ← Theme support, menus, enqueue
├── index.php              ← Fallback template
├── header.php             ← HTML open, wp_head, skip link, nav
├── footer.php             ← Semantic footer, wp_footer
├── front-page.php         ← Homepage (7 placeholder sections)
├── assets/
│   ├── css/
│   │   ├── base.css       ← Design tokens, reset, typography, buttons, utils
│   │   ├── header.css     ← Header shell styles
│   │   ├── footer.css     ← Footer shell styles
│   │   └── front-page.css ← Placeholder section styles (Phase 1 only)
│   └── js/
│       └── main.js        ← Mobile nav toggle, DOM-ready guard
└── design_files/          ← React design reference — DO NOT MODIFY
```