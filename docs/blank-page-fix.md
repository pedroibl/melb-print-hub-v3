# Blank Page Fix (Local Development)

## What Was Happening
- When running the Laravel app locally with the Vite dev server, the browser console was full of `Content-Security-Policy` errors. Entries such as `Refused to load the script 'http://[::1]:5173/@vite/client'` showed that all Vite assets were being blocked.
- The existing CSP only allowed `https://` CDN hosts (plus the live domain) and explicitly listed IPv6 loopback URLs (`http://[::1]:5173`). Most browsers treat bracketed IPv6 literals as invalid inside CSP directives, so the sources were ignored and Inertia returned a blank page.

## What We Changed
- We kept the hardened production CSP, but we now relax it automatically when the app is running in the `local` environment.
- In `app/Http/Middleware/SecurityHeaders.php`, the middleware detects `app()->environment('local')` and swaps in a Vite-friendly policy:
  - `script-src`, `style-src`, `style-src-elem`, `font-src`, `img-src`, and `connect-src` allow any `http:` or `https:` origin (plus WebSocket equivalents) so the dev server can inject assets and hot-module reload over `ws://`.
  - Other directives (`frame-src`, `object-src`, `base-uri`, etc.) stay restrictive so we don't accidentally open up more than we need to in production.
- Because this middleware runs after the old `ContentSecurityPolicy` middleware in the stack, its relaxed header overwrites the stricter one only for local requests.

## Result
- Reloading the app locally now loads the Vite client and React bundle without CSP violations, and the page renders instead of staying blank.
- Production and staging environments keep the original locked-down CSP, so there is no regression for live traffic.

## Follow-Up / Tips
- If you add additional local tooling (Storybook, alternate ports, etc.), it will continue to work because the policy uses scheme wildcards (`http:`/`https:`) rather than hard-coded host lists.
- Should you need to audit the final CSP header, check the response headers in your browser dev tools or run `curl -I http://localhost:8000` while the dev server is running.
