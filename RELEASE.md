> v2.0.0 ~ "Laravel 12 and 13"

---
## Highlights

- **Requires Laravel 12 or 13, and PHP 8.2 or later.** Use the 1.x line on Laravel 10.
- **Fatal errors on Laravel 11+ fixed.** `Blueprint::geometry()` and `Blueprint::spatialIndex()` now match the framework's signatures, and the `Blueprint` and `MySqlGrammar` constructors take a connection.
- **`doctrine/dbal` removed**, along with `src/Doctrine` and the connection's Doctrine type mappings. Laravel 11 removed Doctrine support.

---
## Upgrading
Migrations don't need changing: `$table->geometry('col', 4326)` still reads an integer second argument as the SRID. `geometry('col', null, 4326)` also works, while a string subtype is accepted but ignored.

Code calling `getDoctrineSchemaManager()` or `getDoctrineConnection()` on this connection must move to the `Schema` builder's `getColumns()`, `getIndexes()` and `getTables()`.
