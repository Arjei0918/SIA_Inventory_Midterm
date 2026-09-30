# Inventory Figma Prototype Specification

## Prototype link

Current Inventory working prototype:

https://www.figma.com/make/WHtI5A7PsMuKbbhydBD6yM/Build-System-Integration?t=BP2lzBXOyck8v9gE-20&fullscreen=1

Set the final Figma sharing permission to **Anyone with the link can view** and set prototype mode to open on the Dashboard.

## Main desktop flow

```text
Dashboard
  ↓
Products
  ↓
Product Details
  ├── Edit → Edit Product → Save → Success
  └── Delete → Confirmation → Delete → Success
Products
  ├── Add Product → Save → Success
  ├── Stock In → Submit → Success
  └── Stock Out → Submit → Success
Stock History
```

## Required screens

| # | Screen | Main API data | Required interactions |
|---:|---|---|---|
| 1 | Dashboard | products + movements | open Products, Stock History |
| 2 | Product List | products | view, edit, delete, add |
| 3 | Product Details | product/{id} | edit, delete, back |
| 4 | Add Product | ProductInput | save, cancel |
| 5 | Edit Product | ProductInput | save changes, cancel |
| 6 | Delete Confirmation | product/{id} | confirm, cancel |
| 7 | Stock In | StockMovementInput | select product, quantity, submit |
| 8 | Stock Out | StockMovementInput | select product, quantity, submit |
| 9 | Stock History | movements | back to products |
| 10 | Mobile Dashboard | products + movements | mobile navigation |
| 11 | Mobile Product List | products | open details/forms |

## State screens

Each important flow needs:

- loading;
- populated;
- empty;
- API error;
- validation error;
- success;
- delete confirmation;
- cancel confirmation when unsaved changes exist.

## Realistic sample content

Use the same data as the mock API:

```text
Pencil — School Supplies — ₱10.00 — 321
Printer Ink — Computer Supplies — ₱850.00 — 15
Bond Paper — Office Supplies — ₱250.00 — 20
Notebook — School Supplies — ₱45.00 — 50
Ballpen — School Supplies — ₱15.00 — 115
```

## Mobile requirement

Create phone-width versions of the main screens. The desktop and mobile versions must use the same shared class design system and terminology.
