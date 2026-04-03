# AGENTS.md

## Scope
This repository is a Symfony bundle. Follow Symfony best practices and keep changes consistent with the bundle’s existing architecture.

## Engineering Expectations
- Prefer dependency injection over service container access.
- Keep public APIs stable and documented.
- Follow PSR-4 autoloading: `src/` for production code, `tests/` for test code.
- Keep configuration under `config/` and avoid hard-coding environment-specific values.
- Use Symfony contracts and components already listed in `composer.json`.

## Tests and Static Analysis
Run these from the repo root:

```sh
composer install
```

```sh
vendor/bin/phpunit -c phpunit.dist.xml
```

```sh
vendor/bin/phpstan analyse -c phpstan.dist.neon
```

## Notes
- PHPUnit uses `Sensiolabs\MinifyBundle\Tests\Fixtures\MinifyBundleTestKernel` via `phpunit.dist.xml`.
- PHPStan level is configured in `phpstan.dist.neon` and excludes `src/SensiolabsMinifyBundle.php`.
