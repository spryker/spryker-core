<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class PriceCartConnectorConfig extends AbstractBundleConfig
{
    protected const MIN_PRICE_RESTRICTION = 1;

    /**
     * Specification:
     * - Min price used for validation price of a product in the Spryker\Zed\PriceCartConnector\Business\Validator\PriceProductValidator.
     *
     * @api
     *
     * @return int
     */
    public function getMinPriceRestriction(): int
    {
        return static::MIN_PRICE_RESTRICTION;
    }
}
