<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Cache\CacheLoader;

use Spryker\Shared\Twig\Cache\CacheLoaderInterface;

class FilesystemCacheLoader implements CacheLoaderInterface
{
    /**
     * @var string
     */
    protected $cacheFilePath;

    /**
     * @param string $cacheFilePath
     */
    public function __construct($cacheFilePath)
    {
        $this->cacheFilePath = $cacheFilePath;
    }

    /**
     * @return array
     */
    public function load()
    {
        if (!file_exists($this->cacheFilePath)) {
            return [];
        }

        return include($this->cacheFilePath);
    }
}
