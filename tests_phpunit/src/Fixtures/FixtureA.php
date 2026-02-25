<?php

namespace AKlump\TestFixture\Tests\Fixtures;

use AKlump\TestFixture\Fixture;
use AKlump\TestFixture\FixtureInterface;

#[Fixture(id: 'fixture_a', weight: 10, discoverable: false)]
class FixtureA implements FixtureInterface {
  public static bool $called = false;
  public function setUp(array $options): void {
    self::$called = true;
  }
}
