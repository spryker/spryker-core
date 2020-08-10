<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver;

use Exception;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\ClassResolver\Cache\Provider\File;
use Spryker\Shared\Kernel\ClassResolver\Cache\ProviderInterface;
use Spryker\Shared\Kernel\KernelConstants;

/**
 * @deprecated Use {@link \Spryker\Shared\Kernel\KernelConstants::RESOLVABLE_CLASS_NAMES_CACHE_ENABLED} instead.
 */
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
            $className = Config::get(KernelConstants::AUTO_LOADER_UNRESOLVABLE_CACHE_PROVIDER, File::class);

            $cacheProvider = new $className();

            $this->assertProviderInterface($cacheProvider);

            $this->cacheProvider = $cacheProvider;
        }

        return $this->cacheProvider;
    }
}
