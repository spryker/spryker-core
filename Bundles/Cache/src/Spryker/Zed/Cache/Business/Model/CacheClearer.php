<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cache\Business\Model;

use Spryker\Zed\Cache\CacheConfig;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;

class CacheClearer implements CacheClearerInterface
{
    /**
     * @var \Spryker\Zed\Cache\CacheConfig
     */
    protected $config;

    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $fileSystem;

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * @param \Spryker\Zed\Cache\CacheConfig $config
     * @param \Symfony\Component\Filesystem\Filesystem $fileSystem
     * @param \Symfony\Component\Finder\Finder $finder
     */
    public function __construct(CacheConfig $config, Filesystem $fileSystem, Finder $finder)
    {
        $this->config = $config;
        $this->fileSystem = $fileSystem;
        $this->finder = $finder;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Cache\Business\Model\CacheClearer::clearCodeBucketCache()} instead.
     *
     * @return array<string>
     */
    public function clearCache()
    {
        return $this->clear(
            $this->config->getCachePath(),
            $this->config->getAllowedStores(),
        );
    }

    /**
     * @return string
     */
    public function clearCodeBucketCache(): string
    {
        $directory = $this->config->getCodeBucketCachePath();

        return $this->clearDirectoriesByPattern($directory);
    }

    /**
     * @return string
     */
    public function clearDefaultCodeBucketCache(): string
    {
        $directory = $this->config->getDefaultCodeBucketCachePath();

        return $this->clearDirectoriesByPattern($directory);
    }

    /**
     * @return array<string>
     */
    public function clearProjectSpecificCache(): array
    {
        $projectSpecificCache = $this->config->getProjectSpecificCache();

        $emptyProjectDirectories = [];
        foreach ($projectSpecificCache as $projectSpecificCacheDirectory) {
            $emptyProjectDirectories[] = $this->clearDirectoriesByPattern($projectSpecificCacheDirectory);
        }

        return $emptyProjectDirectories;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<string>
     */
    public function clearAutoLoaderCache()
    {
        return $this->clear(
            $this->config->getAutoloaderCachePath(),
            $this->config->getAllowedStores(),
        );
    }

    /**
     * @param string $directoryPattern
     * @param array<string> $stores
     *
     * @return array<string>
     */
    protected function clear($directoryPattern, array $stores)
    {
        $emptiedDirectories = [];

        foreach ($stores as $store) {
            $directory = $this->getDirectoryPathFromPattern($directoryPattern, $store);

            if (!$this->fileSystem->exists($directory)) {
                continue;
            }

            $this->clearDirectory($directory);
            $emptiedDirectories[] = $directory;
        }

        return $emptiedDirectories;
    }

    /**
     * @param string $directoryPattern
     * @param string $store
     *
     * @return string
     */
    protected function getDirectoryPathFromPattern($directoryPattern, $store)
    {
        return str_replace($this->config->getStorePatternMarker(), $store, $directoryPattern);
    }

    /**
     * @param string $directory
     *
     * @return void
     */
    protected function clearDirectory($directory)
    {
        $this->fileSystem->remove($this->findFiles($directory));
    }

    /**
     * @param string $directory
     *
     * @return \Symfony\Component\Finder\Finder
     */
    protected function findFiles($directory)
    {
        $finder = clone $this->finder;
        $finder
            ->in($directory)
            ->depth(0)
            ->ignoreDotFiles(false);

        return $finder;
    }

    /**
     * @param string $directoryPattern
     *
     * @return string
     */
    protected function clearDirectoriesByPattern(string $directoryPattern): string
    {
        try {
            $finder = clone $this->finder;
            $iterator = $finder->directories()->depth(0)->in(dirname($directoryPattern))->name(basename($directoryPattern));
        } catch (DirectoryNotFoundException $e) {
            return '';
        }

        $directories = [];
        foreach ($iterator as $splInfo) {
            $directory = $splInfo->getPath();
            $this->clearDirectory($directory);
            $directories[] = $directory;
        }

        return implode(PHP_EOL, $directories);
    }
}
