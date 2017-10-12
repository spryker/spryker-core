<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\Cache;

use Spryker\Shared\Kernel\ClassResolver\ResolverCache;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     * @var \Spryker\Shared\Kernel\ClassResolver\ResolverCacheInterface
     */
    protected $cache;

    /**
     * @return \Spryker\Shared\Kernel\ClassResolver\Cache\StorageInterface
     */
    abstract protected function buildStorage();

    /**
     * @return \Spryker\Shared\Kernel\ClassResolver\ResolverCacheInterface
     */
    public function getCache()
    {
        if ($this->cache === null) {
            $this->cache = new ResolverCache(
                $this->buildStorage()
            );
        }

        return $this->cache;
    }
}
