<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PriceProduct;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class PriceProductConfig extends AbstractSharedConfig
{
    /**
     * Price mode for price type when its applicable to gross and net price modes.
     */
    protected const PRICE_MODE_BOTH = 'BOTH';

    /**
     * @return string
     */
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /**
     * @return string
     */
    public function getPriceTypeDefaultName(): string
    {
        return static::PRICE_TYPE_DEFAULT;
    }

    /**
     * @return string
     */
    public function getPriceModeIdentifierForBothType(): string
    {
        return static::PRICE_MODE_BOTH;
    }
}
