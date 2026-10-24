---
paths:
  - 'resources/views/**'
---

# Views

## Layouts are anonymous components, not a views/layouts folder
Page layouts live at resources/views/components/layouts/{app,admin}.blade.php and are used as <x-layouts.app> / <x-layouts.admin>, not @extends. A file at resources/views/layouts/app.blade.php will NOT resolve as <x-layouts.app> — Blade only looks under components/.

The storefront layout takes `title`/`description` props; the admin layout takes `title`/`heading`/`subheading` plus an `actions` slot for page buttons. Both declare @props, so any new prop must be added there or it silently lands in $attributes.
