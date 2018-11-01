<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence;

use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;
use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipToCompanyBusinessUnitTableMap;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationship;
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
            ->endUse();

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
    public function findExistingPriceProductConcreteMerchantRelationshipStorageEntitiesByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array
    {
        return $this->getFactory()
            ->createPriceProductConcreteMerchantRelationshipStorageQuery()
            ->filterByFkCompanyBusinessUnit_In($companyBusinessUnitIds)
            ->find()
            ->getData();
    }

    /**
     * @param string[] $priceKeys
     *
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage[]
     */
    public function findExistingPriceProductAbstractMerchantRelationshipStorageEntitiesByPriceKeys(array $priceKeys): array
    {
        return $this->getFactory()
            ->createPriceProductAbstractMerchantRelationshipStorageQuery()
            ->filterByPriceKey_In($priceKeys)
            ->find()
            ->getData();
    }

    /**
     * @param string[] $priceKeys
     *
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage[]
     */
    public function findExistingPriceProductConcreteMerchantRelationshipStorageEntitiesByPriceKeys(array $priceKeys): array
    {
        return $this->getFactory()
            ->createPriceProductConcreteMerchantRelationshipStorageQuery()
            ->filterByPriceKey_In($priceKeys)
            ->find()
            ->getData();
    }

    /**
     * @param int[] $companyBusinessUnitIds
     *
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage[]
     */
    public function findExistingPriceProductAbstractMerchantRelationshipStorageEntitiesByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array
    {
        return $this->getFactory()
            ->createPriceProductAbstractMerchantRelationshipStorageQuery()
            ->filterByFkCompanyBusinessUnit_In($companyBusinessUnitIds)
            ->find()
            ->getData();
    }

    /**
     * @param int[] $priceProductMerchantRelationshipIds
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function findMerchantRelationshipProductConcretePricesStorageByIds(array $priceProductMerchantRelationshipIds): array
    {
        $priceProductStoreQuery = $this->getFactory()
            ->getPropelPriceProductMerchantRelationshipQuery()
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT, PriceProductMerchantRelationshipStorageTransfer::ID_COMPANY_BUSINESS_UNIT)
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_MERCHANT_RELATIONSHIP, PriceProductMerchantRelationshipStorageTransfer::ID_MERCHANT_RELATIONSHIP)
            ->usePriceProductStoreQuery()
                ->joinWithStore()
                ->joinWithCurrency()
                ->usePriceProductQuery()
                    ->joinWithProduct()
                    ->joinWithPriceType()
                ->endUse()
            ->endUse()
            ->useMerchantRelationshipQuery()
                ->innerJoinSpyMerchantRelationshipToCompanyBusinessUnit()
            ->endUse()
            ->filterByIdPriceProductMerchantRelationship_In($priceProductMerchantRelationshipIds)
            ->filterByFkProduct(null, Criteria::ISNOTNULL);

        $priceProductStoreEntites = $this->mapPriceProductMerchantRelationshipToPriceProductStore(
            $priceProductStoreQuery->find()->getData()
        );

        return $this->getFactory()
            ->createCompanyBusinessUnitPriceProductMapper()
            ->mapProductConcretePrices($priceProductStoreEntites);
    }

    /**
     * @param \Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationship[] $priceProductMerchantRelationships
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore[]
     */
    protected function mapPriceProductMerchantRelationshipToPriceProductStore(array $priceProductMerchantRelationships): array
    {
        return array_map(function (SpyPriceProductMerchantRelationship $priceProductMerchantRelationship) {
            $priceProductStore = $priceProductMerchantRelationship->getPriceProductStore();

            $priceProductStore->setVirtualColumn(
                PriceProductMerchantRelationshipStorageTransfer::ID_COMPANY_BUSINESS_UNIT,
                $priceProductMerchantRelationship->getVirtualColumn(PriceProductMerchantRelationshipStorageTransfer::ID_COMPANY_BUSINESS_UNIT)
            );
            $priceProductStore->setVirtualColumn(
                PriceProductMerchantRelationshipStorageTransfer::ID_MERCHANT_RELATIONSHIP,
                $priceProductMerchantRelationship->getVirtualColumn(PriceProductMerchantRelationshipStorageTransfer::ID_MERCHANT_RELATIONSHIP)
            );

            return $priceProductStore;
        }, $priceProductMerchantRelationships);
    }

    /**
     * @param int[] $priceProductMerchantRelationshipIds
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function findMerchantRelationshipProductAbstractPricesStorageByIds(array $priceProductMerchantRelationshipIds): array
    {
        $priceProductStoreQuery = $this->getFactory()
            ->getPropelPriceProductMerchantRelationshipQuery()
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT, PriceProductMerchantRelationshipStorageTransfer::ID_COMPANY_BUSINESS_UNIT)
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_MERCHANT_RELATIONSHIP, PriceProductMerchantRelationshipStorageTransfer::ID_MERCHANT_RELATIONSHIP)
            ->usePriceProductStoreQuery()
                ->joinWithStore()
                ->joinWithCurrency()
                ->usePriceProductQuery()
                    ->joinWithSpyProductAbstract()
                    ->joinWithPriceType()
                ->endUse()
            ->endUse()
            ->useMerchantRelationshipQuery()
                ->innerJoinSpyMerchantRelationshipToCompanyBusinessUnit()
            ->endUse()
            ->filterByIdPriceProductMerchantRelationship_In($priceProductMerchantRelationshipIds)
            ->filterByFkProductAbstract(null, Criteria::ISNOTNULL);

        $priceProductStoreEntites = $this->mapPriceProductMerchantRelationshipToPriceProductStore(
            $priceProductStoreQuery->find()->getData()
        );

        return $this->getFactory()
            ->createCompanyBusinessUnitPriceProductMapper()
            ->mapProductAbstractPrices($priceProductStoreEntites);
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
