<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Price;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\Price\PriceConfig as SharedPriceConfig;

class PriceConfig extends AbstractBundleConfig
{
    /**
     * @return \Spryker\Shared\Price\PriceConfig
     */
    public function createSharedPriceConfig()
    {
        return new SharedPriceConfig();
    }
}
