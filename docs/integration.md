# Inventory Integration

## 1. Integration principle

Inventory is the source of truth for product and stock information.

Other modules may read Inventory data through the Inventory API. They should not directly access the Inventory data store.

## 2. Integration endpoint

```text
GET /api/v1/integration/inventory
```

Response fields:

| Field | Meaning |
|---|---|
| `productId` | Inventory-facing product reference, proposed as `INV-00001` format |
| `productName` | Product name |
| `category` | Inventory category |
| `unitPrice` | Current unit price |
| `stockQuantity` | Current available quantity |
| `availability` | `Available` or `Out of Stock` |
| `lowStock` | `true` when stock is 10 or below |

## 3. Module connections

| Module | Inventory relationship | Data direction | Current contract |
|---|---|---|---|
| Landing Page | Displays public inventory availability when required | Inventory → Landing Page | `GET /api/v1/integration/inventory` |
| Finance | May consume product prices/stock for finance workflows | Inventory → Finance | Inventory endpoint available; exact Finance use case requires class agreement |
| Library | May consume supply/equipment availability | Inventory → Library | Inventory endpoint available; exact Library use case requires class agreement |
| Registrar | May request inventory-related availability when a registrar workflow requires it | Inventory ↔ Registrar | No direct write contract defined |
| Student Portal | May display availability if the class includes an inventory-related student workflow | Inventory → Student Portal | No direct write contract defined |
| Faculty | May request supply availability if required by the class workflow | Inventory → Faculty | No direct write contract defined |
| Clinic | May request clinic supply availability if included in the class workflow | Inventory → Clinic | No direct write contract defined |

These relationships deliberately avoid inventing undocumented business transactions. The owning group for each cross-module workflow must be recorded in the class agreements log.

## 4. ID agreement

Inventory's proposed integration-facing product ID format is:

```text
INV-00001
```

Example:

```text
productId: INV-00005
```

This is a proposal from the Inventory group and must be reconciled with the class-wide ID agreement before submission.

## 5. Port agreement

The API is configured for local development on:

```text
http://localhost:8080
```

If the class assigned a different Inventory port, update the OpenAPI server URL and the group's final run instructions before submission.

## 6. Prism / contract-first integration

When another group's API is not ready, Inventory can consume their OpenAPI contract through a Prism mock. Inventory itself exposes its OpenAPI contract at:

```text
server/public/openapi.yaml
```

Other groups can use this contract to mock Inventory responses while their own implementation is still in progress.

## 7. Agreements log

| Date | Groups | Agreement | Status |
|---|---|---|---|
| 2026-09-29 | Inventory | Inventory owns product and stock data within the Inventory module. | Recorded by Inventory |
| 2026-09-29 | Inventory | Inventory exposes read-only integration data through `/api/v1/integration/inventory`. | Recorded by Inventory |
| 2026-09-29 | Inventory | Product integration ID proposal is `INV-00001` format. | Proposed; class confirmation required |
| 2026-09-29 | Inventory | Local development API port is 8080 in this repository. | Repository default; class port confirmation required |

No agreement with another group is claimed as final unless it has been recorded and approved by the participating groups.
