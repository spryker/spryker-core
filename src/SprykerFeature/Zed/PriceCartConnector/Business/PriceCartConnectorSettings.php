<?php

namespace SprykerFeature\Zed\PriceCartConnector\Business;

use SprykerFeature\Zed\Price\Business\PriceSettings;

class PriceCartConnectorSettings
{
    /**
     * Return the string which represents the gross price type
     *
     * @return string
     */
    public function getGrossPriceType()
    {
        return PriceSettings::DEFAULT_PRICE_TYPE;
    }
}
 