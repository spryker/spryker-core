<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipToCompanyBusinessUnitTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\Map\SpyPriceProductMerchantRelationshipTableMap;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationship;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConstants;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStoragePersistenceFactory getFactory()
 */
class PriceProductMerchantRelationshipStorageRepository extends AbstractRepository implements PriceProductMerchantRelationshipStorageRepositoryInterface
{
    /**
     * @uses \Orm\Zed\Product\Persistence\Map\SpyProductTableMap::COL_SKU
     */
    public const COL_PRODUCT_CONCRETE_SKU = 'spy_product.sku';

    /**
     * @uses \Orm\Zed\Product\Persistence\Map\SpyProductTableMap::COL_ID_PRODUCT
     */
    public const COL_PRODUCT_CONCRETE_ID_PRODUCT = 'spy_product.id_product';

    /**
     * @uses \Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap::COL_SKU
     */
    public const COL_PRODUCT_ABSTRACT_SKU = 'spy_product_abstract.sku';

    /**
     * @uses \Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT
     */
    public const COL_PRODUCT_ABSTRACT_ID_PRODUCT = 'spy_product_abstract.id_product_abstract';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param array $priceProductStoreIds
     *
     * @return array
     */
    public function findPriceProductStoreListByIdsForConcrete(array $priceProductStoreIds): array
    {
        $priceProductStoreQuery = $this->queryPriceProductStoreByIds($priceProductStoreIds);
        /** @var \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery */
        $priceProductStoreQuery = $this->queryProducts($priceProductStoreQuery)
            ->joinWithStore()
            ->select([
                static::COL_PRODUCT_CONCRETE_SKU,
                static::COL_PRODUCT_CONCRETE_ID_PRODUCT,
                PriceProductMerchantRelationshipStorageConstants::COL_STORE_NAME,
                SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP,
            ])
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT, MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT)
            ->groupBy(static::COL_PRODUCT_CONCRETE_SKU)
            ->addGroupByColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT);

        return $priceProductStoreQuery->find()->toArray();
    }

    /**
     * @module Store
     *
     * @deprecated Will be removed without replacement.
     *
     * @param array $priceProductStoreIds
     *
     * @return array
     */
    public function findPriceProductStoreListByIdsForAbstract(array $priceProductStoreIds): array
    {
        $priceProductStoreQuery = $this->queryPriceProductStoreByIds($priceProductStoreIds);
        /** @var \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery */
        $priceProductStoreQuery = $this->queryProductsAbstract($priceProductStoreQuery)
            ->joinWithStore()
            ->select([
                static::COL_PRODUCT_ABSTRACT_SKU,
                static::COL_PRODUCT_ABSTRACT_ID_PRODUCT,
                PriceProductMerchantRelationshipStorageConstants::COL_STORE_NAME,
                SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP,
            ])
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT, MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT)
            ->groupBy(static::COL_PRODUCT_ABSTRACT_SKU)
            ->addGroupByColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT);

        return $priceProductStoreQuery->find()->toArray();
    }

    /**
     * @module Store
     *
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
    public function getProductAbstractPriceDataByCompanyBusinessUnitIds(array $businessUnitIds): array
    {
        $priceProductStoreQuery = $this->queryPriceProductStoreByCompanyBusinessUnitIds($businessUnitIds);
        /** @var \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery */
        $priceProductStoreQuery = $this->queryProductsAbstract($priceProductStoreQuery)
            ->joinWithStore()
            ->select([
                static::COL_PRODUCT_ABSTRACT_SKU,
                static::COL_PRODUCT_ABSTRACT_ID_PRODUCT,
                PriceProductMerchantRelationshipStorageConstants::COL_STORE_NAME,
                SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP,
            ])
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT, MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT)
            ->groupBy(static::COL_PRODUCT_ABSTRACT_SKU)
            ->addGroupByColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT);

        return $priceProductStoreQuery->find()->toArray();
    }

    /**
     * @module Store
     *
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
    public function getProductConcretePriceDataByCompanyBusinessUnitIds(array $businessUnitIds): array
    {
        $priceProductStoreQuery = $this->queryPriceProductStoreByCompanyBusinessUnitIds($businessUnitIds);
        /** @var \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery */
        $priceProductStoreQuery = $this->queryProducts($priceProductStoreQuery)
            ->joinWithStore()
            ->select([
                static::COL_PRODUCT_CONCRETE_SKU,
                static::COL_PRODUCT_CONCRETE_ID_PRODUCT,
                PriceProductMerchantRelationshipStorageConstants::COL_STORE_NAME,
                SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP,
            ])
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT, MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT)
            ->groupBy(static::COL_PRODUCT_CONCRETE_SKU)
            ->addGroupByColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT);

        return $priceProductStoreQuery->find()->toArray();
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    protected function queryProducts(SpyPriceProductStoreQuery $priceProductStoreQuery): SpyPriceProductStoreQuery
    {
        return $priceProductStoreQuery
            ->usePriceProductQuery()
                ->innerJoinProduct()
            ->endUse();
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    protected function queryProductsAbstract(SpyPriceProductStoreQuery $priceProductStoreQuery): SpyPriceProductStoreQuery
    {
        return $priceProductStoreQuery
            ->usePriceProductQuery()
                ->innerJoinSpyProductAbstract()
            ->endUse();
    }

    /**
     * @param int $idCompanyBusinessUnit
     * @param array $productConcreteIds
     *
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage[]
     */
    public function findExistingPriceProductConcreteMerchantRelationshipStorageEntities(int $idCompanyBusinessUnit, array $productConcreteIds): array
    {
        $query = $this->getFactory()->createPriceProductConcreteMerchantRelationshipStorageQuery()
            ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->filterByFkProduct_In($productConcreteIds);

        $priceProductMerchantRelationshipStorageEntities = $query->find();

        $priceProductMerchantRelationshipStorageEntityMap = [];
        foreach ($priceProductMerchantRelationshipStorageEntities as $priceProductMerchantRelationshipStorageEntity) {
            $identifier = $priceProductMerchantRelationshipStorageEntity->getPriceKey();
            $priceProductMerchantRelationshipStorageEntityMap[$identifier] = $priceProductMerchantRelationshipStorageEntity;
        }

        return $priceProductMerchantRelationshipStorageEntityMap;
    }

    /**
     * @param int $idCompanyBusinessUnit
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage[]
     */
    public function findExistingPriceProductAbstractMerchantRelationshipStorageEntities(int $idCompanyBusinessUnit, array $productAbstractIds): array
    {
        $query = $this->getFactory()->createPriceProductAbstractMerchantRelationshipStorageQuery()
            ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->filterByFkProductAbstract_In($productAbstractIds);

        $priceProductMerchantRelationshipStorageEntities = $query->find();

        $priceProductMerchantRelationshipStorageEntityMap = [];
        foreach ($priceProductMerchantRelationshipStorageEntities as $priceProductMerchantRelationshipStorageEntity) {
            $identifier = $priceProductMerchantRelationshipStorageEntity->getPriceKey();
            $priceProductMerchantRelationshipStorageEntityMap[$identifier] = $priceProductMerchantRelationshipStorageEntity;
        }

        return $priceProductMerchantRelationshipStorageEntityMap;
    }

    /**
     * @param array $businessUnitIds
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    protected function queryPriceProductStoreByCompanyBusinessUnitIds(array $businessUnitIds): SpyPriceProductStoreQuery
    {
        /** @var \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery */
        $priceProductStoreQuery = $this->getFactory()
            ->getPropelPriceProductStoreQuery()
            ->joinWithPriceProductMerchantRelationship()
            ->addJoin(
                SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP,
                SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_MERCHANT_RELATIONSHIP,
                Criteria::INNER_JOIN
            )->addCond(
                'cond1',
                SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT,
                $businessUnitIds,
                Criteria::IN
            )->combine(['cond1']);

        return $priceProductStoreQuery;
    }

    /**
     * @param array $priceProductStoreIds
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    protected function queryPriceProductStoreByIds(array $priceProductStoreIds): SpyPriceProductStoreQuery
    {
        /** @var \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery */
        $priceProductStoreQuery = $this->getFactory()
            ->getPropelPriceProductStoreQuery()
            ->filterByIdPriceProductStore($priceProductStoreIds, Criteria::IN)
            ->joinWithPriceProductMerchantRelationship()
            ->addJoin(
                SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP,
                SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_MERCHANT_RELATIONSHIP,
                Criteria::LEFT_JOIN
            );

        return $priceProductStoreQuery;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param int $idMerchantRelationship
     *
     * @return array
     */
    public function findCompanyBusinessUnitIdsByMerchantRelationship(int $idMerchantRelationship): array
    {
        return $this->getFactory()
            ->getPropelMerchantRelationshipToCompanyBusinessUnitQuery()
            ->filterByFkMerchantRelationship($idMerchantRelationship)
            ->select([
                SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT,
            ])
            ->find()
            ->toArray();
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
     * @deprecated Will be removed without replacement.
     *
     * @param string $idPriceProductMerchantRelationship
     *
     * @return \Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationship|null
     */
    public function findPriceProductMerchantRelationship(string $idPriceProductMerchantRelationship): ?SpyPriceProductMerchantRelationship
    {
        return $this->getFactory()
            ->getPropelPriceProductMerchantRelationshipQuery()
            ->findOneByIdPriceProductMerchantRelationship($idPriceProductMerchantRelationship);
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
