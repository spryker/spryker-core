<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Storage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class StorageConfig extends AbstractBundleConfig
{
    public const CACHE_KEY_GENERATION_STRATEGY_REQUEST = 'CACHE_KEY_GENERATION_STRATEGY_REQUEST';
    public const CACHE_KEY_GENERATION_STRATEGY_EMPTY = 'CACHE_KEY_GENERATION_STRATEGY_EMPTY';

    /**
     * @return string
     */
    public function getRequestCacheKeyGenerationStrategy(): string
    {
        return static::CACHE_KEY_GENERATION_STRATEGY_REQUEST;
    }

    /**
     * @return string
     */
    public function getEmptyCacheKeyGenerationStrategy(): string
    {
        return static::CACHE_KEY_GENERATION_STRATEGY_EMPTY;
    }
}
