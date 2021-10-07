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
     * @phpstan-param array<mixed> $businessUnitProducts
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
     * @phpstan-param array<mixed> $businessUnitProducts
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
     *  - Executes `PriceProductMerchantRelationshipStorageFilterPluginInterface` plugin stack.
     *
     * @api
     *
     * @param array<int> $companyBusinessUnitIds
     *
     * @return void
     */
    public function publishAbstractPriceProductByBusinessUnits(array $companyBusinessUnitIds): void;

    /**
     * Specification:
     *  - Publish merchant relationship prices for product concretes.
     *  - Uses the given company business unit IDs.
     *  - Refreshes the prices data for business units for all product concretes and merchant relationships.
     *  - Executes `PriceProductMerchantRelationshipStorageFilterPluginInterface` plugin stack.
     *
     * @api
     *
     * @param array<int> $companyBusinessUnitIds
     *
     * @return void
     */
    public function publishConcretePriceProductByBusinessUnits(array $companyBusinessUnitIds): void;

    /**
     * Specification:
     *  - Publish merchant relationship prices for product abstracts.
     *  - Uses the given IDs of the `spy_price_product_merchant_relationship` table.
     *  - Merges created or updated prices to the existing ones.
     *  - Executes `PriceProductMerchantRelationshipStorageFilterPluginInterface` plugin stack.
     *
     * @api
     *
     * @param array<int> $priceProductMerchantRelationshipIds
     *
     * @return void
     */
    public function publishAbstractPriceProductMerchantRelationship(array $priceProductMerchantRelationshipIds): void;

    /**
     * Specification:
     *  - Publish merchant relationship prices for product abstracts.
     *  - Uses the given abstract product IDs.
     *  - Refreshes the prices data for product abstracts for all business units and merchant relationships.
     *  - Executes `PriceProductMerchantRelationshipStorageFilterPluginInterface` plugin stack.
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function publishAbstractPriceProductByProductAbstractIds(array $productAbstractIds): void;

    /**
     * Specification:
     *  - Publish merchant relationship prices for product concretes.
     *  - Uses the given IDs of the `spy_price_product_merchant_relationship` table.
     *  - Merges created or updated prices to the existing ones.
     *  - Executes `PriceProductMerchantRelationshipStorageFilterPluginInterface` plugin stack.
     *
     * @api
     *
     * @param array<int> $priceProductMerchantRelationshipIds
     *
     * @return void
     */
    public function publishConcretePriceProductMerchantRelationship(array $priceProductMerchantRelationshipIds): void;

    /**
     * Specification:
     *  - Publish merchant relationship prices for product concretes.
     *  - Uses the given concrete product IDs.
     *  - Refreshes the prices data for product concretes for all business units and merchant relationships.
     *  - Executes `PriceProductMerchantRelationshipStorageFilterPluginInterface` plugin stack.
     *
     * @api
     *
     * @param array<int> $productIds
     *
     * @return void
     */
    public function publishConcretePriceProductByProductIds(array $productIds): void;

    /**
     * Specification:
     *  - Publishes merchant relationship prices for product concretes and abstracts when merchant active changing.
     *  - Refreshes the prices data for products for business units, merchant relationships and merchants from eventEntityTransfers.
     *  - Deletes the product prices from storage for business units, merchant relationships if merchant is deactivated.
     *  - Executes `PriceProductMerchantRelationshipStorageFilterPluginInterface` plugin stack.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantEvents(array $eventEntityTransfers): void;
}
