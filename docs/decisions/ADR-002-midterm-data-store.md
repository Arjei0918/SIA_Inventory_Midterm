# ADR-002: JSON Mock Data Store for Midterm

- Status: Accepted
- Date: 2026-09-29
- Decision: Store midterm mock data in `server/data/store.json`.

## Context

The midterm explicitly requires mock data and no database. The Inventory group also needs POST, PUT, DELETE, stock-in, and stock-out flows that can be demonstrated in Swagger UI and represented by the Figma prototype.

## Decision

Use a JSON file as the data layer for the midterm.

`JsonStore` is responsible only for reading and writing the JSON file. Product and stock business rules remain in the service layer.

## Consequences

The API can demonstrate realistic CRUD and stock workflows without MySQL. The JSON store is intentionally a development/mock-data solution and is not intended for production deployment.

For finals, the data layer can be replaced with a database repository without changing the API routes.
