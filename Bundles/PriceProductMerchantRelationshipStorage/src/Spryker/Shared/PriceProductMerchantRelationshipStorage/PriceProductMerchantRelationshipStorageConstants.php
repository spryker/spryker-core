<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PriceProductMerchantRelationshipStorage;

class PriceProductMerchantRelationshipStorageConstants
{
    /**
     * Specification:
     * - Resource name, it's used for key generating.
     *
     * @api
     */
    public const PRICE_PRODUCT_ABSTRACT_MERCHANT_RELATIONSHIP_RESOURCE_NAME = 'price_product_abstract_merchant_relationship';

    /**
     * Specification:
     * - Resource name, it's used for key generating.
     *
     * @api
     */
    public const PRICE_PRODUCT_CONCRETE_MERCHANT_RELATIONSHIP_RESOURCE_NAME = 'price_product_concrete_merchant_relationship';

    /**
     * @uses \Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap::COL_FK_STORE
     */
    public const COL_PRICE_PRODUCT_STORE_FK_STORE = 'spy_price_product_store.fk_store';

    /**
     * @uses \Orm\Zed\PriceProductMerchantRelationship\Persistence\Map\SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP
     */
    public const COL_FK_MERCHANT_RELATIONSHIP = 'spy_price_product_merchant_relationship.fk_merchant_relationship';
}
