<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage\Cache\Key;

interface CacheKeyStrategyProviderInterface
{
    /**
     * @return \Spryker\Client\Storage\Cache\Key\CacheKeyStrategyInterface
     */
    public function provideCacheKeyGenerationStrategy(): CacheKeyStrategyInterface;
}
