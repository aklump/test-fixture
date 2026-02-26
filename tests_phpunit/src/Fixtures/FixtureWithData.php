<?php

namespace AKlump\TestFixture\Tests\Fixtures;

use AKlump\TestFixture\Fixture;
use AKlump\TestFixture\FixtureInterface;

#[Fixture(id: 'fixture_with_data', weight: 42, tags: ['tag1', 'tag2'])]
class FixtureWithData implements FixtureInterface {
  public array $fixture;
  public static array $received = [];
  public function setUp(array $options): void {
    if (isset($this->fixture)) {
      self::$received = $this->fixture;
    }
  }
}
