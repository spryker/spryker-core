<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Price\PriceModeSwitcher;

interface PriceModeSwitcherInterface
{
    /**
     * @param string $priceMode
     *
     * @throws \Spryker\Yves\Price\Exception\UnknownPriceModeException
     *
     * @return void
     */
    public function switchPriceMode($priceMode);
}
