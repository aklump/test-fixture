<?php

namespace AKlump\TestFixture;

use ReflectionClass;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use RuntimeException;

class FixtureDiscovery {

  private string $vendorDir;

  public function __construct(string $vendor_dir) {
    $this->vendorDir = $vendor_dir;
  }

  public function discover(array $namespace_allow_list = []): array {
    $classes = $this->getCandidateClasses();
    $fixtures = [];

    foreach ($classes as $class) {
      if (!empty($namespace_allow_list)) {
        $matched = false;
        foreach ($namespace_allow_list as $namespace) {
          if (str_starts_with($class, $namespace)) {
            $matched = true;
            break;
          }
        }
        if (!$matched) {
          continue;
        }
      }

      if (!class_exists($class)) {
        continue;
      }

      $reflection = new ReflectionClass($class);
      if (!$reflection->isInstantiable() || !$reflection->implementsInterface(FixtureInterface::class)) {
        continue;
      }

      $attributes = $reflection->getAttributes(Fixture::class);
      if (empty($attributes)) {
        continue;
      }

      /** @var Fixture $fixture_attribute */
      $fixture_attribute = $attributes[0]->newInstance();

      if (!$fixture_attribute->discoverable) {
        continue;
      }

      $id = trim($fixture_attribute->id);
      if ($id === '') {
        throw new RuntimeException(sprintf('Fixture id must be a non-empty string on class "%s".', $class));
      }

      if (isset($fixtures[$id])) {
        throw new RuntimeException(sprintf(
          'Duplicate fixture id "%s" found on class "%s" (already defined by "%s").',
          $id,
          $class,
          $fixtures[$id]['class']
        ));
      }

      $after = $this->normalizeStringList($fixture_attribute->after, 'after', $id, $class);
      $before = $this->normalizeStringList($fixture_attribute->before, 'before', $id, $class);
      $tags = $this->normalizeStringList($fixture_attribute->tags, 'tags', $id, $class, true);

      $fixtures[$id] = [
        'id' => $id,
        'class' => $class,
        'weight' => $fixture_attribute->weight,
        'after' => $after,
        'before' => $before,
        'tags' => $tags,
      ];
    }

    return $fixtures;
  }

  private function normalizeStringList(array $value, string $field, string $id, string $class, bool $dedupe = false): array {
    foreach ($value as $i => $item) {
      if (!is_string($item)) {
        throw new RuntimeException(sprintf(
          'Fixture "%s" (%s) has non-string value in "%s" at index %d.',
          $id,
          $class,
          $field,
          $i
        ));
      }
      $value[$i] = trim($item);
    }

    $value = array_values(array_filter($value, static fn(string $s) => $s !== ''));

    if ($dedupe) {
      $value = array_values(array_unique($value));
    }

    return $value;
  }

  private function getCandidateClasses(): array {
    $classes = [];

    // 1. From classmap
    $classmapFile = $this->vendorDir . '/composer/autoload_classmap.php';
    if (file_exists($classmapFile)) {
      $classmap = require $classmapFile;
      $classes = array_keys($classmap);
    }

    // 2. From PSR-4
    $psr4File = $this->vendorDir . '/composer/autoload_psr4.php';
    if (file_exists($psr4File)) {
      $psr4 = require $psr4File;
      foreach ($psr4 as $namespace => $dirs) {
        foreach ($dirs as $dir) {
          $classes = array_merge($classes, $this->scanDirectoryForClasses($dir, $namespace));
        }
      }
    }

    return array_unique($classes);
  }

  private function scanDirectoryForClasses(string $dir, string $namespace): array {
    if (!is_dir($dir)) {
      return [];
    }

    $classes = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    $php_files = new RegexIterator($iterator, '/\.php$/');

    foreach ($php_files as $file) {
      $relative_path = str_replace([$dir, '.php'], ['', ''], $file->getPathname());
      $class_name = $namespace . str_replace(DIRECTORY_SEPARATOR, '\\', ltrim($relative_path, DIRECTORY_SEPARATOR));
      $classes[] = $class_name;
    }

    return $classes;
  }
}
