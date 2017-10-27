<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PriceProduct;

use Spryker\Shared\Config\Config;

class PriceProductConfig
{
    /**
     * @return string
     */
    public function getPriceTypeDefaultName()
    {
        return Config::get(PriceProductConstants::DEFAULT_PRICE_TYPE);
    }
}
