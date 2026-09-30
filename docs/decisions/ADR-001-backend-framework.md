# ADR-001: Backend Framework

- Status: Accepted
- Date: 2026-09-29
- Decision: Use Slim Framework 4 with PHP 8.1+ for the midterm Inventory API.

## Context

The Inventory group already has an existing PHP/XAMPP implementation. The midterm requires a REST API, a clear routes → services → data architecture, OpenAPI documentation, mock data, and a locally runnable API.

## Decision

Use Slim Framework 4 with Slim PSR-7.

The midterm API stores its mock data in a JSON file rather than MySQL. This keeps the API database-free while allowing POST, PUT, and DELETE demonstrations to persist during local development.

## Reasons

1. The group already has PHP experience from the existing Inventory system.
2. Slim provides explicit routing without forcing a large application structure.
3. The routes → services → data requirement maps cleanly to the project.
4. Slim works with the PHP development environment already used by the group.
5. The JSON store allows the midterm to demonstrate CRUD behavior without introducing a database.

## Consequences

### Positive

- Familiar language and environment.
- Small dependency footprint.
- Clear API routing.
- Easy OpenAPI alignment.
- No MySQL setup is required for the midterm API.

### Negative

- Composer is required.
- The JSON data store is not a replacement for a production database.
- Authentication and authorization are outside the midterm scope.

## Finals transition

For finals, the service layer can be retained while the JSON data layer is replaced by a database repository. This minimizes changes to the route contract.
