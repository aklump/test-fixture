<?php

namespace AKlump\TestFixture\Tests;

use AKlump\TestFixture\FixtureRunner;
use AKlump\TestFixture\Tests\Fixtures\FixtureA;
use AKlump\TestFixture\Tests\Fixtures\FixtureB;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\TestFixture\FixtureRunner
 */
class FixtureRunnerTest extends TestCase {

  public function testRun() {
    FixtureA::$called = FALSE;
    FixtureB::$called = FALSE;

    $fixtures = [
      [
        'id' => 'fixture_a',
        'class' => FixtureA::class,
      ],
      [
        'id' => 'fixture_b',
        'class' => FixtureB::class,
      ],
    ];

    $runner = new FixtureRunner($fixtures, ['key' => 'value']);
    $runner->run(TRUE);

    $this->assertTrue(FixtureA::$called);
    $this->assertTrue(FixtureB::$called);
  }
}
