<?php

namespace AKlump\TestFixture\Tests\Fixtures;

use AKlump\TestFixture\Fixture;
use AKlump\TestFixture\TestFixtureInterface;

#[Fixture(id: 'fixture_a', weight: 10)]
class FixtureA implements TestFixtureInterface {
  public static bool $called = false;
  public function setUp(array $options): void {
    self::$called = true;
  }
}
