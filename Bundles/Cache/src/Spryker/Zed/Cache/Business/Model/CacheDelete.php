<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cache\Business\Model;

use Spryker\Zed\Cache\CacheConfig;
use Symfony\Component\Filesystem\Filesystem;

class CacheDelete
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
     * @return array
     */
    public function deleteAllFiles()
    {
        $rootDirectory = $this->config->getCachePath();
        $stores = $this->config->getAllowedStores();
        $dirs = [];
        foreach ($stores as $store) {
            $dirs[] = str_replace('{STORE}', $store, $rootDirectory);
        }
        $filesystem = new Filesystem();
        $filesystem->remove($dirs);

        return $dirs;
    }

}
