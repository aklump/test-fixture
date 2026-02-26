<?php

namespace AKlump\TestFixture\Tests\Fixtures;

use AKlump\TestFixture\AbstractFixture;
use AKlump\TestFixture\Fixture;

#[Fixture(id: 'fixture_a', weight: 10, discoverable: true)]
class FixtureA extends AbstractFixture {
  public static bool $called = false;
  public function setUp(array $options): void {
    self::$called = true;
  }
}
