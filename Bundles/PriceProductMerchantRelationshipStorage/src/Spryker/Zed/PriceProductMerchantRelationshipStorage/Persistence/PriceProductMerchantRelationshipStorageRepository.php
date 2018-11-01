<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence;

use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;
use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipToCompanyBusinessUnitTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStoragePersistenceFactory getFactory()
 */
class PriceProductMerchantRelationshipStorageRepository extends AbstractRepository implements PriceProductMerchantRelationshipStorageRepositoryInterface
{
    /**
     * @module Store
     * @module Currency
     * @module Product
     * @module PriceProduct
     * @module MerchantRelationship
     *
     * @param int[] $companyBusinessUnitIds
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function getProductAbstractPriceDataByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array
    {
        $priceProductStoreQuery = $this->getFactory()
            ->getPropelPriceProductStoreQuery()
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT, PriceProductMerchantRelationshipStorageTransfer::ID_COMPANY_BUSINESS_UNIT)
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_MERCHANT_RELATIONSHIP, PriceProductMerchantRelationshipStorageTransfer::ID_MERCHANT_RELATIONSHIP)
            ->joinWithCurrency()
            ->joinWithStore()
            ->usePriceProductQuery()
                ->joinWithSpyProductAbstract()
                ->joinWithPriceType()
            ->endUse()
            ->usePriceProductMerchantRelationshipQuery()
                ->filterByFkProductAbstract(null, Criteria::ISNOTNULL)
                ->useMerchantRelationshipQuery()
                    ->useSpyMerchantRelationshipToCompanyBusinessUnitQuery()
                        ->filterByFkCompanyBusinessUnit_In($companyBusinessUnitIds)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->groupBy(PriceProductMerchantRelationshipStorageTransfer::ID_COMPANY_BUSINESS_UNIT)
            ->groupBy(PriceProductMerchantRelationshipStorageTransfer::ID_MERCHANT_RELATIONSHIP);

        return $this->getFactory()
            ->createCompanyBusinessUnitPriceProductMapper()
            ->mapProductAbstractPrices(
                $priceProductStoreQuery->find()->getData()
            );
    }

    /**
     * @module Store
     * @module Currency
     * @module Product
     * @module PriceProduct
     * @module MerchantRelationship
     *
     * @param int[] $companyBusinessUnitIds
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function getProductConcretePriceDataByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array
    {
        $priceProductStoreQuery = $this->getFactory()
            ->getPropelPriceProductStoreQuery()
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT, PriceProductMerchantRelationshipStorageTransfer::ID_COMPANY_BUSINESS_UNIT)
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_MERCHANT_RELATIONSHIP, PriceProductMerchantRelationshipStorageTransfer::ID_MERCHANT_RELATIONSHIP)
            ->joinWithCurrency()
            ->joinWithStore()
            ->usePriceProductQuery()
                ->joinWithProduct()
                ->joinWithPriceType()
            ->endUse()
            ->usePriceProductMerchantRelationshipQuery()
                ->filterByFkProduct(null, Criteria::ISNOTNULL)
                ->useMerchantRelationshipQuery()
                    ->useSpyMerchantRelationshipToCompanyBusinessUnitQuery()
                        ->filterByFkCompanyBusinessUnit_In($companyBusinessUnitIds)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->groupBy(PriceProductMerchantRelationshipStorageTransfer::ID_COMPANY_BUSINESS_UNIT)
            ->groupBy(PriceProductMerchantRelationshipStorageTransfer::ID_MERCHANT_RELATIONSHIP);

        return $this->getFactory()
            ->createCompanyBusinessUnitPriceProductMapper()
            ->mapProductConcretePrices(
                $priceProductStoreQuery->find()->getData()
            );
    }

    /**
     * @param int[] $companyBusinessUnitIds
     *
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage[]
     */
    public function findExistingPriceProductConcreteMerchantRelationshipStorageEntities(array $companyBusinessUnitIds): array
    {
        return $this->getFactory()
            ->createPriceProductConcreteMerchantRelationshipStorageQuery()
            ->filterByFkCompanyBusinessUnit_In($companyBusinessUnitIds)
            ->find()
            ->getData();
    }

    /**
     * @param int[] $companyBusinessUnitIds
     *
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage[]
     */
    public function findExistingPriceProductAbstractMerchantRelationshipStorageEntities(array $companyBusinessUnitIds): array
    {
        return $this->getFactory()
            ->createPriceProductAbstractMerchantRelationshipStorageQuery()
            ->filterByFkCompanyBusinessUnit_In($companyBusinessUnitIds)
            ->find()
            ->getData();
    }

    /**
     * @param array $priceProductMerchantRelationshipIds
     *
     * @return array
     */
    public function findCompanyBusinessUnitIdsByPriceProductMerchantRelationshipIdsForConcreteProducts(array $priceProductMerchantRelationshipIds): array
    {
        return $this->getFactory()
            ->getPropelPriceProductMerchantRelationshipQuery()
            ->useMerchantRelationshipQuery()
                ->innerJoinWithSpyMerchantRelationshipToCompanyBusinessUnit()
            ->endUse()
            ->filterByIdPriceProductMerchantRelationship_In($priceProductMerchantRelationshipIds)
            ->filterByFkProduct(null, Criteria::ISNOTNULL)
            ->select([
                SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT,
            ])->groupBy(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT)
            ->find()
            ->toArray();
    }

    /**
     * @param array $priceProductMerchantRelationshipIds
     *
     * @return array
     */
    public function findCompanyBusinessUnitIdsByPriceProductMerchantRelationshipIdsForAbstractProducts(array $priceProductMerchantRelationshipIds): array
    {
        return $this->getFactory()
            ->getPropelPriceProductMerchantRelationshipQuery()
            ->useMerchantRelationshipQuery()
            ->innerJoinWithSpyMerchantRelationshipToCompanyBusinessUnit()
            ->endUse()
            ->filterByIdPriceProductMerchantRelationship_In($priceProductMerchantRelationshipIds)
            ->filterByFkProductAbstract(null, Criteria::ISNOTNULL)
            ->select([
                SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT,
            ])->groupBy(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT)
            ->find()
            ->toArray();
    }

    /**
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage[]
     */
    public function findAllPriceProductConcreteMerchantRelationshipStorageEntities(): array
    {
        return $this->getFactory()
            ->createPriceProductConcreteMerchantRelationshipStorageQuery()
            ->find()
            ->getArrayCopy();
    }

    /**
     * @param array $priceProductConcreteMerchantRelationshipStorageEntityIds
     *
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage[]
     */
    public function findPriceProductConcreteMerchantRelationshipStorageEntitiesByIds(array $priceProductConcreteMerchantRelationshipStorageEntityIds): array
    {
        return $this->getFactory()
            ->createPriceProductConcreteMerchantRelationshipStorageQuery()
            ->filterByIdPriceProductConcreteMerchantRelationshipStorage_In($priceProductConcreteMerchantRelationshipStorageEntityIds)
            ->find()
            ->getArrayCopy();
    }

    /**
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage[]
     */
    public function findAllPriceProductAbstractMerchantRelationshipStorageEntities(): array
    {
        return $this->getFactory()
            ->createPriceProductAbstractMerchantRelationshipStorageQuery()
            ->find()
            ->getArrayCopy();
    }

    /**
     * @param array $priceProductAbstractMerchantRelationshipStorageEntityIds
     *
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage[]
     */
    public function findPriceProductAbstractMerchantRelationshipStorageEntitiesByIds(array $priceProductAbstractMerchantRelationshipStorageEntityIds): array
    {
        return $this->getFactory()
            ->createPriceProductAbstractMerchantRelationshipStorageQuery()
            ->filterByIdPriceProductAbstractMerchantRelationshipStorage_In($priceProductAbstractMerchantRelationshipStorageEntityIds)
            ->find()
            ->getArrayCopy();
    }
}
