<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Shared\PriceProductMerchantRelationshipDataImport;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface PriceProductMerchantRelationshipDataImportConstants
{
    /**
     * @uses \Spryker\Zed\PriceProductMerchantRelationship\Dependency\PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE
     *
     * @var string
     */
    public const ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE = 'Entity.spy_price_product_store.create';

    /**
     * @uses \Spryker\Zed\PriceProductMerchantRelationship\Dependency\PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_UPDATE
     *
     * @var string
     */
    public const ENTITY_SPY_PRICE_PRODUCT_STORE_UPDATE = 'Entity.spy_price_product_store.update';

    /**
     * @var string
     */
    public const ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATIONSHIP_CREATE = 'Entity.spy_price_product_merchant_relationship.create';

    /**
     * @var string
     */
    public const ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATIONSHIP_UPDATE = 'Entity.spy_price_product_merchant_relationship.update';
}
