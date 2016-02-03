<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Price;

use Spryker\Shared\Config;
use Spryker\Shared\Price\PriceConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class PriceConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getPriceTypeDefaultName()
    {
        return Config::get(PriceConstants::DEFAULT_PRICE_TYPE);
    }

}
