# PRD: TrueSpace Brand Styling Update

**Status:** Draft
**Date:** 2026-03-04
**Branch:** `feature/truespace-styling`

---

## Overview

Update the Laravel interview app's visual design to match the look and feel of [www.truespace.com](https://www.truespace.com). Changes are derived from inspecting TrueSpace's live site with browser DevTools. This is a pure styling change — no functionality is altered.

---

## Design Tokens from TrueSpace.com (via DevTools)

| Token | Current App | TrueSpace Value |
|---|---|---|
| Page background | `#f3f4f6` (gray-100) | `#fcfaf9` (warm off-white) |
| Primary text | `#111827` (gray-900) | `#3f3f3f` (dark charcoal) |
| Primary accent | `#4f46e5` (indigo-600) | `#00a3c9` (teal) |
| Secondary accent | — | `#2ea3f2` (blue) |
| Font family | Figtree | `degular` → **Inter** (free substitute) |
| Card border radius | 8px (rounded-lg) | 10px (rounded-xl) |
| Card shadow | flat `shadow-sm` | `0px 2px 18px rgba(0,0,0,0.08)` on hover |
| Button background | `#1f2937` (gray-800) | `#00a3c9` (teal) |
| Focus/active ring | indigo | teal |

> **Note on font:** TrueSpace uses `degular`, a paid typeface. **Inter** is substituted — it shares the same geometric, modern sans-serif character and is freely available via Google Fonts.

---

## Files to Change

### 1. `tailwind.config.js`
- Register custom color palette under `ts-*` namespace:
  - `ts-teal: #00a3c9`
  - `ts-blue: #2ea3f2`
  - `ts-cream: #fcfaf9`
  - `ts-text: #3f3f3f`
- Switch `fontFamily.sans` from `Figtree` to `Inter`

**Why:** Central design token registry. All Blade files reference Tailwind utilities, so defining colors here propagates changes everywhere without touching every template individually.

---

### 2. `resources/css/app.css`
- Add Google Fonts import for Inter (weights 400, 500, 600, 700)

**Why:** Inter must be loaded before Tailwind's `font-sans` utility applies it.

---

### 3. `resources/views/layouts/app.blade.php`
- Remove Figtree `<link>` tag (font now loaded via CSS)
- Change page background: `bg-gray-100` → `bg-ts-cream`

**Why:** Main authenticated layout wraps every logged-in page — one change propagates to all views.

---

### 4. `resources/views/layouts/guest.blade.php`
- Remove Figtree `<link>` tag
- Change page background: `bg-gray-100` → `bg-ts-cream`

**Why:** Guest layout wraps all auth pages (login, register, password reset).

---

### 5. `resources/views/layouts/navigation.blade.php`
- Active nav link bottom border: `border-indigo-400` → `border-ts-teal`
- Active nav link focus border: `focus:border-indigo-700` → `focus:border-ts-teal`
- User dropdown button hover text: `hover:text-gray-700` (no change — already neutral)

**Why:** The teal underline on active nav items is the most visible brand signal in the nav bar.

---

### 6. `resources/views/components/primary-button.blade.php`
- Background: `bg-gray-800` → `bg-ts-teal`
- Hover: `hover:bg-gray-700` → `hover:bg-ts-blue`
- Focus ring: `focus:ring-indigo-500` → `focus:ring-ts-teal`

**Why:** CTAs are the highest-impact brand touchpoint. TrueSpace's primary actions use teal.

---

### 7. `resources/views/components/nav-link.blade.php`
- Active state border: `border-indigo-400` → `border-ts-teal`
- Active state focus: `focus:border-indigo-700` → `focus:border-ts-teal`

**Why:** Matches nav styling update above; this is the reusable component version.

---

### 8. `resources/views/components/text-input.blade.php`
- Focus border: `focus:border-indigo-500` → `focus:border-ts-teal`
- Focus ring: `focus:ring-indigo-500` → `focus:ring-ts-teal`

**Why:** Consistent accent color across all interactive elements (buttons, links, inputs).

---

### 9. `resources/views/components/application-logo.blade.php`
- Swap the default Laravel SVG for a TrueSpace logo `<img>` tag
- Expected asset path: `public/images/truespace-logo.svg` (or `.png`)
- A fallback text wordmark is included if the file is absent

**Why:** The logo is the most immediate brand signal; using the Laravel default breaks immersion.

> **Action required:** Drop the TrueSpace logo file into `public/images/` before building.

---

### 10. `resources/views/dashboard.blade.php`
- Stat card accent (Active Assessments count): `text-indigo-600` → `text-ts-teal`
- Table row links: `text-indigo-600 hover:text-indigo-900` → `text-ts-teal hover:text-ts-blue`
- Card border radius: `sm:rounded-lg` → `sm:rounded-xl`
- Card shadow: add `hover:shadow-md` transition

**Why:** Dashboard is the primary post-login screen and the richest with indigo references.

---

### 11. All other views (grep-driven sweep)
A `grep -r "indigo"` pass will catch remaining uses across:
- `resources/views/assessments/` (index, show, edit, take)
- `resources/views/auth/` (login, register, etc.)
- `resources/views/profile/`
- `resources/views/livewire/`

Each `indigo-*` occurrence will be replaced with its `ts-teal` or `ts-blue` equivalent.

---

## Card / Shadow Treatment

All white card containers (`bg-white shadow-sm sm:rounded-lg`) will be updated to:
- `sm:rounded-xl` — matches TrueSpace's `10px` radius
- `transition-shadow hover:shadow-[0px_2px_18px_rgba(0,0,0,0.08)]` — matches TrueSpace's hover depth effect

---

## Out of Scope

- No layout or information architecture changes
- No functionality changes
- No animation changes beyond existing Tailwind transitions
- No dark mode support

---

## Open Questions

| # | Question | Status |
|---|---|---|
| 1 | Logo file path — where will the asset be placed? | **Pending** — assumed `public/images/truespace-logo.svg` |
| 2 | Should `danger-button` and `secondary-button` also adopt teal theming? | Pending |

---

## Deliverable

A GitHub PR against `main` with all styling changes, a summary of what changed and why, and this PRD linked in the PR description.
