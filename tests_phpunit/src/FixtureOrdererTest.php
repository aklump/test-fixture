<?php

namespace AKlump\TestFixture\Tests;

use AKlump\TestFixture\Exception\FixtureException;
use AKlump\TestFixture\FixtureOrderer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\TestFixture\FixtureOrderer
 */
class FixtureOrdererTest extends TestCase {

  public function testOrder() {
    $orderer = new FixtureOrderer();
    $fixtures = [
      'fixture_a' => [
        'id' => 'fixture_a',
        'weight' => 10,
        'after' => [],
        'before' => [],
      ],
      'fixture_b' => [
        'id' => 'fixture_b',
        'weight' => 5,
        'after' => ['fixture_a'],
        'before' => [],
      ],
      'fixture_c' => [
        'id' => 'fixture_c',
        'weight' => 0,
        'after' => [],
        'before' => ['fixture_a'],
      ],
    ];

    $ordered = $orderer->order($fixtures);
    $ids = array_column($ordered, 'id');

    // Expectations:
    // c must be before a.
    // a must be before b.
    // So: c, a, b.
    $this->assertEquals(['fixture_c', 'fixture_a', 'fixture_b'], $ids);
  }

  public function testWeightTieBreak() {
    $orderer = new FixtureOrderer();
    $fixtures = [
      'b' => ['id' => 'b', 'weight' => 0, 'after' => [], 'before' => []],
      'a' => ['id' => 'a', 'weight' => 0, 'after' => [], 'before' => []],
      'c' => ['id' => 'c', 'weight' => -1, 'after' => [], 'before' => []],
    ];

    $ordered = $orderer->order($fixtures);
    $ids = array_column($ordered, 'id');

    // weight -1 (c), then weight 0 (a, b) sorted lexicographically: c, a, b
    $this->assertEquals(['c', 'a', 'b'], $ids);
  }

  public function testCircularDependency() {
    $this->expectException(FixtureException::class);
    $this->expectExceptionMessage('Circular dependency detected');

    $orderer = new FixtureOrderer();
    $fixtures = [
      'a' => ['id' => 'a', 'weight' => 0, 'after' => ['b'], 'before' => []],
      'b' => ['id' => 'b', 'weight' => 0, 'after' => ['a'], 'before' => []],
    ];

    $orderer->order($fixtures);
  }

  public function testMissingDependencyAfterThrowsException() {
    $this->expectException(FixtureException::class);
    $this->expectExceptionMessage('depends on missing fixture "missing"');
    $orderer = new FixtureOrderer();
    $orderer->order(['a' => ['id' => 'a', 'after' => ['missing'], 'before' => []]]);
  }

  public function testMissingDependencyBeforeThrowsException() {
    $this->expectException(FixtureException::class);
    $this->expectExceptionMessage('must run before missing fixture "missing"');
    $orderer = new FixtureOrderer();
    $orderer->order(['a' => ['id' => 'a', 'after' => [], 'before' => ['missing']]]);
  }
}
