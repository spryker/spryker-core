<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PriceProduct;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\AbstractSharedConfig;

class PriceProductConfig extends AbstractSharedConfig
{
    /**
     * Price mode for price type when its applicable to gross and net price modes.
     */
    const PRICE_MODE_BOTH = 'BOTH';

    /**
     * @return string
     */
    public function getPriceTypeDefaultName()
    {
        return Config::get(PriceProductConstants::DEFAULT_PRICE_TYPE);
    }

    /**
     * @return string
     */
    public function getPriceModeIdentifierForBothType()
    {
        return static::PRICE_MODE_BOTH;
    }
}
