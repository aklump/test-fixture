#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use AKlump\TestFixture\FixtureRunner;
use AKlump\TestFixture\Helper\GetFixtures;

$rebuild = in_array('--rebuild', $argv);

try {
  $ordered_fixtures = (new GetFixtures())($vendor_dir = __DIR__ . '/vendor', [], $rebuild);
}
catch (Exception $e) {
  echo "Error ordering fixtures: " . $e->getMessage() . "\n";
  exit(1);
}

$options = ['env' => 'test'];
$runner = new FixtureRunner($ordered_fixtures, $options);

try {
  $runner->run();
}
catch (Exception $e) {
  exit(1);
}
