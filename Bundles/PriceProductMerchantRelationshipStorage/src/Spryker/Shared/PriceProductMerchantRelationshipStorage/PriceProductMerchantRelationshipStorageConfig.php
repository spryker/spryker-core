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
     *
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
     *
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
     * Specification:
     * - This event will be used for spy_price_product_merchant_relationship publishing.
     *
     * @api
     *
     * @var string
     */
    public const PRICE_PRODUCT_ABSTRACT_MERCHANT_RELATIONSHIP_PUBLISH = 'PriceProductMerchantRelationship.price_product_abstract_merchant_relationship.publish';

    /**
     * Specification:
     * - This event will be used for spy_price_product_merchant_relationship un-publishing.
     *
     * @api
     *
     * @var string
     */
    public const PRICE_PRODUCT_ABSTRACT_MERCHANT_RELATIONSHIP_UNPUBLISH = 'PriceProductMerchantRelationship.price_product_abstract_merchant_relationship.unpublish';

    /**
     * Specification:
     * - This event will be used for spy_price_product_merchant_relationship publishing.
     *
     * @api
     *
     * @var string
     */
    public const PRICE_PRODUCT_CONCRETE_MERCHANT_RELATIONSHIP_PUBLISH = 'PriceProductMerchantRelationship.price_product_concrete_merchant_relationship.publish';

    /**
     * Specification:
     * - This event will be used for spy_price_product_merchant_relationship un-publishing.
     *
     * @api
     *
     * @var string
     */
    public const PRICE_PRODUCT_CONCRETE_MERCHANT_RELATIONSHIP_UNPUBLISH = 'PriceProductMerchantRelationship.price_product_concrete_merchant_relationship.unpublish';

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
