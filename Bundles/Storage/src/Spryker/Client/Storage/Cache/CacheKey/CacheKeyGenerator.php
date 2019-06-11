<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage\Cache\CacheKey;

use Spryker\Client\Storage\Exception\InvalidCacheKeyGeneratorStrategyException;
use Symfony\Component\HttpFoundation\Request;

class CacheKeyGenerator implements CacheKeyGeneratorInterface
{
    /**
     * @var \Spryker\Client\Storage\Cache\CacheKey\CacheKeyGeneratorStrategyInterface[]
     */
    protected $cacheKeyGeneratorStrategies;

    /**
     * @param \Spryker\Client\Storage\Cache\CacheKey\CacheKeyGeneratorStrategyInterface[] $cacheKeyGeneratorStrategies
     */
    public function __construct(array $cacheKeyGeneratorStrategies)
    {
        $this->cacheKeyGeneratorStrategies = $cacheKeyGeneratorStrategies;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     *
     * @return string
     */
    public function generateCacheKey(?Request $request = null): string
    {
        return $this->resolveCacheKeyGeneratorStrategy()->generateCacheKey($request);
    }

    /**
     * @throws \Spryker\Client\Storage\Exception\InvalidCacheKeyGeneratorStrategyException
     *
     * @return \Spryker\Client\Storage\Cache\CacheKey\CacheKeyGeneratorStrategyInterface
     */
    protected function resolveCacheKeyGeneratorStrategy(): CacheKeyGeneratorStrategyInterface
    {
        foreach ($this->cacheKeyGeneratorStrategies as $cacheKeyGenerationStrategy) {
            if ($cacheKeyGenerationStrategy->isApplicable()) {
                return $cacheKeyGenerationStrategy;
            }
        }

        throw new InvalidCacheKeyGeneratorStrategyException('None of the applied CacheKeyStrategyInterface is accepted, please check your configuration for those.');
    }
}
