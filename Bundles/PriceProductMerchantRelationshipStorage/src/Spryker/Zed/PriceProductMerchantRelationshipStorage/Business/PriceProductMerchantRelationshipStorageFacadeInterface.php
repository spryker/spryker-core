<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\PriceProductMerchantRelationshipStorageBusinessFactory getFactory()
 */
interface PriceProductMerchantRelationshipStorageFacadeInterface
{
    /**
     * Specification:
     *  - Saves prices in spy_price_product_concrete_business_unit_storage
     *
     * @api
     *
     * @param array $priceProductStoreIds
     *
     * @return void
     */
    public function publishConcretePriceProduct(array $priceProductStoreIds): void;

    /**
     * Specification:
     *  - Removes prices from spy_price_product_concrete_business_unit_storage
     *
     * @api
     *
     * @param array $merchantRelationshipConcreteProducts
     *
     * @return void
     */
    public function unpublishConcretePriceProduct(array $merchantRelationshipConcreteProducts): void;

    /**
     * Specification:
     *  - Saves prices in spy_price_product_abstract_business_unit_storage
     *
     * @api
     *
     * @param array $businessUnitProducts
     *
     * @return void
     */
    public function publishAbstractPriceProduct(array $businessUnitProducts): void;

    /**
     * Specification:
     *  - Removes prices from spy_price_product_abstract_business_unit_storage
     *
     * @api
     *
     * @param array $merchantRelationshipAbstractProducts
     *
     * @return void
     */
    public function unpublishAbstractPriceProduct(array $merchantRelationshipAbstractProducts): void;
}
