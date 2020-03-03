<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver;

use Exception;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\ClassResolver\Cache\ProviderInterface;
use Spryker\Shared\Kernel\KernelConstants;

class ResolverCacheManager implements ResolverCacheFactoryInterface
{
    /**
     * @var bool|null
     */
    protected $useCache;

    /**
     * @var \Spryker\Shared\Kernel\ClassResolver\Cache\ProviderInterface|null
     */
    protected $cacheProvider;

    /**
     * @return bool
     */
    public function useCache()
    {
        if ($this->useCache === null) {
            $this->useCache = Config::get(KernelConstants::AUTO_LOADER_UNRESOLVABLE_CACHE_ENABLED, false);
        }

        return $this->useCache;
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    protected function assertConfig()
    {
        if (!Config::hasValue(KernelConstants::AUTO_LOADER_UNRESOLVABLE_CACHE_PROVIDER)) {
            throw new Exception(
                'Undefined UnresolvableCacheProvider. Make sure class exists and it\'s set in AUTO_LOADER_UNRESOLVABLE_CACHE_PROVIDER'
            );
        }
    }

    /**
     * @param \Spryker\Shared\Kernel\ClassResolver\Cache\ProviderInterface $cacheProvider
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function assertProviderInterface($cacheProvider)
    {
        if (!($cacheProvider instanceof ProviderInterface)) {
            throw new Exception(sprintf(
                'Class "%s" defined in AUTO_LOADER_UNRESOLVABLE_CACHE_PROVIDER must implement %s',
                get_class($cacheProvider),
                ProviderInterface::class
            ));
        }
    }

    /**
     * @return \Spryker\Shared\Kernel\ClassResolver\Cache\ProviderInterface
     */
    public function createClassResolverCacheProvider()
    {
        if ($this->cacheProvider === null) {
            $this->assertConfig();

            $className = Config::get(KernelConstants::AUTO_LOADER_UNRESOLVABLE_CACHE_PROVIDER);

            $cacheProvider = new $className();

            $this->assertProviderInterface($cacheProvider);

            $this->cacheProvider = $cacheProvider;
        }

        return $this->cacheProvider;
    }
}
