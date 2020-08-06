<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PriceProductMerchantRelationshipStorage;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class PriceProductMerchantRelationshipStorageConfig extends AbstractSharedConfig
{
    /**
     * Price Dimension Merchant Relationship
     *
     * @uses \Spryker\Shared\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConfig::PRICE_DIMENSION_MERCHANT_RELATIONSHIP
     */
    protected const PRICE_DIMENSION_MERCHANT_RELATIONSHIP = 'PRICE_DIMENSION_MERCHANT_RELATIONSHIP';

    /**
     * @see \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_MODES
     */
    public const PRICE_MODES = [
        'NET_MODE',
        'GROSS_MODE',
    ];

    /**
     * @see \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     */
    public const PRICE_GROSS_MODE = 'GROSS_MODE';

    /**
     * @api
     *
     * @return string
     */
    public function getPriceDimensionMerchantRelationship(): string
    {
        return static::PRICE_DIMENSION_MERCHANT_RELATIONSHIP;
    }
}
