<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\PriceCartConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\Price\PriceConfig;

class PriceCartConnectorConfig extends AbstractBundleConfig
{

    /**
     * Return the string which represents the gross price type
     *
     * @return string
     */
    public function getGrossPriceType()
    {
        return PriceConfig::DEFAULT_PRICE_TYPE;
    }

}
