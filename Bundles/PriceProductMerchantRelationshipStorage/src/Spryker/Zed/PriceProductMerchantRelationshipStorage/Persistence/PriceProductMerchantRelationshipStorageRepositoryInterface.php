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
     * @param array $businessUnitProducts
     *
     * @return array
     */
    public function findPriceProductStoresByCompanyBusinessUnitAbstractProducts(array $businessUnitProducts): array;

    /**
     * @param array $businessUnitProducts
     *
     * @return array
     */
    public function findPriceProductStoresByCompanyBusinessUnitConcreteProducts(array $businessUnitProducts): array;

    /**
     * @param array $concreteProducts
     *
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage[]
     */
    public function findExistingPriceProductConcreteMerchantRelationshipStorageEntities(array $concreteProducts): array;

    /**
     * @param array $concreteProducts
     *
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage[]
     */
    public function findExistingPriceProductAbstractMerchantRelationshipStorageEntities(array $concreteProducts): array;

    /**
     * @param int $idMerchantRelationship
     *
     * @return array
     */
    public function findCompanyBusinessUnitIdsByMerchantRelationship(int $idMerchantRelationship): array;

    /**
     * @param int $idPriceProductMerchantRelationship
     *
     * @return \Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationship
     */
    public function findPriceProductMerchantRelationship(int $idPriceProductMerchantRelationship): SpyPriceProductMerchantRelationship;
}
