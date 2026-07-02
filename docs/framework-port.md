# Framework Port Notes

The running demo uses plain PHP so it can run on PHP 7.4 without dependency setup. For the role's stack, this is how I would move it into a framework.

## Laravel

- Move route definitions from `routes/web.php` into the Laravel app.
- Convert current controllers into `App\Http\Controllers`.
- Move `TrackingService` and `OrderService` into `app/Services`.
- Replace file storage with Eloquent models:
  - `FunnelEvent`
  - `Order`
  - `MemberAccess`
- Run the included migrations in `database/migrations`.
- Put the purchase webhook in `routes/api.php` or exempt only that route from CSRF.
- Add feature tests for every funnel step.

## CodeIgniter

- Map the same endpoints in `app/Config/Routes.php`.
- Move controllers into `app/Controllers`.
- Move the service classes into `app/Libraries` or `app/Services`.
- Use CodeIgniter migrations for the three tables.
- Use filters for member access and webhook signature validation.

## Vue

The VSL page includes a small Vue widget for the launch signal strip. In production I would move it into Vite, compile it with the app frontend, and add tests around CTA timing and event emission.

