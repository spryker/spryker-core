<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct;

use Spryker\Shared\Config\Config;
use Spryker\Shared\PriceProduct\PriceProductConfig as SharedPriceProductConfig;
use Spryker\Shared\PriceProduct\PriceProductConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class PriceProductConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getPriceTypeDefaultName()
    {
        return Config::get(PriceProductConstants::DEFAULT_PRICE_TYPE);
    }

    /**
     * @return string
     */
    public function getPriceModeIdentifierForBothType()
    {
        return SharedPriceProductConfig::PRICE_MODE_BOTH;
    }
}
