<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStoragePersistenceFactory getFactory()
 */
interface PriceProductMerchantRelationshipStorageRepositoryInterface
{
    /**
     * @param array<int> $companyBusinessUnitIds
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer>
     */
    public function findMerchantRelationshipProductAbstractPricesDataByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array;

    /**
     * @param array<int> $companyBusinessUnitIds
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer>
     */
    public function findMerchantRelationshipProductConcretePricesDataByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array;

    /**
     * @param array<int> $priceProductMerchantRelationshipIds
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer>
     */
    public function findMerchantRelationshipProductConcretePricesDataByIds(array $priceProductMerchantRelationshipIds): array;

    /**
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer>
     */
    public function findMerchantRelationshipProductConcretePricesDataByProductIds(array $productIds): array;

    /**
     * @param array<int> $priceProductMerchantRelationshipIds
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer>
     */
    public function findMerchantRelationshipProductAbstractPricesByIds(array $priceProductMerchantRelationshipIds): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer>
     */
    public function findMerchantRelationshipProductAbstractPricesDataByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param array<int> $companyBusinessUnitIds
     *
     * @return array<\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage>
     */
    public function findExistingPriceProductAbstractMerchantRelationshipEntitiesByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array;

    /**
     * @param array<string> $priceKeys
     *
     * @return array<\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage>
     */
    public function findExistingPriceProductAbstractMerchantRelationshipEntitiesByPriceKeys(array $priceKeys): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage>
     */
    public function findExistingPriceProductAbstractMerchantRelationshipEntitiesByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param array<int> $companyBusinessUnitIds
     *
     * @return array<\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage>
     */
    public function findExistingPriceProductConcreteMerchantRelationshipEntitiesByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array;

    /**
     * @param array<string> $priceKeys
     *
     * @return array<\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage>
     */
    public function findExistingPriceProductConcreteMerchantRelationshipEntitiesByPriceKeys(array $priceKeys): array;

    /**
     * @param array<int> $productIds
     *
     * @return array<\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage>
     */
    public function findExistingPriceProductConcreteMerchantRelationshipEntitiesByProductIds(array $productIds): array;

    /**
     * @return array
     */
    public function findAllPriceProductConcreteMerchantRelationshipStorageEntities(): array;

    /**
     * @param array $priceProductConcreteMerchantRelationshipStorageEntityIds
     *
     * @return array
     */
    public function findPriceProductConcreteMerchantRelationshipStorageEntitiesByIds(array $priceProductConcreteMerchantRelationshipStorageEntityIds): array;

    /**
     * @return array
     */
    public function findAllPriceProductAbstractMerchantRelationshipStorageEntities(): array;

    /**
     * @param array $priceProductAbstractMerchantRelationshipStorageEntityIds
     *
     * @return array
     */
    public function findPriceProductAbstractMerchantRelationshipStorageEntitiesByIds(array $priceProductAbstractMerchantRelationshipStorageEntityIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $priceProductConcreteMerchantRelationshipStorageIds
     *
     * @return array<\Generated\Shared\Transfer\SpyPriceProductConcreteMerchantRelationshipStorageEntityTransfer>
     */
    public function findFilteredPriceProductConcreteMerchantRelationshipStorageEntities(
        FilterTransfer $filterTransfer,
        array $priceProductConcreteMerchantRelationshipStorageIds = []
    ): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $priceProductAbstractMerchantRelationshipStorageIds
     *
     * @return array<\Generated\Shared\Transfer\SpyPriceProductAbstractMerchantRelationshipStorageEntityTransfer>
     */
    public function findFilteredPriceProductAbstractMerchantRelationshipStorageEntities(
        FilterTransfer $filterTransfer,
        array $priceProductAbstractMerchantRelationshipStorageIds = []
    ): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipTransfer>
     */
    public function getFilteredPriceProductConcreteMerchantRelationships(FilterTransfer $filterTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipTransfer>
     */
    public function getFilteredPriceProductAbstractMerchantRelationships(FilterTransfer $filterTransfer): array;
}
