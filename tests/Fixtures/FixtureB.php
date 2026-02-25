<?php

namespace AKlump\TestFixture\Tests\Fixtures;

use AKlump\TestFixture\Fixture;
use AKlump\TestFixture\TestFixtureInterface;

#[Fixture(id: 'fixture_b', weight: 5, after: ['fixture_a'])]
class FixtureB implements TestFixtureInterface {
  public static bool $called = false;
  public function setUp(array $options): void {
    self::$called = true;
  }
}
