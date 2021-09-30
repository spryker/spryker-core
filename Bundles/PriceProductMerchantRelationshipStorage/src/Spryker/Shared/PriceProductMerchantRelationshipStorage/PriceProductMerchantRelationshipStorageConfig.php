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
     * @var string
     */
    protected const PRICE_DIMENSION_MERCHANT_RELATIONSHIP = 'PRICE_DIMENSION_MERCHANT_RELATIONSHIP';

    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_MODES
     *
     * @var array<string>
     */
    public const PRICE_MODES = [
        'NET_MODE',
        'GROSS_MODE',
    ];

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     * @var string
     */
    public const PRICE_GROSS_MODE = 'GROSS_MODE';

    /**
     * Specification:
     * - This events will be used for spy_merchant entity changes.
     *
     * @uses \Spryker\Zed\Merchant\Dependency\MerchantEvents::ENTITY_SPY_MERCHANT_UPDATE
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_MERCHANT_UPDATE = 'Entity.spy_merchant.update';

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
