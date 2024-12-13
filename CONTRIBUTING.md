# Contributing

Thank you for considering contributing to PHP Robots.txt! This document outlines the process and guidelines for contributing to the project. Please also review our [Code of Conduct](CODE_OF_CONDUCT.md) to understand our community standards.

## Development Setup

1. Fork the repository
2. Clone your fork:
   ```bash
   git clone https://github.com/fkrzski/robots-txt.git
   ```
3. Install dependencies:
   ```bash
   composer install
   ```

## Development Workflow

1. Create a new branch:
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. Make your changes and ensure all tests pass:
   ```bash
   composer ci
   ```

3. Commit your changes following [Conventional Commits](https://www.conventionalcommits.org/):
   ```bash
   feat: add new feature
   fix: resolve specific issue
   docs: update documentation
   test: add or update tests
   ```

## Code Quality Standards

Before submitting your PR, please ensure:

1. All tests pass:
   - Unit tests via PHPUnit/Pest
   - Type coverage tests
   - Mutation tests

2. Code follows PSR-12 standards:
   - Run `composer cs:check` to check code style
   - Run `composer cs:fix` to automatically fix style issues

3. Static analysis shows no errors:
   - Run `composer analyse` to check with PHPStan and Psalm

4. Rector checks pass:
   - Run `composer rector:check` to verify code quality
   - Run `composer rector:fix` to automatically fix issues

## Pull Request Process

1. Update documentation to reflect any changes
2. Add or update tests to cover your changes
3. Ensure the test suite passes
4. Update the README.md with details of changes if needed
5. The PR will be merged once you have the sign-off of at least one maintainer

## Questions or Problems?

Feel free to open an issue in the repository for:
- Bug reports
- Feature requests
- Questions about the codebase

## License

By contributing to PHP Robots.txt, you agree that your contributions will be licensed under its MIT license.