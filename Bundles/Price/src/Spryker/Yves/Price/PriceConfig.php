<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Price;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Price\PriceConfig as SharedPriceConfig;
use Spryker\Shared\Price\PriceConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class PriceConfig extends AbstractBundleConfig
{
    /**
     * @return string[]
     */
    public function getPriceModes()
    {
        return [
            SharedPriceConfig::PRICE_MODE_NET => SharedPriceConfig::PRICE_MODE_NET,
            SharedPriceConfig::PRICE_MODE_GROSS => SharedPriceConfig::PRICE_MODE_GROSS,
        ];
    }

    /**
     * @return string
     */
    public function getDefaultPriceMode()
    {
        return Config::get(PriceConstants::DEFAULT_PRICE_MODE, SharedPriceConfig::PRICE_MODE_GROSS);
    }

    /**
     * @return string
     */
    public function getNetPriceModeIdentifier()
    {
        return SharedPriceConfig::PRICE_MODE_NET;
    }

    /**
     * @return string
     */
    public function getGrossPriceModeIdentifier()
    {
        return SharedPriceConfig::PRICE_MODE_GROSS;
    }
}
