<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStoragePersistenceFactory getFactory()
 */
interface PriceProductMerchantRelationshipStorageRepositoryInterface
{
    /**
     * @param int[] $companyBusinessUnitIds
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function getProductAbstractPriceDataByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array;

    /**
     * @param int[] $companyBusinessUnitIds
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function getProductConcretePriceDataByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array;

    /**
     * @param int[] $companyBusinessUnitIds
     *
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage[]
     */
    public function findExistingPriceProductConcreteMerchantRelationshipStorageEntities(array $companyBusinessUnitIds): array;

    /**
     * @param int[] $companyBusinessUnitIds
     *
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage[]
     */
    public function findExistingPriceProductAbstractMerchantRelationshipStorageEntities(array $companyBusinessUnitIds): array;

    /**
     * @param array $priceProductMerchantRelationshipIds
     *
     * @return array
     */
    public function findCompanyBusinessUnitIdsByPriceProductMerchantRelationshipIdsForConcreteProducts(array $priceProductMerchantRelationshipIds): array;

    /**
     * @param array $priceProductMerchantRelationshipIds
     *
     * @return array
     */
    public function findCompanyBusinessUnitIdsByPriceProductMerchantRelationshipIdsForAbstractProducts(array $priceProductMerchantRelationshipIds): array;

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
}
