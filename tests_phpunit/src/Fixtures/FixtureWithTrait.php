<?php

namespace AKlump\TestFixture\Tests\Fixtures;

use AKlump\TestFixture\Fixture;
use AKlump\TestFixture\FixtureInterface;
use AKlump\TestFixture\FixtureMetadataTrait;

#[Fixture(id: 'fixture_with_trait', weight: 42, discoverable: false)]
class FixtureWithTrait implements FixtureInterface {

  use FixtureMetadataTrait;

  public static array $received = [];

  public function setUp(array $options): void {
    self::$received = $this->fixture;
  }
}
