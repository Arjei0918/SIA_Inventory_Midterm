# Instructor Handout — Inventory Module

## Project

College Management System — Inventory Module

## Module purpose

Inventory manages products, current stock, stock movements, availability, and low-stock monitoring. It exposes REST API data for integration with the other College Management System modules.

## Midterm technology

- PHP 8.1+
- Slim Framework 4
- JSON mock data
- OpenAPI 3.0.3
- Swagger UI
- Figma prototype

## Run

```bash
composer install
php -S localhost:8080 -t server/public server/public/router.php
```

Swagger UI:

```text
http://localhost:8080/docs/
```

Health:

```text
http://localhost:8080/api/v1/health
```

## API endpoints

- `GET /api/v1/health`
- `GET /api/v1/products`
- `GET /api/v1/products/{id}`
- `POST /api/v1/products`
- `PUT /api/v1/products/{id}`
- `DELETE /api/v1/products/{id}`
- `GET /api/v1/stock/movements`
- `POST /api/v1/stock/movements`
- `GET /api/v1/integration/inventory`

## Main prototype features

- Inventory dashboard
- Product list
- Product details
- Add/edit/delete product
- Stock in
- Stock out
- Stock movement history
- Low-stock status
- Loading, empty, error, validation, success, and confirmation states
- Desktop and mobile screens

## Figma

Current working prototype:

https://www.figma.com/make/WHtI5A7PsMuKbbhydBD6yM/Build-System-Integration?t=BP2lzBXOyck8v9gE-20&fullscreen=1

The final Figma link must be shared with **Anyone with the link can view**.

## Integration

Inventory owns product and stock data. Other modules consume Inventory's read-only integration view:

```text
GET /api/v1/integration/inventory
```

The class-wide product ID format and final cross-module agreements must be confirmed before submission.
