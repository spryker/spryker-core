<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Price\PriceModeSwitcher;

interface PriceModeSwitcherInterface
{
    /**
     * @param string $priceMode
     *
     * @throws \Spryker\Client\Price\Exception\UnknownPriceModeException
     *
     * @return void
     */
    public function switchPriceMode(string $priceMode): void;
}
