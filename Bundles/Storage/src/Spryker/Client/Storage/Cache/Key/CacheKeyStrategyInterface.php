<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage\Cache\Key;

use Symfony\Component\HttpFoundation\Request;

interface CacheKeyStrategyInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     *
     * @return string
     */
    public function generateCacheKey(?Request $request = null): string;

    /**
     * @return string
     */
    public function getStrategyName(): string;
}
