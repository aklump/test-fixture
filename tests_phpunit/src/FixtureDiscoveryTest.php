<?php

namespace AKlump\TestFixture\Tests;

use AKlump\TestFixture\FixtureDiscovery;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\TestFixture\FixtureDiscovery
 */
class FixtureDiscoveryTest extends TestCase {

  public function testDiscoverIgnoresPerDiscoverable() {
    $discovery = new FixtureDiscovery(__DIR__ . '/../vendor');
    $fixtures = $discovery->discover();

    // After adding discoverable: false to FixtureA and FixtureB, they should not be discovered
    $this->assertArrayNotHasKey('fixture_a', $fixtures);
    $this->assertArrayNotHasKey('fixture_b', $fixtures);
  }
}
