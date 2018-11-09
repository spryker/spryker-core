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
    public function findProductAbstractPriceDataByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array;

    /**
     * @param int[] $companyBusinessUnitIds
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function findProductConcretePriceDataByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array;

    /**
     * @param int $companyBusinessUnitId
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function findProductConcretePriceDataByCompanyBusinessUnitIdAndProductIds(int $companyBusinessUnitId, array $productIds): array;

    /**
     * @param int[] $companyBusinessUnitIds
     *
     * @return string[]
     */
    public function findExistingPriceProductConcreteMerchantRelationshipPriceKeysByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array;

    /**
     * @param string[] $priceKeys
     *
     * @return string[]
     */
    public function findExistingPriceKeysOfPriceProductAbstractMerchantRelationshipStorage(array $priceKeys): array;

    /**
     * @param string[] $priceKeys
     *
     * @return string[]
     */
    public function findExistingPriceKeysOfPriceProductConcreteMerchantRelationship(array $priceKeys): array;

    /**
     * @param int[] $companyBusinessUnitIds
     *
     * @return string[]
     */
    public function findExistingPriceProductAbstractMerchantRelationshipPriceKeysByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array;

    /**
     * @param int[] $priceProductMerchantRelationshipIds
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function findMerchantRelationshipProductConcretePricesByIds(array $priceProductMerchantRelationshipIds): array;

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipPriceKeyTransfer[] $priceKeys
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function findMerchantRelationshipProductConcretePricesStorageByPriceKeys(array $priceKeys): array;

    /**
     * @param int[] $priceProductMerchantRelationshipIds
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function findMerchantRelationshipProductAbstractPricesByIds(array $priceProductMerchantRelationshipIds): array;

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipPriceKeyTransfer[] $priceKeys
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function findMerchantRelationshipProductAbstractPricesStorageByPriceKeys(array $priceKeys): array;

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
     * @param int[] $merchantRelationshipIds
     *
     * @return int[]
     */
    public function findCompanyBusinessUnitIdsByMerchantRelationshipIds(array $merchantRelationshipIds): array;

    /**
     * @param int[] $priceProductStoreIds
     *
     * @return string[]
     */
    public function findStoreNamesByPriceProductStoreIds(array $priceProductStoreIds): array;
}
