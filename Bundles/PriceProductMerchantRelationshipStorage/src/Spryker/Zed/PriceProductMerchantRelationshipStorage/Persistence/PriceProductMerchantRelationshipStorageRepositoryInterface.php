<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence;

use Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationship;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStoragePersistenceFactory getFactory()
 */
interface PriceProductMerchantRelationshipStorageRepositoryInterface
{
    /**
     * @param array $priceProductStoreIds
     *
     * @return array
     */
    public function findPriceProductStoreListByIdsForConcrete(array $priceProductStoreIds): array;

    /**
     * @param array $priceProductStoreIds
     *
     * @return array
     */
    public function findPriceProductStoreListByIdsForAbstract(array $priceProductStoreIds): array;

    /**
     * Returns array in format:
     * [
     *    [sku, id_product_abstract, id_store, id_merchant_relationship_id, id_company_business_unit],
     *    ...,
     * ]
     *
     * @param array $businessUnitIds
     *
     * @return array
     */
    public function getProductAbstractPriceDataByCompanyBusinessUnitIds(array $businessUnitIds): array;

    /**
     * Returns array in format:
     * [
     *    [sku, id_product, id_store, id_merchant_relationship_id, id_company_business_unit],
     *    ...,
     * ]
     *
     * @param array $businessUnitIds
     *
     * @return array
     */
    public function getProductConcretePriceDataByCompanyBusinessUnitIds(array $businessUnitIds): array;

    /**
     * @param int $idCompanyBusinessUnit
     * @param array $productConcreteIds
     *
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage[]
     */
    public function findExistingPriceProductConcreteMerchantRelationshipStorageEntities(int $idCompanyBusinessUnit, array $productConcreteIds): array;

    /**
     * @param int $idCompanyBusinessUnit
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage[]
     */
    public function findExistingPriceProductAbstractMerchantRelationshipStorageEntities(int $idCompanyBusinessUnit, array $productAbstractIds): array;

    /**
     * @param int $idMerchantRelationship
     *
     * @return array
     */
    public function findCompanyBusinessUnitIdsByMerchantRelationship(int $idMerchantRelationship): array;

    /**
     * @param string $idPriceProductMerchantRelationship
     *
     * @return \Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationship|null
     */
    public function findPriceProductMerchantRelationship(string $idPriceProductMerchantRelationship): ?SpyPriceProductMerchantRelationship;

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
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function findPriceProductConcreteMerchantRelationshipStorageEntitiesByOffsetAndLimit(int $offset, int $limit): array;
}
