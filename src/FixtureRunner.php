<?php

namespace AKlump\TestFixture;

class FixtureRunner {

  public function __construct(
    private array $fixtures,
    private array $globalOptions,
  ) {}

  public function run(bool $silent = FALSE): void {
    foreach ($this->fixtures as $fixtureRecord) {
      $class = $fixtureRecord['class'];
      $id = $fixtureRecord['id'];

      if (!$silent) {
        echo sprintf('Executing fixture "%s" (%s)... ', $id, $class);
      }

      try {
        /** @var FixtureInterface $fixture */
        $fixture = new $class();
        $fixture->setUp($this->globalOptions);
        if (!$silent) {
          echo "Done.\n";
        }
      } catch (\Exception $e) {
        if (!$silent) {
          echo "Failed!\n";
          echo "Error: " . $e->getMessage() . "\n";
        }
        throw $e;
      }
    }
  }
}
