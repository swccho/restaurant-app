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
- [ ] 31. Mark Menu Items module complete

---

## 🎯 Phase 4 — Offers Module

- [ ] 32. Create offers migration
- [ ] 33. Create Offer model
- [ ] 34. Create OfferType enum
- [ ] 35. Create OfferScope enum
- [ ] 36. Create OfferResource
- [ ] 37. Add start/end date validation
- [ ] 38. Add conditional fields by scope
- [ ] 39. Add active toggle
- [ ] 40. Test offer CRUD
- [ ] 41. Mark Offers module complete

---

## 🧾 Phase 5 — Orders Module

- [ ] 42. Create orders migration
- [ ] 43. Create order_items migration
- [ ] 44. Create Order model + relationships
- [ ] 45. Create OrderStatus enum
- [ ] 46. Add snapshot fields in order_items
- [ ] 47. Create OrderResource (read-focused)
- [ ] 48. Add status badge column
- [ ] 49. Add status update action
- [ ] 50. Add order filters (status tabs)
- [ ] 51. Add print-friendly view
- [ ] 52. Test order workflow transitions
- [ ] 53. Mark Orders module complete

---

## 🛡️ Phase 6 — Security & Validation

- [ ] 54. Add policies where required
- [ ] 55. Restrict staff permissions properly
- [ ] 56. Validate all required fields
- [ ] 57. Prevent invalid status transitions
- [ ] 58. Add database indexes
- [ ] 59. Remove debug code
- [ ] 60. Mark Security phase complete

---

## 🎨 Phase 7 — UI Consistency

- [ ] 61. Standardize badge colors
- [ ] 62. Standardize toggle styling
- [ ] 63. Ensure empty states have messages
- [ ] 64. Ensure responsive layout
- [ ] 65. Clean spacing + sections
- [ ] 66. Final UI review pass
- [ ] 67. Mark UI phase complete

---

## 🚀 Phase 8 — Finalization

- [ ] 68. Run full manual admin flow test
- [ ] 69. Optimize queries where needed
- [ ] 70. Run Pint formatting
- [ ] 71. Clean unused imports/files
- [ ] 72. Final production config check
- [ ] 73. Final commit: Admin Panel MVP Complete

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
