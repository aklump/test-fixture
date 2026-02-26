<?php

namespace AKlump\TestFixture\Tests;

use AKlump\TestFixture\FixtureDiscovery;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\TestFixture\FixtureDiscovery
 */
class FixtureDiscoveryTest extends TestCase {

  public function testDiscoverIgnoresPerDiscoverable() {
    $discovery = new FixtureDiscovery(__DIR__ . '/../../vendor');
    // We must restrict discovery to a namespace that doesn't have duplicates
    // because DuplicateFixture is now in the autoload path.
    $fixtures = $discovery->discover(['AKlump\TestFixture\Tests\Fixtures\\']);

    $this->assertArrayHasKey('fixture_a', $fixtures);
    $this->assertArrayHasKey('fixture_b', $fixtures);
  }

  public function testDiscoverWithAllowList() {
    $discovery = new FixtureDiscovery(__DIR__ . '/../../vendor');

    // Should include when namespace matches
    $fixtures = $discovery->discover(['AKlump\TestFixture\Tests\Fixtures\\']);
    $this->assertArrayHasKey('fixture_a', $fixtures);
    $this->assertArrayHasKey('fixture_b', $fixtures);

    // Should exclude when namespace doesn't match
    $fixtures = $discovery->discover(['NonExistent\Namespace\\']);
    $this->assertArrayNotHasKey('fixture_a', $fixtures);
    $this->assertArrayNotHasKey('fixture_b', $fixtures);

    // Multiple namespaces
    $fixtures = $discovery->discover(['NonExistent\Namespace\\', 'AKlump\TestFixture\Tests\Fixtures\\']);
    $this->assertArrayHasKey('fixture_a', $fixtures);
    $this->assertArrayHasKey('fixture_b', $fixtures);
  }

  public function testDuplicateFixtureIdThrowsException() {
    $this->expectException(\RuntimeException::class);
    $this->expectExceptionMessage('Duplicate fixture id "fixture_a" found');
    $discovery = new FixtureDiscovery(__DIR__ . '/../../vendor');
    // We include both namespaces to trigger the duplicate ID error
    $discovery->discover([
      'AKlump\TestFixture\Tests\Fixtures',
      'AKlump\TestFixture\Tests\FixturesDuplicate',
    ]);
  }
}
