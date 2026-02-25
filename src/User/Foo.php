<?php

namespace AKlump\TestFixture\User;

use AKlump\TestFixture\TestFixtureInterface;
use AKlump\TestFixture\Fixture;

#[Fixture(id: 'foo', weight: 10)]
class Foo implements TestFixtureInterface {

  public function setUp(array $options): void {
    echo "Executing Foo fixture with options: " . json_encode($options) . "\n";
  }
}
