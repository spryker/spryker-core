<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Price;

/**
 * @deprecated use \Spryker\Client\Price\PriceClient|\Spryker\Zed\Price\Business\PriceFacade respectively instead
 */
interface PriceMode
{
    public const PRICE_MODE_NET = PriceConfig::PRICE_MODE_NET;
    public const PRICE_MODE_GROSS = PriceConfig::PRICE_MODE_GROSS;
}
