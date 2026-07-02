# Interview Prep Notes

## One-minute pitch

I understand this role as full-stack development for revenue-critical funnels. The work is not only page building; it affects paid traffic, checkout completion, affiliate attribution, upsells, member access, and launch quality. I built this mini-case to show how I think through the full path from VSL to checkout to webhook to members area.

## How I would explain the architecture

- `public/index.php` is the front controller and router.
- `FunnelController` owns the user journey.
- `AdminController` owns operational QA visibility.
- `TrackingService` preserves attribution and records funnel events.
- `OrderService` receives purchase events and creates access.
- `database/schema.sql` shows how I would model this in SQL.

## Strong answers to likely questions

### How do you QA a funnel before launch?

I test it like a buyer and like the person who gets paged if it breaks. I check video, audio, CTA timing, every button, mobile layout, checkout redirect, query parameter preservation, webhook delivery, thank-you routing, member access, console errors, network waterfall, Cloudflare cache rules, and logs. I also want a known rollback point before deploy.

### How would you debug a broken checkout?

I would reproduce the issue and isolate where it fails: click handler, redirect, marketplace checkout, payment result, webhook, thank-you page, or member access. Then I would inspect the browser console, network requests, query parameters, server logs, webhook logs, and marketplace configuration.

### How would you speed up a VSL page?

I would measure first with Lighthouse and the DevTools network tab. Then I would optimize video delivery, image formats, CSS/JS size, script loading order, third-party scripts, CDN caching, compression, and mobile layout. I would cache static assets aggressively but avoid caching dynamic checkout and members pages incorrectly.

### What do you know about ClickBank/Digistore24 integration?

The pattern is preserving affiliate attribution into checkout, receiving a server-to-server payment notification, verifying it, storing the order, and granting access only after a valid purchase event. I would read the exact API docs for signatures and edge cases before production implementation.

## Questions to ask them

- Is this role directly with RZ HR Studio or with one of your clients?
- What stack is the client using today: Laravel, Symfony, CodeIgniter, WordPress, or custom PHP?
- What currently breaks most often in the funnel process?
- Do you have staging environments before production?
- How do you currently run A/B tests?
- Which marketplace is most important: ClickBank, Digistore24, or both?
- What does success look like in the first 30 to 60 days?
