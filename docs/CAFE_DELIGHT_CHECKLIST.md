# ✅ Cafe Delight — Admin Panel Completion Checklist
(Laravel + Filament)

> Cursor must follow steps in order.
> After completing a step, mark it as:
> [x] Step Completed
> Do NOT skip steps.
> Do NOT combine multiple steps into one.

---

## 🏗️ Phase 1 — Project Setup

- [x] 1. Install fresh Laravel project
- [x] 2. Configure .env (DB connection, app name)
- [ ] 3. Install Filament and set up admin panel
- [x] 4. Install spatie/laravel-permission
- [x] 5. Install Spatie Media Library
- [x] 6. Configure storage (public disk + storage:link)
- [x] 7. Install Laravel Pint (code formatter)
- [x] 8. Create base roles (owner, staff)
- [x] 9. Protect Filament panel by role
- [x] 10. Create initial admin user (owner role)
- [x] 11. Add .env.example + README local setup
- [x] 12. Clean baseline commit
- [ ] Verify admin login works (manual)

---

## 📂 Phase 2 — Categories Module

- [x] 13. Create categories migration
- [x] 14. Create Category model + relationships
- [x] 15. Create CategoryResource (Filament)
- [x] 16. Add form validation + UX (helper text, slug normalise)
- [x] 17. Add filters + table enhancements (active filter, toggle, empty state)
- [x] 18. Enforce unique slug (DB index, ignore on edit, auto-generate, suffix)
- [x] 19. Manual QA (Categories)
- [x] 20. Finalize Categories module (Pint, no debug)

---

## 🍔 Phase 3 — Menu Items Module

- [x] 21. Create menu_items migration
- [x] 22. Create MenuItem model + relationships
- [x] 23. Integrate Media Library with MenuItem (menu_images, single, images only)
- [x] 24. Create MenuItemResource (form sections, table, image column)
- [x] 25. Add price validation + currency formatting
- [x] 26. Add category + availability + featured filters
- [x] 27. Add bulk actions (toggle availability, delete with confirmation)
- [x] 28. Enforce slug uniqueness + auto strategy
- [x] 29. Add soft deletes (migration, trait, Trashed filter, restore)
- [x] 30. Manual QA for Menu Items module
- [x] 31. Mark Menu Items module complete

---

## 🎯 Phase 4 — Offers Module

- [x] 32. Create offers migration
- [x] 33. Create Offer model
- [x] 34. Create OfferType enum
- [x] 35. Create OfferScope enum
- [x] 36. Create OfferResource
- [x] 37. Add start/end date validation
- [x] 38. Add conditional fields by scope
- [x] 39. Add value rules per type
- [x] 40. Manual QA for Offers module
- [x] 41. Mark Offers module complete

---

## 🧾 Phase 5 — Orders Module

- [x] 42. Create orders migration
- [x] 43. Create order_items migration
- [x] 44. Create Order model + relationships
- [x] 45. Create OrderItem model
- [x] 46. Create OrderStatus enum (label, color, canTransitionTo)
- [x] 47. Add order code generator (OrderCodeService)
- [x] 48. Create OrderResource (list + view, items relation manager)
- [x] 49. Add status update action with workflow rules
- [x] 50. Add status tabs (All + per-status with badge)
- [x] 51. Add print-friendly view
- [x] 52. Test order workflow transitions (order list perf: select columns, pagination 25/50)
- [x] 53. Mark Orders module complete

---

## 🛡️ Phase 6 — Security & Validation

- [x] 54. Add policies where required (Category, MenuItem, Offer, Order)
- [x] 55. Restrict staff permissions properly (owner-only: delete category/order, forceDelete)
- [x] 56. Validate all required fields (categories name/slug unique; menu/offers/order rules)
- [x] 57. Prevent invalid status transitions (OrderStatusService single source of truth)
- [x] 58. Add database indexes (composite indexes migration) + status_updated_by audit
- [x] 59. Remove debug code (no dd/dump; .env in .gitignore)
- [x] 60. Mark Security phase complete — **Manual review:** guests/no-role blocked from /admin; staff restrictions; CSRF intact; no public admin routes

---

## 🎨 Phase 7 — UI Consistency

- [x] 61. Standardize badge colors (OrderStatus, OfferType, OfferScope enum color(); resources use enum only)
- [x] 62. Standardize toggle styling (ToggleColumn + labels; form toggles with helper text)
- [x] 63. Ensure empty states have messages (friendly copy on all four resources)
- [x] 64. Ensure responsive layout (striped tables; Filament default sidebar/forms)
- [x] 65. Clean spacing + sections (helper text on forms; consistent sections)
- [x] 66. Final UI review pass (nav labels/icons; cohesive tables and forms)
- [x] 67. Mark UI phase complete (Pint, no pending migrations)

---

## 🚀 Phase 8 — Finalization

- [x] 68. Run full manual admin flow test (owner + staff; see test script in step)
- [x] 69. Optimize queries where needed (MenuItemResource eager load category; orders already scoped)
- [x] 70. Run Pint formatting (composer format / composer lint)
- [x] 71. Clean unused imports/files (.env not committed; .env.example + README deployment notes)
- [x] 72. Final production config check (config:cache, route:cache; README deployment notes)
- [x] 73. Final commit: Admin Panel MVP Complete (migrate:fresh --seed, lint; optional tag v1.0-admin-mvp)

---

# 🎯 Definition of Done

Admin panel is complete only when:
- Admin login works
- Categories CRUD works
- Menu Items CRUD with images works
- Offers CRUD works
- Orders visible + status updatable
- Role restrictions enforced
- No UI inconsistencies
- No validation gaps
