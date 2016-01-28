<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\PriceCartConnector;

use Spryker\Shared\PriceCartConnector\PriceCartConnectorConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class PriceCartConnectorConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getGrossPriceType()
    {
        return $this->getConfig()->get(PriceCartConnectorConstants::DEFAULT_PRICE_TYPE, 'DEFAULT');
    }

}
