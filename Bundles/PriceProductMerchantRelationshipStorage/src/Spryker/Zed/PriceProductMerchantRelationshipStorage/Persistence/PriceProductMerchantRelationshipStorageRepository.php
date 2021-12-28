<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;
use Generated\Shared\Transfer\PriceProductMerchantRelationshipValueTransfer;
use Orm\Zed\Currency\Persistence\Map\SpyCurrencyTableMap;
use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipTableMap;
use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipToCompanyBusinessUnitTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\Map\SpyPriceProductMerchantRelationshipTableMap;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStoragePersistenceFactory getFactory()
 */
class PriceProductMerchantRelationshipStorageRepository extends AbstractRepository implements PriceProductMerchantRelationshipStorageRepositoryInterface
{
    /**
     * @param array<int> $companyBusinessUnitIds
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer>
     */
    public function findMerchantRelationshipProductAbstractPricesDataByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array
    {
        $priceProductMerchantRelationshipsQuery = $this->queryPriceProductMerchantRelationship($companyBusinessUnitIds)
            ->filterByFkProductAbstract(null, Criteria::ISNOTNULL);

        $priceProductMerchantRelationships = $this->withPriceProductAbstractData($priceProductMerchantRelationshipsQuery)
            ->setFormatter(new PropelArraySetFormatter())
            ->find();

        return $this->getFactory()
            ->createCompanyBusinessUnitPriceProductMapper()
            ->mapPriceProductMerchantRelationshipArrayToTransfers(
                $priceProductMerchantRelationships,
            );
    }

    /**
     * @param array<int> $companyBusinessUnitIds
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer>
     */
    public function findMerchantRelationshipProductConcretePricesDataByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array
    {
        $priceProductMerchantRelationshipsQuery = $this->queryPriceProductMerchantRelationship($companyBusinessUnitIds)
            ->filterByFkProduct(null, Criteria::ISNOTNULL);

        $priceProductMerchantRelationships = $this->withPriceProductConcreteData($priceProductMerchantRelationshipsQuery)
            ->setFormatter(new PropelArraySetFormatter())
            ->find();

        return $this->getFactory()
            ->createCompanyBusinessUnitPriceProductMapper()
            ->mapPriceProductMerchantRelationshipArrayToTransfers(
                $priceProductMerchantRelationships,
            );
    }

    /**
     * @param array<int> $priceProductMerchantRelationshipIds
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer>
     */
    public function findMerchantRelationshipProductConcretePricesDataByIds(array $priceProductMerchantRelationshipIds): array
    {
        $priceProductMerchantRelationshipsQuery = $this->queryPriceProductMerchantRelationship()
            ->filterByIdPriceProductMerchantRelationship_In($priceProductMerchantRelationshipIds)
            ->filterByFkProduct(null, Criteria::ISNOTNULL);

        $priceProductMerchantRelationships = $this->withPriceProductConcreteData($priceProductMerchantRelationshipsQuery)
            ->setFormatter(new PropelArraySetFormatter())
            ->find();

        return $this->getFactory()
            ->createCompanyBusinessUnitPriceProductMapper()
            ->mapPriceProductMerchantRelationshipArrayToTransfers(
                $priceProductMerchantRelationships,
            );
    }

    /**
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer>
     */
    public function findMerchantRelationshipProductConcretePricesDataByProductIds(array $productIds): array
    {
        $priceProductMerchantRelationshipsQuery = $this->queryPriceProductMerchantRelationship()
            ->filterByFkProduct_In($productIds);

        $priceProductMerchantRelationships = $this->withPriceProductConcreteData($priceProductMerchantRelationshipsQuery)
            ->setFormatter(new PropelArraySetFormatter())
            ->find();

        return $this->getFactory()
            ->createCompanyBusinessUnitPriceProductMapper()
            ->mapPriceProductMerchantRelationshipArrayToTransfers(
                $priceProductMerchantRelationships,
            );
    }

    /**
     * @param array<int> $priceProductMerchantRelationshipIds
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer>
     */
    public function findMerchantRelationshipProductAbstractPricesByIds(array $priceProductMerchantRelationshipIds): array
    {
        $priceProductMerchantRelationshipsQuery = $this->queryPriceProductMerchantRelationship()
            ->filterByIdPriceProductMerchantRelationship_In($priceProductMerchantRelationshipIds)
            ->filterByFkProductAbstract(null, Criteria::ISNOTNULL);

        $priceProductMerchantRelationships = $this->withPriceProductAbstractData($priceProductMerchantRelationshipsQuery)
            ->setFormatter(new PropelArraySetFormatter())
            ->find();

        return $this->getFactory()
            ->createCompanyBusinessUnitPriceProductMapper()
            ->mapPriceProductMerchantRelationshipArrayToTransfers(
                $priceProductMerchantRelationships,
            );
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer>
     */
    public function findMerchantRelationshipProductAbstractPricesDataByProductAbstractIds(array $productAbstractIds): array
    {
        $priceProductMerchantRelationshipsQuery = $this->queryPriceProductMerchantRelationship()
            ->filterByFkProductAbstract_In($productAbstractIds);

        $priceProductMerchantRelationships = $this->withPriceProductAbstractData($priceProductMerchantRelationshipsQuery)
            ->setFormatter(new PropelArraySetFormatter())
            ->find();

        return $this->getFactory()
            ->createCompanyBusinessUnitPriceProductMapper()
            ->mapPriceProductMerchantRelationshipArrayToTransfers(
                $priceProductMerchantRelationships,
            );
    }

    /**
     * @param array<int> $companyBusinessUnitIds
     *
     * @return array<\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage>
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
     * @param array<string> $priceKeys
     *
     * @return array<\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage>
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
     * @param array<int> $productAbstractIds
     *
     * @return array<\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage>
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
     * @param array<int> $companyBusinessUnitIds
     *
     * @return array<\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage>
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
     * @param array<string> $priceKeys
     *
     * @return array<\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage>
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
     * @param array<int> $productIds
     *
     * @return array<\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage>
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
     * @return array<\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage>
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
     * @return array<\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage>
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
     * @return array<\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage>
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
     * @return array<\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage>
     */
    public function findPriceProductAbstractMerchantRelationshipStorageEntitiesByIds(array $priceProductAbstractMerchantRelationshipStorageEntityIds): array
    {
        return $this->getFactory()
            ->createPriceProductAbstractMerchantRelationshipStorageQuery()
            ->filterByIdPriceProductAbstractMerchantRelationshipStorage_In($priceProductAbstractMerchantRelationshipStorageEntityIds)
            ->find()
            ->getArrayCopy();
    }

    /**
     * @module Store
     * @module Currency
     * @module PriceProduct
     * @module MerchantRelationship
     * @module PriceProductMerchantRelationship
     *
     * @param array $filterByCompanyBusinessUnitIds
     *
     * @return \Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery
     */
    protected function queryPriceProductMerchantRelationship(array $filterByCompanyBusinessUnitIds = []): SpyPriceProductMerchantRelationshipQuery
    {
        return $this->getFactory()
            ->getPropelPriceProductMerchantRelationshipQuery()
            ->usePriceProductStoreQuery()
                ->innerJoinStore()
                ->innerJoinCurrency()
                ->usePriceProductQuery()
                    ->innerJoinPriceType()
                ->endUse()
            ->endUse()
            ->useMerchantRelationshipQuery()
                ->useSpyMerchantRelationshipToCompanyBusinessUnitQuery()
                    ->_if((bool)$filterByCompanyBusinessUnitIds)
                        ->filterByFkCompanyBusinessUnit_In($filterByCompanyBusinessUnitIds)
                    ->_endif()
                ->endUse()
            ->endUse();
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function withPriceProductAbstractData(ModelCriteria $modelCriteria): ModelCriteria
    {
        return $this->withPriceProductData($modelCriteria)
            ->withColumn(SpyPriceProductMerchantRelationshipTableMap::COL_FK_PRODUCT_ABSTRACT, PriceProductMerchantRelationshipStorageTransfer::ID_PRODUCT)
            ->withColumn(SpyMerchantRelationshipTableMap::COL_FK_MERCHANT, PriceProductMerchantRelationshipValueTransfer::FK_MERCHANT);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function withPriceProductConcreteData(ModelCriteria $modelCriteria): ModelCriteria
    {
        return $this->withPriceProductData($modelCriteria)
            ->withColumn(SpyPriceProductMerchantRelationshipTableMap::COL_FK_PRODUCT, PriceProductMerchantRelationshipStorageTransfer::ID_PRODUCT)
            ->withColumn(SpyMerchantRelationshipTableMap::COL_FK_MERCHANT, PriceProductMerchantRelationshipValueTransfer::FK_MERCHANT);
    }

    /**
     * @module Store
     * @module Currency
     * @module PriceProduct
     * @module MerchantRelationship
     * @module PriceProductMerchantRelationship
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function withPriceProductData(ModelCriteria $modelCriteria): ModelCriteria
    {
        return $modelCriteria
            ->withColumn(SpyStoreTableMap::COL_NAME, PriceProductMerchantRelationshipStorageTransfer::STORE_NAME)
            ->withColumn(SpyCurrencyTableMap::COL_CODE, PriceProductMerchantRelationshipValueTransfer::CURRENCY_CODE)
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT, PriceProductMerchantRelationshipStorageTransfer::ID_COMPANY_BUSINESS_UNIT)
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_MERCHANT_RELATIONSHIP, PriceProductMerchantRelationshipValueTransfer::ID_MERCHANT_RELATIONSHIP)
            ->withColumn(SpyPriceTypeTableMap::COL_NAME, PriceProductMerchantRelationshipValueTransfer::PRICE_TYPE)
            ->withColumn(SpyPriceProductStoreTableMap::COL_PRICE_DATA, PriceProductMerchantRelationshipValueTransfer::PRICE_DATA)
            ->withColumn(SpyPriceProductStoreTableMap::COL_GROSS_PRICE, PriceProductMerchantRelationshipValueTransfer::GROSS_PRICE)
            ->withColumn(SpyPriceProductStoreTableMap::COL_NET_PRICE, PriceProductMerchantRelationshipValueTransfer::NET_PRICE);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $priceProductConcreteMerchantRelationshipStorageIds
     *
     * @return array<\Generated\Shared\Transfer\SpyPriceProductConcreteMerchantRelationshipStorageEntityTransfer>
     */
    public function findFilteredPriceProductConcreteMerchantRelationshipStorageEntities(
        FilterTransfer $filterTransfer,
        array $priceProductConcreteMerchantRelationshipStorageIds = []
    ): array {
        $query = $this->getFactory()->createPriceProductConcreteMerchantRelationshipStorageQuery();

        if ($priceProductConcreteMerchantRelationshipStorageIds) {
            $query->filterByIdPriceProductConcreteMerchantRelationshipStorage_In($priceProductConcreteMerchantRelationshipStorageIds);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $priceProductAbstractMerchantRelationshipStorageIds
     *
     * @return array<\Generated\Shared\Transfer\SpyPriceProductAbstractMerchantRelationshipStorageEntityTransfer>
     */
    public function findFilteredPriceProductAbstractMerchantRelationshipStorageEntities(
        FilterTransfer $filterTransfer,
        array $priceProductAbstractMerchantRelationshipStorageIds = []
    ): array {
        $query = $this->getFactory()->createPriceProductAbstractMerchantRelationshipStorageQuery();

        if ($priceProductAbstractMerchantRelationshipStorageIds) {
            $query->filterByIdPriceProductAbstractMerchantRelationshipStorage_In($priceProductAbstractMerchantRelationshipStorageIds);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipTransfer>
     */
    public function getFilteredPriceProductConcreteMerchantRelationships(FilterTransfer $filterTransfer): array
    {
        $priceProductMerchantRelationshipQuery = $this->queryPriceProductMerchantRelationship()
            ->filterByFkProduct(null, Criteria::ISNOTNULL);

        $priceProductMerchantRelationshipEntityTransfers = $this->buildQueryFromCriteria($priceProductMerchantRelationshipQuery, $filterTransfer)
            ->setFormatter(ModelCriteria::FORMAT_OBJECT)
            ->find();

        return $this->getFactory()
            ->createPriceProductMerchantRelationshipMapper()
            ->mapEntitiesToPriceProductMerchantRelationshipTransferCollection(
                $priceProductMerchantRelationshipEntityTransfers->getData(),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipTransfer>
     */
    public function getFilteredPriceProductAbstractMerchantRelationships(FilterTransfer $filterTransfer): array
    {
        $priceProductMerchantRelationshipQuery = $this->queryPriceProductMerchantRelationship()
            ->filterByFkProductAbstract(null, Criteria::ISNOTNULL);

        $priceProductMerchantRelationshipEntityTransfers = $this->buildQueryFromCriteria($priceProductMerchantRelationshipQuery, $filterTransfer)
            ->setFormatter(ModelCriteria::FORMAT_OBJECT)
            ->find();

        return $this->getFactory()
            ->createPriceProductMerchantRelationshipMapper()
            ->mapEntitiesToPriceProductMerchantRelationshipTransferCollection(
                $priceProductMerchantRelationshipEntityTransfers->getData(),
            );
    }
}
