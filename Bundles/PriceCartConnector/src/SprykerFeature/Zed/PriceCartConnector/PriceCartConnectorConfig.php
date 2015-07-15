<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PriceCartConnector;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Zed\Price\PriceConfig;

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
