---
paths:
  - 'app/**'
---

# App

## Checkout goes through PlaceOrderAction, never a controller
All stock arithmetic happens in App\Actions\PlaceOrderAction inside a DB transaction with lockForUpdate() on the products, so two concurrent checkouts cannot both claim the last unit. StoreOrderRequest::after() pre-checks stock for a friendly error; the action re-checks under the lock because the request check is racy on its own. Keep both.

Order lines snapshot product_name and price at purchase time — never join back to products for historical pricing. Money columns are decimal(10,2) with 'decimal:2' casts; cast to (float) before arithmetic.

lazy loading is blocked outside production (AppServiceProvider), so eager-load relations in controllers or views will throw.
