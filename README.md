# College Management System — Inventory Module

Inventory is one of the eight modules in the College Management System. This repository contains the Inventory group's midterm API contract, mock-data API, existing legacy PHP/MySQL implementation, and documentation needed to connect Inventory with the other modules.

## Midterm scope

The midterm focuses on:

- a documented REST API under `/api/v1`;
- mock data with no MySQL dependency for the midterm API;
- Swagger UI and an OpenAPI 3.0.3 contract;
- routes → services → data separation;
- RFC 7807-style Problem Details for 400 and 404 errors;
- a high-fidelity clickable Figma prototype;
- integration documentation and architecture documentation.

The original database-backed PHP implementation remains under `backend/` and is preserved as legacy/finals work. The new midterm API is under `server/`.

## Features

Inventory manages:

1. Product list and product details
2. Add product
3. Edit product
4. Delete product
5. Stock in
6. Stock out
7. Stock movement history
8. Low-stock monitoring
9. Read-only inventory data for integration with other modules

Low stock is defined as a quantity of **10 or less**, matching the existing Inventory system behavior.

## Technology

- PHP 8.1+
- Slim Framework 4
- Slim PSR-7
- JSON mock-data store (no database)
- OpenAPI 3.0.3
- Swagger UI
- Figma for the midterm frontend prototype

## Project structure

```text
SIA_Inventory/
├── server/
│   ├── data/
│   │   └── store.json
│   ├── routes/
│   │   ├── helpers.php
│   │   ├── health.php
│   │   ├── products.php
│   │   ├── stock.php
│   │   └── integration.php
│   ├── services/
│   │   ├── JsonStore.php
│   │   ├── ProblemDetails.php
│   │   ├── ProductService.php
│   │   └── StockService.php
│   └── public/
│       ├── index.php
│       ├── router.php
│       ├── openapi.yaml
│       └── docs/
│           └── index.html
│
├── docs/
│   ├── architecture.md
│   ├── data-model.md
│   ├── design-system.md
│   ├── integration.md
│   ├── prototype.md
│   └── decisions/
│       └── ADR-001-backend-framework.md
│
├── frontend/                 # Existing PHP/MySQL implementation, retained for finals/reference
├── backend/                  # Existing PHP/MySQL implementation, retained for finals/reference
├── composer.json
└── README.md
```

## Getting started

### Requirements

Install:

- PHP 8.1 or newer
- Composer
- a modern web browser
- Node.js/npm only if you want to run Redocly lint locally

MySQL and XAMPP are **not required** for the midterm API.

### 1. Install PHP dependencies

From the repository root:

```bash
composer install
```

### 2. Start the midterm API

```bash
php -S localhost:8080 -t server/public server/public/router.php
```

Keep that terminal open.

### 3. Open Swagger UI

Open:

```text
http://localhost:8080/docs/
```

The Swagger page loads the contract from:

```text
http://localhost:8080/openapi.yaml
```

### 4. Test the health endpoint

Open:

```text
http://localhost:8080/api/v1/health
```

Expected response:

```json
{
  "status": "ok"
}
```

### 5. Useful API endpoints

| Method | Endpoint | Purpose |
|---|---|---|
| GET | `/api/v1/health` | Health check |
| GET | `/api/v1/products` | List products |
| GET | `/api/v1/products/{id}` | Product details |
| POST | `/api/v1/products` | Add product |
| PUT | `/api/v1/products/{id}` | Edit product |
| DELETE | `/api/v1/products/{id}` | Delete product |
| GET | `/api/v1/stock/movements` | Stock history |
| POST | `/api/v1/stock/movements` | Stock in/out |
| GET | `/api/v1/integration/inventory` | Read-only integration data |

## Example requests

### Add a product

```bash
curl -X POST http://localhost:8080/api/v1/products ^
  -H "Content-Type: application/json" ^
  -d "{\"product_name\":\"Whiteboard Marker\",\"category\":\"Office Supplies\",\"price\":35,\"stock\":40}"
```

### Stock in

```bash
curl -X POST http://localhost:8080/api/v1/stock/movements ^
  -H "Content-Type: application/json" ^
  -d "{\"product_id\":5,\"movement_type\":\"IN\",\"quantity\":20,\"remarks\":\"New delivery\"}"
```

### Stock out

```bash
curl -X POST http://localhost:8080/api/v1/stock/movements ^
  -H "Content-Type: application/json" ^
  -d "{\"product_id\":5,\"movement_type\":\"OUT\",\"quantity\":5,\"remarks\":\"Issued to Student Affairs\"}"
```

The JSON store is intentionally used instead of a database. Changes made through POST/PUT/DELETE are written to `server/data/store.json`, so the API remains database-free while still allowing the full CRUD flow to be demonstrated.

## API behavior

### Successful responses

Successful collection responses use:

```json
{
  "data": []
}
```

Successful resource responses use:

```json
{
  "data": {}
}
```

### 400 Bad Request

Invalid input returns `application/problem+json`:

```json
{
  "type": "about:blank",
  "title": "Validation error",
  "status": 400,
  "detail": "The quantity field is required.",
  "instance": "/api/v1/stock/movements"
}
```

### 404 Not Found

Unknown IDs return:

```json
{
  "type": "about:blank",
  "title": "Product not found",
  "status": 404,
  "detail": "No product exists with the requested ID.",
  "instance": "/api/v1/products/999"
}
```

## Prototype

The midterm frontend is a Figma prototype, not a coded frontend.

Current working Inventory prototype link:

https://www.figma.com/make/WHtI5A7PsMuKbbhydBD6yM/Build-System-Integration?t=BP2lzBXOyck8v9gE-20&fullscreen=1

The required screen list and click flows are documented in `docs/prototype.md`.

## Integration

Inventory owns product and stock data. Other modules consume Inventory data through the read-only integration endpoint.

See:

- `docs/integration.md`
- `docs/architecture.md`
- `docs/data-model.md`

The final student/employee/shared-ID formats and assigned ports must match the class-wide agreements. Inventory's proposed product identifier is `INV-00001`, `INV-00002`, etc.; this is a proposal, not a claim that the class has already approved it.

## Validation

From the repository root:

```bash
npx @redocly/cli lint server/public/openapi.yaml
```

The contract is written as OpenAPI 3.0.3 and is intended to pass Swagger Editor validation and Redocly lint.

## Git workflow

Use:

```text
feat/...
fix/...
docs/...
```

Commit format:

```text
type(scope): what you did
```

Examples:

```text
feat(server): add inventory product endpoints
feat(server): add stock movement service
docs(integration): document module data ownership
docs(frontend): document inventory prototype screens
```

Do not push directly to `main`. Open a pull request and have at least one teammate review and approve it before merging.

Never commit `.env`, passwords, API keys, or other secrets.

## Project status

**Midterm Inventory package:** API contract, mock-data API structure, documentation, and prototype specification are prepared in this repository.

Before submission, the group must verify the class-approved design-system URL, assigned Inventory port, and actual agreements with the other groups against the final class agreements. Those values are intentionally not fabricated in this repository.
