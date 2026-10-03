> v1.0.3 ~ "Building a connection no longer connects to MySQL"

---
## Highlights

- **Fix: resolving a connection no longer opens a database connection.** `MysqlConnection` registered its Doctrine geometry type mappings in the constructor through `getDoctrineSchemaManager()`, which resolved the PDO handle. Anything that merely resolved `DB::connection()` (`DB::listen()`, schema checks in service providers, `artisan package:discover` during `composer install`) connected to MySQL and failed when no database was reachable. The mappings are now registered on first Doctrine use.
- **Release automation.** Release-branch tagging and GitHub Release publishing now match the other Fleetbase modules: merging a `release/v*` branch into `main` tags the release, and the tag publishes the GitHub Release from these notes.

---
## Upgrading
No changes required. Code that relied on the connection being established when the connection object was constructed should call `getPdo()` explicitly.

---
## Need help?
- [GitHub Discussions](https://github.com/fleetbase/fleetbase/discussions)
- [Discord](https://discord.gg/HnTqQ6zAVn)
---
