<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence;

use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;
use Generated\Shared\Transfer\PriceProductMerchantRelationshipValueTransfer;
use Orm\Zed\Currency\Persistence\Map\SpyCurrencyTableMap;
use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipToCompanyBusinessUnitTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\Map\SpyPriceProductMerchantRelationshipTableMap;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStoragePersistenceFactory getFactory()
 */
class PriceProductMerchantRelationshipStorageRepository extends AbstractRepository implements PriceProductMerchantRelationshipStorageRepositoryInterface
{
    /**
     * @module Store
     * @module Currency
     * @module PriceProduct
     * @module MerchantRelationship
     * @module PriceProductMerchantRelationship
     *
     * @param int[] $companyBusinessUnitIds
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function findProductAbstractPriceDataByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array
    {
        $priceProductMerchantRelationships = $this->getFactory()
            ->getPropelPriceProductStoreQuery()
                ->innerJoinStore()
                ->innerJoinCurrency()
            ->usePriceProductQuery()
                ->innerJoinPriceType()
            ->endUse()
            ->usePriceProductMerchantRelationshipQuery()
                ->filterByFkProductAbstract(null, Criteria::ISNOTNULL)
                ->useMerchantRelationshipQuery()
                    ->useSpyMerchantRelationshipToCompanyBusinessUnitQuery()
                        ->filterByFkCompanyBusinessUnit_In($companyBusinessUnitIds)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->withColumn(SpyStoreTableMap::COL_NAME, PriceProductMerchantRelationshipStorageTransfer::STORE_NAME)
            ->withColumn(SpyCurrencyTableMap::COL_CODE, PriceProductMerchantRelationshipValueTransfer::CURRENCY_CODE)
            ->withColumn(SpyPriceProductMerchantRelationshipTableMap::COL_FK_PRODUCT_ABSTRACT, PriceProductMerchantRelationshipStorageTransfer::ID_PRODUCT)
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT, PriceProductMerchantRelationshipStorageTransfer::ID_COMPANY_BUSINESS_UNIT)
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_MERCHANT_RELATIONSHIP, PriceProductMerchantRelationshipStorageTransfer::ID_MERCHANT_RELATIONSHIP)
            ->withColumn(SpyPriceTypeTableMap::COL_NAME, PriceProductMerchantRelationshipValueTransfer::PRICE_TYPE)
            ->withColumn(SpyPriceProductStoreTableMap::COL_PRICE_DATA, PriceProductMerchantRelationshipValueTransfer::PRICE_DATA)
            ->withColumn(SpyPriceProductStoreTableMap::COL_GROSS_PRICE, PriceProductMerchantRelationshipValueTransfer::GROSS_PRICE)
            ->withColumn(SpyPriceProductStoreTableMap::COL_NET_PRICE, PriceProductMerchantRelationshipValueTransfer::NET_PRICE)
            ->setFormatter(new PropelArraySetFormatter())
            ->find();

        return $this->getFactory()
            ->createCompanyBusinessUnitPriceProductMapper()
            ->mapPriceProductMerchantRelationshipArrayToTransfers(
                $priceProductMerchantRelationships
            );
    }

    /**
     * @module Store
     * @module Currency
     * @module PriceProduct
     * @module MerchantRelationship
     * @module PriceProductMerchantRelationship
     *
     * @param int[] $companyBusinessUnitIds
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function findProductConcretePriceDataByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array
    {
        $priceProductMerchantRelationships = $this->getFactory()
            ->getPropelPriceProductStoreQuery()
            ->innerJoinStore()
            ->innerJoinCurrency()
            ->usePriceProductQuery()
                ->innerJoinPriceType()
            ->endUse()
            ->usePriceProductMerchantRelationshipQuery()
                ->filterByFkProduct(null, Criteria::ISNOTNULL)
                ->useMerchantRelationshipQuery()
                    ->useSpyMerchantRelationshipToCompanyBusinessUnitQuery()
                        ->filterByFkCompanyBusinessUnit_In($companyBusinessUnitIds)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->withColumn(SpyStoreTableMap::COL_NAME, PriceProductMerchantRelationshipStorageTransfer::STORE_NAME)
            ->withColumn(SpyCurrencyTableMap::COL_CODE, PriceProductMerchantRelationshipValueTransfer::CURRENCY_CODE)
            ->withColumn(SpyPriceProductMerchantRelationshipTableMap::COL_FK_PRODUCT, PriceProductMerchantRelationshipStorageTransfer::ID_PRODUCT)
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT, PriceProductMerchantRelationshipStorageTransfer::ID_COMPANY_BUSINESS_UNIT)
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_MERCHANT_RELATIONSHIP, PriceProductMerchantRelationshipStorageTransfer::ID_MERCHANT_RELATIONSHIP)
            ->withColumn(SpyPriceTypeTableMap::COL_NAME, PriceProductMerchantRelationshipValueTransfer::PRICE_TYPE)
            ->withColumn(SpyPriceProductStoreTableMap::COL_PRICE_DATA, PriceProductMerchantRelationshipValueTransfer::PRICE_DATA)
            ->withColumn(SpyPriceProductStoreTableMap::COL_GROSS_PRICE, PriceProductMerchantRelationshipValueTransfer::GROSS_PRICE)
            ->withColumn(SpyPriceProductStoreTableMap::COL_NET_PRICE, PriceProductMerchantRelationshipValueTransfer::NET_PRICE)
            ->setFormatter(new PropelArraySetFormatter())
            ->find();

        return $this->getFactory()
            ->createCompanyBusinessUnitPriceProductMapper()
            ->mapPriceProductMerchantRelationshipArrayToTransfers(
                $priceProductMerchantRelationships
            );
    }

    /**
     * @param int[] $companyBusinessUnitIds
     *
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage[]
     */
    public function findExistingPriceProductAbstractMerchantRelationshipEntitiesByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array
    {
        return $this->getFactory()
            ->createPriceProductAbstractMerchantRelationshipStorageQuery()
            ->filterByFkCompanyBusinessUnit_In($companyBusinessUnitIds)
            ->find()
            ->getData();
    }

    /**
     * @param string[] $priceKeys
     *
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage[]
     */
    public function findExistingPriceProductAbstractMerchantRelationshipEntitiesByPriceKeys(array $priceKeys): array
    {
        return $this->getFactory()
            ->createPriceProductAbstractMerchantRelationshipStorageQuery()
            ->filterByPriceKey_In($priceKeys)
            ->find()
            ->getData();
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage[]
     */
    public function findExistingPriceProductAbstractMerchantRelationshipEntitiesByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->createPriceProductAbstractMerchantRelationshipStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find()
            ->getData();
    }

    /**
     * @param int[] $companyBusinessUnitIds
     *
     * @return string[]
     */
    public function findExistingPriceProductConcreteMerchantRelationshipEntitiesByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array
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
     * @return string[]
     */
    public function findExistingPriceProductConcreteMerchantRelationshipEntitiesByPriceKeys(array $priceKeys): array
    {
        return $this->getFactory()
            ->createPriceProductConcreteMerchantRelationshipStorageQuery()
            ->filterByPriceKey_In($priceKeys)
            ->find()
            ->getData();
    }

    /**
     * @param int[] $productIds
     *
     * @return string[]
     */
    public function findExistingPriceProductConcreteMerchantRelationshipEntitiesByProductIds(array $productIds): array
    {
        return $this->getFactory()
            ->createPriceProductConcreteMerchantRelationshipStorageQuery()
            ->filterByFkProduct_In($productIds)
            ->find()
            ->getData();
    }

    /**
     * @module Store
     * @module Currency
     * @module PriceProduct
     * @module MerchantRelationship
     * @module PriceProductMerchantRelationship
     *
     * @param int[] $priceProductMerchantRelationshipIds
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function findMerchantRelationshipProductConcretePricesByIds(array $priceProductMerchantRelationshipIds): array
    {
        $priceProductMerchantRelationships = $this->getFactory()
            ->getPropelPriceProductMerchantRelationshipQuery()
            ->usePriceProductStoreQuery()
                ->innerJoinStore()
                ->innerJoinCurrency()
                ->usePriceProductQuery()
                    ->innerJoinPriceType()
                ->endUse()
            ->endUse()
            ->useMerchantRelationshipQuery()
                ->innerJoinSpyMerchantRelationshipToCompanyBusinessUnit()
            ->endUse()
            ->filterByIdPriceProductMerchantRelationship_In($priceProductMerchantRelationshipIds)
            ->filterByFkProduct(null, Criteria::ISNOTNULL)
            ->withColumn(SpyStoreTableMap::COL_NAME, PriceProductMerchantRelationshipStorageTransfer::STORE_NAME)
            ->withColumn(SpyCurrencyTableMap::COL_CODE, PriceProductMerchantRelationshipValueTransfer::CURRENCY_CODE)
            ->withColumn(SpyPriceProductMerchantRelationshipTableMap::COL_FK_PRODUCT, PriceProductMerchantRelationshipStorageTransfer::ID_PRODUCT)
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT, PriceProductMerchantRelationshipStorageTransfer::ID_COMPANY_BUSINESS_UNIT)
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_MERCHANT_RELATIONSHIP, PriceProductMerchantRelationshipStorageTransfer::ID_MERCHANT_RELATIONSHIP)
            ->withColumn(SpyPriceTypeTableMap::COL_NAME, PriceProductMerchantRelationshipValueTransfer::PRICE_TYPE)
            ->withColumn(SpyPriceProductStoreTableMap::COL_PRICE_DATA, PriceProductMerchantRelationshipValueTransfer::PRICE_DATA)
            ->withColumn(SpyPriceProductStoreTableMap::COL_GROSS_PRICE, PriceProductMerchantRelationshipValueTransfer::GROSS_PRICE)
            ->withColumn(SpyPriceProductStoreTableMap::COL_NET_PRICE, PriceProductMerchantRelationshipValueTransfer::NET_PRICE)
            ->setFormatter(new PropelArraySetFormatter())
            ->find();

        return $this->getFactory()
            ->createCompanyBusinessUnitPriceProductMapper()
            ->mapPriceProductMerchantRelationshipArrayToTransfers(
                $priceProductMerchantRelationships
            );
    }

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function findMerchantRelationshipProductConcretePriceDataByProductIds(array $productIds): array
    {
        $priceProductMerchantRelationships = $this->getFactory()
            ->getPropelPriceProductMerchantRelationshipQuery()
            ->usePriceProductStoreQuery()
                ->innerJoinStore()
                ->innerJoinCurrency()
                ->usePriceProductQuery()
                    ->innerJoinPriceType()
                ->endUse()
            ->endUse()
            ->useMerchantRelationshipQuery()
                ->innerJoinSpyMerchantRelationshipToCompanyBusinessUnit()
            ->endUse()
            ->filterByFkProduct_In($productIds)
            ->withColumn(SpyStoreTableMap::COL_NAME, PriceProductMerchantRelationshipStorageTransfer::STORE_NAME)
            ->withColumn(SpyCurrencyTableMap::COL_CODE, PriceProductMerchantRelationshipValueTransfer::CURRENCY_CODE)
            ->withColumn(SpyPriceProductMerchantRelationshipTableMap::COL_FK_PRODUCT, PriceProductMerchantRelationshipStorageTransfer::ID_PRODUCT)
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT, PriceProductMerchantRelationshipStorageTransfer::ID_COMPANY_BUSINESS_UNIT)
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_MERCHANT_RELATIONSHIP, PriceProductMerchantRelationshipStorageTransfer::ID_MERCHANT_RELATIONSHIP)
            ->withColumn(SpyPriceTypeTableMap::COL_NAME, PriceProductMerchantRelationshipValueTransfer::PRICE_TYPE)
            ->withColumn(SpyPriceProductStoreTableMap::COL_PRICE_DATA, PriceProductMerchantRelationshipValueTransfer::PRICE_DATA)
            ->withColumn(SpyPriceProductStoreTableMap::COL_GROSS_PRICE, PriceProductMerchantRelationshipValueTransfer::GROSS_PRICE)
            ->withColumn(SpyPriceProductStoreTableMap::COL_NET_PRICE, PriceProductMerchantRelationshipValueTransfer::NET_PRICE)
            ->setFormatter(new PropelArraySetFormatter())
            ->find();

        return $this->getFactory()
            ->createCompanyBusinessUnitPriceProductMapper()
            ->mapPriceProductMerchantRelationshipArrayToTransfers(
                $priceProductMerchantRelationships
            );
    }

    /**
     * @module Store
     * @module Currency
     * @module PriceProduct
     * @module MerchantRelationship
     * @module PriceProductMerchantRelationship
     *
     * @param int[] $priceProductMerchantRelationshipIds
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function findMerchantRelationshipProductAbstractPricesByIds(array $priceProductMerchantRelationshipIds): array
    {
        $priceProductMerchantRelationships = $this->getFactory()
            ->getPropelPriceProductMerchantRelationshipQuery()
            ->usePriceProductStoreQuery()
                ->innerJoinStore()
                ->innerJoinCurrency()
                ->usePriceProductQuery()
                    ->innerJoinPriceType()
                ->endUse()
            ->endUse()
            ->useMerchantRelationshipQuery()
                ->innerJoinSpyMerchantRelationshipToCompanyBusinessUnit()
            ->endUse()
            ->filterByIdPriceProductMerchantRelationship_In($priceProductMerchantRelationshipIds)
            ->filterByFkProductAbstract(null, Criteria::ISNOTNULL)
            ->withColumn(SpyStoreTableMap::COL_NAME, PriceProductMerchantRelationshipStorageTransfer::STORE_NAME)
            ->withColumn(SpyCurrencyTableMap::COL_CODE, PriceProductMerchantRelationshipValueTransfer::CURRENCY_CODE)
            ->withColumn(SpyPriceProductMerchantRelationshipTableMap::COL_FK_PRODUCT_ABSTRACT, PriceProductMerchantRelationshipStorageTransfer::ID_PRODUCT)
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT, PriceProductMerchantRelationshipStorageTransfer::ID_COMPANY_BUSINESS_UNIT)
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_MERCHANT_RELATIONSHIP, PriceProductMerchantRelationshipStorageTransfer::ID_MERCHANT_RELATIONSHIP)
            ->withColumn(SpyPriceTypeTableMap::COL_NAME, PriceProductMerchantRelationshipValueTransfer::PRICE_TYPE)
            ->withColumn(SpyPriceProductStoreTableMap::COL_PRICE_DATA, PriceProductMerchantRelationshipValueTransfer::PRICE_DATA)
            ->withColumn(SpyPriceProductStoreTableMap::COL_GROSS_PRICE, PriceProductMerchantRelationshipValueTransfer::GROSS_PRICE)
            ->withColumn(SpyPriceProductStoreTableMap::COL_NET_PRICE, PriceProductMerchantRelationshipValueTransfer::NET_PRICE)
            ->setFormatter(new PropelArraySetFormatter())
            ->find();

        return $this->getFactory()
            ->createCompanyBusinessUnitPriceProductMapper()
            ->mapPriceProductMerchantRelationshipArrayToTransfers(
                $priceProductMerchantRelationships
            );
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function findMerchantRelationshipProductAbstractPriceDataByProductAbstractIds(array $productAbstractIds): array
    {
        $priceProductMerchantRelationships = $this->getFactory()
            ->getPropelPriceProductMerchantRelationshipQuery()
            ->usePriceProductStoreQuery()
                ->innerJoinStore()
                ->innerJoinCurrency()
                ->usePriceProductQuery()
                    ->innerJoinPriceType()
                ->endUse()
            ->endUse()
            ->useMerchantRelationshipQuery()
                ->innerJoinSpyMerchantRelationshipToCompanyBusinessUnit()
            ->endUse()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->withColumn(SpyStoreTableMap::COL_NAME, PriceProductMerchantRelationshipStorageTransfer::STORE_NAME)
            ->withColumn(SpyCurrencyTableMap::COL_CODE, PriceProductMerchantRelationshipValueTransfer::CURRENCY_CODE)
            ->withColumn(SpyPriceProductMerchantRelationshipTableMap::COL_FK_PRODUCT_ABSTRACT, PriceProductMerchantRelationshipStorageTransfer::ID_PRODUCT)
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT, PriceProductMerchantRelationshipStorageTransfer::ID_COMPANY_BUSINESS_UNIT)
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_MERCHANT_RELATIONSHIP, PriceProductMerchantRelationshipStorageTransfer::ID_MERCHANT_RELATIONSHIP)
            ->withColumn(SpyPriceTypeTableMap::COL_NAME, PriceProductMerchantRelationshipValueTransfer::PRICE_TYPE)
            ->withColumn(SpyPriceProductStoreTableMap::COL_PRICE_DATA, PriceProductMerchantRelationshipValueTransfer::PRICE_DATA)
            ->withColumn(SpyPriceProductStoreTableMap::COL_GROSS_PRICE, PriceProductMerchantRelationshipValueTransfer::GROSS_PRICE)
            ->withColumn(SpyPriceProductStoreTableMap::COL_NET_PRICE, PriceProductMerchantRelationshipValueTransfer::NET_PRICE)
            ->setFormatter(new PropelArraySetFormatter())
            ->find();

        return $this->getFactory()
            ->createCompanyBusinessUnitPriceProductMapper()
            ->mapPriceProductMerchantRelationshipArrayToTransfers(
                $priceProductMerchantRelationships
            );
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
