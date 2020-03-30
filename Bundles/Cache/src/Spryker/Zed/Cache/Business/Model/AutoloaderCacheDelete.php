<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cache\Business\Model;

use Spryker\Zed\Cache\CacheConfig;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @deprecated Use Spryker\Zed\Cache\Business\Model\CacheClearer instead.
 */
class AutoloaderCacheDelete
{
    /**
     * @var \Spryker\Zed\Cache\CacheConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Cache\CacheConfig $config
     */
    public function __construct(CacheConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Deletes all cache files for all stores
     *
     * @return string
     */
    public function deleteAllFiles()
    {
        $rootDirectory = $this->config->getAutoloaderCachePath();
        $filesystem = new Filesystem();
        $filesystem->remove($rootDirectory);

        return $rootDirectory;
    }
}
