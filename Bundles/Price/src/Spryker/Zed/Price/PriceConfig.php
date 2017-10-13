<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price;

use Spryker\Shared\Config\Config;
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
