<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PriceProductMerchantRelationshipDataImport;

interface PriceProductMerchantRelationshipDataImportConstants
{
    /**
     * @uses \Spryker\Zed\PriceProductMerchantRelationship\Dependency\PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE
     */
    public const ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE = 'Entity.spy_price_product_store.create';

    /**
     * @uses \Spryker\Zed\PriceProductMerchantRelationship\Dependency\PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_UPDATE
     */
    public const ENTITY_SPY_PRICE_PRODUCT_STORE_UPDATE = 'Entity.spy_price_product_store.update';

    /**
     * @uses \Spryker\Zed\PriceProductMerchantRelationship\Dependency\PriceProductMerchantRelationshipEvents::ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATIONSHIP_DELETE
     */
    public const ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATIONSHIP_DELETE = 'Entity.spy_price_product_merchant_relationship.delete';
}
