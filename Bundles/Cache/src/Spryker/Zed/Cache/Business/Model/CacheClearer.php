<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cache\Business\Model;

use Spryker\Zed\Cache\CacheConfig;
use Symfony\Component\Filesystem\Filesystem;
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
     * @return string[]
     */
    public function clearCache()
    {
        return $this->clear(
            $this->config->getCachePath(),
            $this->config->getAllowedStores()
        );
    }

    /**
     * @return string[]
     */
    public function clearAutoLoaderCache()
    {
        return $this->clear(
            $this->config->getAutoloaderCachePath(),
            $this->config->getAllowedStores()
        );
    }

    /**
     * @param string $directoryPattern
     * @param string[] $stores
     *
     * @return string[]
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
            ->depth(0);

        return $finder;
    }
}
