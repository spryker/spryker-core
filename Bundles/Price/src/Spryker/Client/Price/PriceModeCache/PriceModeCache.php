<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Price\PriceModeCache;

class PriceModeCache implements PriceModeCacheInterface
{
    /**
     * @var string|null
     */
    protected static $priceModeCache;

    /**
     * @return bool
     */
    public function isCached(): bool
    {
        return (bool)static::$priceModeCache;
    }

    /**
     * @return string
     */
    public function get(): string
    {
        return static::$priceModeCache;
    }

    /**
     * @param string $priceMode
     *
     * @return void
     */
    public function cache(string $priceMode): void
    {
        static::$priceModeCache = $priceMode;
    }

    /**
     * @return void
     */
    public function invalidate(): void
    {
        static::$priceModeCache = null;
    }
}
