<?php

namespace AKlump\TestFixture\Tests\FixturesDuplicate;

use AKlump\TestFixture\Fixture;
use AKlump\TestFixture\FixtureInterface;

#[Fixture(id: 'fixture_a', weight: 20, discoverable: true)]
class DuplicateFixture implements FixtureInterface {
  public function setUp(array $options): void {
  }
}
