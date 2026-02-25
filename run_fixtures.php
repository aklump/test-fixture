#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use AKlump\TestFixture\FixtureDiscovery;
use AKlump\TestFixture\FixtureCache;
use AKlump\TestFixture\FixtureOrderer;
use AKlump\TestFixture\FixtureRunner;

$vendor_dir = __DIR__ . '/vendor';
$cache_file = __DIR__ . '/.test_fixture.cache.json';

$discovery = new FixtureDiscovery($vendor_dir);
$cache = new FixtureCache($cache_file, $vendor_dir);

$rebuild = in_array('--rebuild', $argv);

if ($rebuild) {
  $fixtures = $cache->rebuild($discovery);
}
else {
  $fixtures = $cache->get();
  if ($fixtures === NULL) {
    echo "Cache miss or invalidated, discovering fixtures...\n";
    $fixtures = $cache->rebuild($discovery);
  }
  else {
    echo "Using cached fixtures.\n";
  }
}

$orderer = new FixtureOrderer();
try {
  $ordered_fixtures = $orderer->order($fixtures);
}
catch (Exception $e) {
  echo "Error ordering fixtures: " . $e->getMessage() . "\n";
  exit(1);
}

$global_options = ['env' => 'test'];
$runner = new FixtureRunner($ordered_fixtures, $global_options);

try {
  $runner->run();
}
catch (Exception $e) {
  exit(1);
}
