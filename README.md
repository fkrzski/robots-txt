# PHP Robots.txt

A modern, fluent PHP package for managing robots.txt rules with type safety and great developer experience.

## Requirements

- PHP 8.2 or higher
- Code coverage driver

## Installation

You can install the package via composer:

```bash
composer require fkrzski/robots-txt
```

## Testing and Development

The project includes several command groups for testing and code quality:

### Run all tests and checks

```bash
composer ci
```

This command runs:
- Type coverage tests
- Test coverage analysis
- Mutation tests
- Static analysis (PHPStan & Psalm)
- Code style checks
- Rector checks

### Run all tests

```bash
composer test:all
```

This command runs:
- Type coverage tests (`test:types`)
- Test coverage analysis (`test:coverage`)
- Mutation tests (`test:mutation`)

### Run code analysis

```bash
composer analyse
```

This command runs:
- PHPStan static analysis
- Psalm static analysis

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.