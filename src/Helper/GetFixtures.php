<?php

namespace AKlump\TestFixture\Helper;

use AKlump\TestFixture\FixtureCache;
use AKlump\TestFixture\FixtureDiscovery;
use AKlump\TestFixture\FixtureOrderer;

class GetFixtures {

  public function __invoke(string $vendor_dir = '', bool $rebuild_cache = FALSE): array {
    $cache_file = getenv('TEST_FIXTURE_CACHE_FILE') ?: '';

    if ($cache_file === '') {
      $vendor_dir_real = $vendor_dir !== '' ? (realpath($vendor_dir) ?: $vendor_dir) : '';
      $cache_key = $vendor_dir_real !== '' ? sha1($vendor_dir_real) : 'default';

      $cache_dir = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'test_fixture';
      if (!is_dir($cache_dir)) {
        @mkdir($cache_dir, 0777, TRUE);
      }

      $cache_file = $cache_dir . DIRECTORY_SEPARATOR . "fixtures.$cache_key.cache.json";
    }

    $discovery = new FixtureDiscovery($vendor_dir);
    $cache = new FixtureCache($cache_file, $vendor_dir);

    if ($rebuild_cache) {
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

    return $orderer->order($fixtures);
  }
}
