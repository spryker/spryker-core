<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductOfferGuiPageConfig extends AbstractBundleConfig
{
    public const PRODUCT_TABLE_IS_ACTIVE_FILTER_NAME = 'isActive';
    public const PRODUCT_TABLE_HAS_OFFERS_FILTER_NAME = 'hasOffers';

    /**
     * Specification:
     * - Returns the whitelisted product table filter names.
     *
     * @api
     *
     * @return string[]
     */
    public function getAllowedFilterNames(): array
    {
        return [
            static::PRODUCT_TABLE_IS_ACTIVE_FILTER_NAME,
            static::PRODUCT_TABLE_HAS_OFFERS_FILTER_NAME,
        ];
    }
}
