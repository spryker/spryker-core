<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector;

use Spryker\Shared\Price\PriceMode;
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

    /**
     * @return string
     */
    public function getPriceMode()
    {
        return PriceMode::PRICE_MODE_GROSS;
    }

}
