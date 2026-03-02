---
name: cafe-delight-admin
description: Build and extend the Cafe Delight admin panel using Laravel and Filament. Use when creating migrations, models, Filament resources, offers, orders, menu items, Spatie Media Library integration, role-based access, or order workflow logic.
---

# Cafe Delight Admin Panel (Laravel + Filament)

## When to Apply

Use this skill when working on:
- Migrations, models, Filament resources
- Categories, menu items, offers, orders
- Spatie Media Library, spatie/laravel-permission
- Order status workflow, offer scoping
- Admin UX, validation, deployment

---

## 1. Laravel Core Architecture

**Migrations**: Include proper indexes (`category_id`, `is_available`, `status`, `created_at`, `start_at`, `end_at`).

**Models**: Define relationships, query scopes, casts. Use typed properties and return types.

**Enums** (in `app/Enums`):
- `OrderStatus`: received, preparing, ready, out_for_delivery, delivered, cancelled
- `OfferType`: percentage, fixed
- `OfferScope`: menu, category, items

**Controllers**: Keep thin. Business logic goes in `app/Services` or `app/Actions`. Use Policies for authorization.

---

## 2. Filament Admin Development

**Resources**: Generate with `php artisan make:filament-resource` then customize.

**Forms**:
- Use sections and columns for layout
- Add validation rules (required, min, max)
- Conditional fields: show category selector when `scope=category`, show item selector when `scope=items`

**Tables**:
- Enable searchable columns
- Enable sortable columns
- Add filters (status, is_active, category)

**Actions**:
- Custom table actions: status update, print view
- Bulk actions: toggle availability (menu items)

**Navigation**: Customize labels and icons in resource config.

---

## 3. Database & Relationships

| Relationship | Implementation |
|--------------|----------------|
| Category → MenuItems | One-to-many, `category_id` on `menu_items` |
| Order → OrderItems | One-to-many, `order_id` on `order_items` |

**Offer scoping**:
- `scope=menu`: applies to entire menu
- `scope=category`: requires `category_id`, applies to that category
- `scope=items`: requires pivot/JSON for selected `menu_item_ids`

**Order snapshot**: In `order_items`, store `name_snapshot` and `price_snapshot` so price/name changes do not affect historical orders.

---

## 4. Image Handling (Spatie Media Library)

- Attach to `MenuItem` model via `HasMedia` / `InteractsWithMedia`
- Register collection (e.g. `images`) and conversions (e.g. `thumb`)
- Display thumbnails in Filament tables via `ImageColumn`
- Ensure `php artisan storage:link` is run for public disk

---

## 5. Authorization & Roles

- Install `spatie/laravel-permission`
- Create roles: `owner`, `staff`
- Restrict Filament panel access by role (check in `FilamentUser` or panel provider)
- Apply model policies where needed (e.g. only owner can delete)

---

## 6. Order Workflow Logic

**Status flow** (strict, forward-only except cancelled):
```
received → preparing → ready → out_for_delivery → delivered
         ↘ cancelled (from any non-delivered state)
```

- Prevent invalid backward transitions (e.g. delivered → preparing)
- Track `status_updated_at` on orders
- Add table filter by status

---

## 7. Validation & Data Integrity

- Offer: `end_at >= start_at`
- Price: `> 0`
- Menu item: `category_id` required
- Add unique constraints where needed (e.g. offer code per workspace if multi-tenant)

---

## 8. Clean Code Practices

- Strict typing (PHP 8+)
- Value objects/enums instead of magic strings
- Helper methods instead of duplicated logic
- Business logic out of views
- Run `./vendor/bin/pint` before commits

---

## 9. Admin UX Quality

- Clean forms: placeholders, helper text, logical grouping
- Use filters for better usability
- Test flows manually after each feature

---

## 10. Deployment Awareness (MVP)

- `php artisan storage:link`
- Run migrations in deployment
- Set `APP_DEBUG=false`, `APP_ENV=production`

---

## File Organization

| Purpose | Location |
|---------|----------|
| Models | `app/Models` |
| Filament resources | `app/Filament/Resources` |
| Enums | `app/Enums` |
| Actions | `app/Actions` |
| Services | `app/Services` |
| Policies | `app/Policies` |
| Migrations | `database/migrations` |

---

## Additional Reference

For detailed patterns (migration templates, Filament form examples, offer scope logic), see [reference.md](reference.md).
