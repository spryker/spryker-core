<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Price\PriceModeCache;

interface PriceModeCacheInterface
{
    /**
     * @return bool
     */
    public function isCached(): bool;

    /**
     * @return string
     */
    public function get(): string;

    /**
     * @param string $priceMode
     *
     * @return void
     */
    public function cache(string $priceMode): void;

    /**
     * @return void
     */
    public function invalidate(): void;
}
