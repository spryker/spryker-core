<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\PriceCartConnector;

use Spryker\Shared\Price\PriceConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class PriceCartConnectorConfig extends AbstractBundleConfig
{

    /**
     * Return the string which represents the gross price type
     *
     * @return string
     */
    public function getGrossPriceType()
    {
        return PriceConstants::DEFAULT_PRICE_TYPE;
    }

}
