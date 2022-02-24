<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Price;

/**
 * @deprecated Use {@link \Spryker\Zed\Price\Business\PriceFacade}/{@link \Spryker\Client\Price\PriceClient} respectively instead.
 */
interface PriceMode
{
    public const PRICE_MODE_NET = PriceConfig::PRICE_MODE_NET;
    public const PRICE_MODE_GROSS = PriceConfig::PRICE_MODE_GROSS;
}
