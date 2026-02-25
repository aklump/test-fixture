<?php

namespace AKlump\TestFixture\Tests\Fixtures;

use AKlump\TestFixture\Fixture;
use AKlump\TestFixture\FixtureInterface;

#[Fixture(id: 'fixture_b', weight: 5, after: ['fixture_a'], discoverable: false)]
class FixtureB implements FixtureInterface {
  public static bool $called = false;
  public function setUp(array $options): void {
    self::$called = true;
  }
}
