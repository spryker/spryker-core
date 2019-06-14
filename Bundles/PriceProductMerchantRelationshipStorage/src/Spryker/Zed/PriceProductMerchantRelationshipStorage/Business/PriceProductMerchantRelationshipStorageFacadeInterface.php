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
     *  -
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param array $businessUnitProducts
     *
     * @return void
     */
    public function publishConcretePriceProductByBusinessUnitProducts(array $businessUnitProducts): void;

    /**
     * Specification:
     *  -
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param array $businessUnitProducts
     *
     * @return void
     */
    public function publishAbstractPriceProductByBusinessUnitProducts(array $businessUnitProducts): void;

    /**
     * Specification:
     *  - Publish merchant relationship prices for product abstracts.
     *  - Uses the given company business unit IDs.
     *  - Refreshes the prices data for business units for all product abstracts and merchant relationships.
     *
     * @api
     *
     * @param int[] $companyBusinessUnitIds
     *
     * @return void
     */
    public function publishAbstractPriceProductByBusinessUnits(array $companyBusinessUnitIds): void;

    /**
     * Specification:
     *  - Publish merchant relationship prices for product concretes.
     *  - Uses the given company business unit IDs.
     *  - Refreshes the prices data for business units for all product concretes and merchant relationships.
     *
     * @api
     *
     * @param int[] $companyBusinessUnitIds
     *
     * @return void
     */
    public function publishConcretePriceProductByBusinessUnits(array $companyBusinessUnitIds): void;

    /**
     * Specification:
     *  - Publish merchant relationship prices for product abstracts.
     *  - Uses the given IDs of the `spy_price_product_merchant_relationship` table.
     *  - Merges created or updated prices to the existing ones.
     *
     * @api
     *
     * @param int[] $priceProductMerchantRelationshipIds
     *
     * @return void
     */
    public function publishAbstractPriceProductMerchantRelationship(array $priceProductMerchantRelationshipIds): void;

    /**
     * Specification:
     *  - Publish merchant relationship prices for product abstracts.
     *  - Uses the given abstract product IDs.
     *  - Refreshes the prices data for product abstracts for all business units and merchant relationships.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publishAbstractPriceProductByProductAbstractIds(array $productAbstractIds): void;

    /**
     * Specification:
     *  - Publish merchant relationship prices for product concretes.
     *  - Uses the given IDs of the `spy_price_product_merchant_relationship` table.
     *  - Merges created or updated prices to the existing ones.
     *
     * @api
     *
     * @param int[] $priceProductMerchantRelationshipIds
     *
     * @return void
     */
    public function publishConcretePriceProductMerchantRelationship(array $priceProductMerchantRelationshipIds): void;

    /**
     * Specification:
     *  - Publish merchant relationship prices for product concretes.
     *  - Uses the given concrete product IDs.
     *  - Refreshes the prices data for product concretes for all business units and merchant relationships.
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishConcretePriceProductByProductIds(array $productIds): void;
}
