<?php

namespace AKlump\TestFixture\Tests;

use AKlump\TestFixture\FixtureDiscovery;
use PHPUnit\Framework\TestCase;

class FixtureDiscoveryTest extends TestCase {

  public function testDiscover() {
    $discovery = new FixtureDiscovery(__DIR__ . '/../vendor');
    $fixtures = $discovery->discover();

    $this->assertArrayHasKey('fixture_a', $fixtures);
    $this->assertArrayHasKey('fixture_b', $fixtures);

    $this->assertEquals('AKlump\TestFixture\Tests\Fixtures\FixtureA', $fixtures['fixture_a']['class']);
    $this->assertEquals(10, $fixtures['fixture_a']['weight']);

    $this->assertEquals('AKlump\TestFixture\Tests\Fixtures\FixtureB', $fixtures['fixture_b']['class']);
    $this->assertEquals(['fixture_a'], $fixtures['fixture_b']['after']);
  }
}
