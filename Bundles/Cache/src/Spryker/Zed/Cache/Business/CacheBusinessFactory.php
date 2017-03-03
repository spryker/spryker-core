<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cache\Business;

use Spryker\Zed\Cache\Business\Model\AutoloaderCacheDelete;
use Spryker\Zed\Cache\Business\Model\CacheClearer;
use Spryker\Zed\Cache\Business\Model\CacheDelete;
use Spryker\Zed\Cache\CacheDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Cache\CacheConfig getConfig()
 */
class CacheBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @deprecated Please see createCacheClearer() for a replacement
     *
     * @return \Spryker\Zed\Cache\Business\Model\CacheDelete
     */
    public function createCacheDelete()
    {
        return new CacheDelete($this->getConfig());
    }

    /**
     * @deprecated Please see createCacheClearer() for a replacement
     *
     * @return \Spryker\Zed\Cache\Business\Model\AutoloaderCacheDelete
     */
    public function createAutoloaderCacheDelete()
    {
        return new AutoloaderCacheDelete($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Cache\Business\Model\CacheClearerInterface
     */
    public function createCacheClearer()
    {
        return new CacheClearer(
            $this->getConfig(),
            $this->getFileSystem(),
            $this->getFinder()
        );
    }

    /**
     * @return \Symfony\Component\Filesystem\Filesystem
     */
    protected function getFileSystem()
    {
        return $this->getProvidedDependency(CacheDependencyProvider::SYMFONY_FILE_SYSTEM);
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function getFinder()
    {
        return $this->getProvidedDependency(CacheDependencyProvider::SYMFONY_FINDER);
    }

}
