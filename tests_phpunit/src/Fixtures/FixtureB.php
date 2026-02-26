<?php

namespace AKlump\TestFixture\Tests\Fixtures;

use AKlump\TestFixture\AbstractFixture;
use AKlump\TestFixture\Fixture;

#[Fixture(id: 'fixture_b', weight: 5, after: ['fixture_a'], discoverable: true)]
class FixtureB extends AbstractFixture {
  public static bool $called = false;
  public function setUp(array $options): void {
    self::$called = true;
  }
}
