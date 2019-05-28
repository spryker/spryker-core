<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Price\PriceModeCache;

interface PriceModeCacheManagerInterface
{
    /**
     * @return bool
     */
    public function hasPriceModeCache(): bool;

    /**
     * @return string
     */
    public function getPriceModeCache(): string;

    /**
     * @param string $priceMode
     *
     * @return void
     */
    public function cachePriceMode(string $priceMode): void;

    /**
     * @return void
     */
    public function invalidatePriceModeCache(): void;
}
