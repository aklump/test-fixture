# Test Fixture Framework

![main](images/hero.jpg)

When you need to do some setup (create users, content, etc) before running tests, call on this project.

It provices a structure for fixture management that is lightweight and test framework-agnostic.

Each fixture class (`implements \AKlump\TestFixture\FixtureInterface`) should be responsible for a single test fixture, such as creating a user.

Refer to `run_fixtures.php` as an example for how to run your fixtures once they are created.

## Key Features

- **Attribute-based Metadata**: No docblock parsing; uses `#[Fixture]` for configuration.
- **Composer Discovery**: Scans `vendor/` and project directories using Composer's autoload metadata.
- **Deterministic Ordering**: Supports weights and `after`/`before` dependencies with topological sorting.
- **Cached Discovery**: Fast execution via JSON indexing.
- **Global Options**: Pass a shared options array to every fixture.

## Core Components

### 1. `FixtureInterface`

Every fixture must implement this interface:

```php
namespace AKlump\TestFixture;

interface FixtureInterface {
  public function setUp(array $options): void;
}
```

### 2. `#[Fixture]` Attribute

Used to define fixture metadata:

- `id` (string, required): Unique identifier.
- `weight` (int, default 0): Lower weights run earlier.
- `after` (array, optional): IDs of fixtures that must run before this one.
- `before` (array, optional): IDs of fixtures that must run after this one.
- `tags` (array, optional): Metadata for future filtering.
- `discoverable` (bool, default true): Set to `false` to hide from discovery (e.g., test-only fixtures).

**Example:**

```php
use AKlump\TestFixture\FixtureInterface;
use AKlump\TestFixture\Fixture;

#[Fixture(id: 'user_roles', weight: -10, after: ['base_schema'])]
class UserRolesFixture implements FixtureInterface {
  public function setUp(array $options): void {
    // Implementation
  }
}
```

## Discovery and Execution

### Discovery

`FixtureDiscovery` uses `vendor/composer/autoload_psr4.php` and `vendor/composer/autoload_classmap.php` to find classes implementing `FixtureInterface` with the `#[Fixture]` attribute.

### Ordering

`FixtureOrderer` performs a topological sort based on `after`/`before` rules, tie-breaking with `weight` and lexicographical `id`.

### Runner

To run all your fixtures create a script that does something like this:

```php
$fixtures = (new GetFixtures())(__DIR__ . '/vendor/autoload.php');
$options = ['env' => 'test'];
(new FixtureRunner($fixtures, $options))->run();
```

## Cache Management

`FixtureCache` stores discovered metadata. It invalidates automatically if Composer's autoload files change. Use the `$rebuild_cache` parameter on `\AKlump\TestFixture\Helper\GetFixtures` to force discovery.
