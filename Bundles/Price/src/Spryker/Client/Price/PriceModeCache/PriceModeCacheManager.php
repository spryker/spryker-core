<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Price\PriceModeCache;

class PriceModeCacheManager implements PriceModeCacheManagerInterface
{
    /**
     * @var string|null
     */
    protected static $priceModeCache;

    /**
     * @return bool
     */
    public function hasPriceModeCache(): bool
    {
        return (bool)static::$priceModeCache;
    }

    /**
     * @return string
     */
    public function getPriceModeCache(): string
    {
        return static::$priceModeCache;
    }

    /**
     * @param string $priceMode
     *
     * @return void
     */
    public function cachePriceMode(string $priceMode): void
    {
        static::$priceModeCache = $priceMode;
    }

    /**
     * @return void
     */
    public function invalidatePriceModeCache(): void
    {
        static::$priceModeCache = null;
    }
}
