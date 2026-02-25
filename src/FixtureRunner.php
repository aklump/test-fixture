<?php

namespace AKlump\TestFixture;

class FixtureRunner {

  public function __construct(
    private array $fixtures,
    private array $globalOptions,
  ) {}

  public function run(): void {
    foreach ($this->fixtures as $fixtureRecord) {
      $class = $fixtureRecord['class'];
      $id = $fixtureRecord['id'];

      echo sprintf('Executing fixture "%s" (%s)... ', $id, $class);

      try {
        /** @var TestFixtureInterface $fixture */
        $fixture = new $class();
        $fixture->setUp($this->globalOptions);
        echo "Done.\n";
      } catch (\Exception $e) {
        echo "Failed!\n";
        echo "Error: " . $e->getMessage() . "\n";
        throw $e;
      }
    }
  }
}
