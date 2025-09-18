# Hero Carousel: Implementation, Fixes, and Tuning

This document summarizes the work done to get the home page hero carousel aligned and behaving correctly, plus how to tweak it going forward.

## Outcomes

- Controls are anchored to the bottom of the hero (not mid-screen).
- Dots are bottom-centered; arrows are bottom-left and bottom-right on `sm+` screens.
- Hero text is truly vertically centered via flex centering and no longer overlaps with controls.
- Images are centered and consistently fill the area without awkward cropping.

## Files Touched

- `resources/js/Pages/Home.jsx:103` — Slide `<img>` uses `object-cover object-center`.
- `resources/js/Pages/Home.jsx:113` — Text container uses absolute full overlay + flex centering (`absolute inset-0 ... flex items-center justify-center text-center z-10`).
- `resources/js/Pages/Home.jsx:139` — Controls moved outside the text container and anchored at the bottom with proper z-index and pointer events.

## What Was Wrong

- Controls were nested inside a centered flex box, so they appeared near the middle.
- Dots overlapped CTAs because the text block had no bottom “safe area”.
- Arrow positions weren’t anchored to the hero bottom edges.
- Some slide images looked off-center due to default object position.

## Fixes Applied

1) Controls layout and positioning

- Moved controls to be siblings of the content block and absolutely positioned them at the bottom.
- Dots are centered with `absolute left-1/2 -translate-x-1/2 bottom-6`.
- Arrows are positioned bottom-left and bottom-right with `absolute left-4 sm:left-6 bottom-6` and `absolute right-4 sm:right-6 bottom-6`.
- Gave controls `z-20` and the content block `z-10` to ensure proper layering.
- Wrapped controls in a container with `pointer-events-none` and set interactive parts to `pointer-events-auto` so they don’t block other interactions inadvertently.

2) Text centering

- Replaced bottom-padding approach with true vertical centering using an absolute full overlay and flex alignment. This centers the text block regardless of viewport height while controls remain anchored at the bottom.

3) Image presentation

- Updated slide images to `object-cover object-center` so they fill the viewport area while keeping the focal point centered.

## Relevant Code (key snippets and rationale)

- Image centering: `resources/js/Pages/Home.jsx:103`

```jsx
<img
  src={slide.image}
  alt={slide.alt}
  className="w-full h-full object-cover object-center"
  loading={index === currentSlide ? 'eager' : 'lazy'}
/>
```

- Text container with absolute centering: `resources/js/Pages/Home.jsx:113`

```jsx
<div className="absolute inset-0 px-4 sm:px-6 lg:px-8 z-10 flex items-center justify-center text-center">
  {/* Headline, description, CTAs */}
</div>
```

- Controls anchored to bottom: `resources/js/Pages/Home.jsx:139`

```jsx
{/* Dots */}
<div className="absolute left-1/2 -translate-x-1/2 bottom-6 z-20 flex items-center space-x-2 pointer-events-auto">
  {slides.map((_, index) => (
    <button /* ...dot button classes... */ />
  ))}
</div>

{/* Left/Right arrows */}
<button className="hidden sm:flex /* ... */ absolute left-4 sm:left-6 bottom-6 z-20 pointer-events-auto" />
<button className="hidden sm:flex /* ... */ absolute right-4 sm:right-6 bottom-6 z-20 pointer-events-auto" />
```

## Behavior Summary

- Auto-advances every 6s (`setInterval`), and you can click dots/arrows to navigate.
- Non-active slides are `aria-hidden` for screen readers; controls have descriptive labels.

## How to Tweak

- Move dots/arrows closer/further from the edge:
  - Adjust `bottom-6` to `bottom-4` or `bottom-8` in the controls.
- Show arrows on mobile:
  - Remove `hidden sm:flex` on the arrow buttons.
- Nudge text up/down subtly:
  - Add small `mt-*`/`mb-*` to inner wrapper, or adjust controls' `bottom-*` spacing.
- Faster/slower auto-play:
  - Edit the `6000` ms in the `useEffect` interval.

## Image Guidelines

- Use images with a wide aspect ratio (e.g., 16:9) and enough resolution (≥ 1600×900) to avoid pixelation.
- Keep the key focal point near center; we deliberately use `object-center`.

## Notes and Next Ideas

- Optional: add a pause on hover or when the tab/window is not visible.
- Optional: add focus-visible styles to dots/arrows for improved keyboard navigation.

## Verification

1. Run `npm run dev` (or `npm run build`).
2. Open the homepage and check:
   - Dots are bottom-centered and clickable.
   - Arrows sit at the bottom corners on `sm+`.
   - Headline/CTAs never overlap the controls.
   - Images fill the hero and look centered across breakpoints.
