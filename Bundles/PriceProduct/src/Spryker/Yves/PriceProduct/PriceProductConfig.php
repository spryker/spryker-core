<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\PriceProduct;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use Spryker\Shared\PriceProduct\PriceProductConfig as SharedPriceProductConfig;

class PriceProductConfig extends AbstractBundleConfig
{
    /**
     * @return \Spryker\Shared\PriceProduct\PriceProductConfig
     */
    public function createSharedPriceConfig()
    {
        return new SharedPriceProductConfig();
    }
}
