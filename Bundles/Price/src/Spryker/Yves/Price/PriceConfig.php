<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Price;

use Spryker\Shared\Price\PriceConfig as SharedPriceConfig;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class PriceConfig extends AbstractBundleConfig
{
    /**
     * @return \Spryker\Shared\Price\PriceConfig
     */
    public function createSharedConfig()
    {
        return new SharedPriceConfig();
    }
}
