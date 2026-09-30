# API Test Plan

Use Swagger UI at `http://localhost:8080/docs/`.

| # | Test | Expected |
|---:|---|---|
| 1 | GET `/api/v1/health` | 200 with `{"status":"ok"}` |
| 2 | GET `/api/v1/products` | 200 product list |
| 3 | GET `/api/v1/products/5` | 200 Pencil |
| 4 | GET `/api/v1/products/999` | 404 Problem Details |
| 5 | GET `/api/v1/products/abc` | 400 Problem Details |
| 6 | POST valid product | 201 with created product |
| 7 | POST missing field | 400 Problem Details |
| 8 | PUT `/api/v1/products/5` valid | 200 updated product |
| 9 | PUT `/api/v1/products/999` | 404 Problem Details |
| 10 | DELETE `/api/v1/products/5` | 200 success |
| 11 | DELETE `/api/v1/products/999` | 404 Problem Details |
| 12 | GET `/api/v1/stock/movements` | 200 movement list |
| 13 | POST stock IN | 201; stock increases |
| 14 | POST stock OUT within stock | 201; stock decreases |
| 15 | POST stock OUT above stock | 400 Problem Details |
| 16 | POST stock with unknown product | 404 Problem Details |
| 17 | GET `/api/v1/integration/inventory` | 200 integration payload |

Reset `server/data/store.json` from the repository copy if a manual test deletes or changes a seed record.
