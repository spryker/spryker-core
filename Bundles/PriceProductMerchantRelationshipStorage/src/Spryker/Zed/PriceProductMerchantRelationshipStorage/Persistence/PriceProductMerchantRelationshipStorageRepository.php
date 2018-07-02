<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipToCompanyBusinessUnitTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\Map\SpyPriceProductMerchantRelationshipTableMap;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationship;
use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\Map\SpyPriceProductAbstractMerchantRelationshipStorageTableMap;
use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\Map\SpyPriceProductConcreteMerchantRelationshipStorageTableMap;
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
     * @param array $priceProductStoreIds
     *
     * @return array
     */
    public function findPriceProductStoreListByIdsForConcrete(array $priceProductStoreIds): array
    {
        $priceProductStoreQuery = $this->queryPriceProductStoreByIds($priceProductStoreIds);
        $priceProductStoreQuery = $this->queryProducts($priceProductStoreQuery)
            ->select([
                static::COL_PRODUCT_CONCRETE_SKU,
                static::COL_PRODUCT_CONCRETE_ID_PRODUCT,
                PriceProductMerchantRelationshipStorageConstants::COL_PRICE_PRODUCT_STORE_FK_STORE,
                SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP,
            ])
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT, MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT)
            ->groupBy(static::COL_PRODUCT_CONCRETE_SKU)
            ->addGroupByColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT);

        return $priceProductStoreQuery->find()->toArray();
    }

    /**
     * @deprecated
     *
     * @param array $priceProductStoreIds
     *
     * @return array
     */
    public function findPriceProductStoreListByIdsForAbstract(array $priceProductStoreIds): array
    {
        $priceProductStoreQuery = $this->queryPriceProductStoreByIds($priceProductStoreIds);
        $priceProductStoreQuery = $this->queryProductsAbstract($priceProductStoreQuery)
            ->select([
                static::COL_PRODUCT_ABSTRACT_SKU,
                static::COL_PRODUCT_ABSTRACT_ID_PRODUCT,
                PriceProductMerchantRelationshipStorageConstants::COL_PRICE_PRODUCT_STORE_FK_STORE,
                SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP,
            ])
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT, MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT)
            ->groupBy(static::COL_PRODUCT_ABSTRACT_SKU)
            ->addGroupByColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT);

        return $priceProductStoreQuery->find()->toArray();
    }

    /**
     * @param array $businessUnitProducts
     *
     * @return array
     */
    public function findPriceProductStoresByCompanyBusinessUnitAbstractProducts(array $businessUnitProducts): array
    {
        $priceProductStoreQuery = $this->queryPriceProductStoreByCompanyBusinessUnitProducts($businessUnitProducts);
        $priceProductStoreQuery = $this->queryProductsAbstract($priceProductStoreQuery)
            ->select([
                static::COL_PRODUCT_ABSTRACT_SKU,
                static::COL_PRODUCT_ABSTRACT_ID_PRODUCT,
                SpyPriceProductStoreTableMap::COL_FK_STORE,
                SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP,
            ])
            ->withColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT, MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT)
            ->groupBy(static::COL_PRODUCT_ABSTRACT_SKU)
            ->addGroupByColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT);

        return $priceProductStoreQuery->find()->toArray();
    }

    /**
     * @param array $businessUnitProducts
     *
     * @return array
     */
    public function findPriceProductStoresByCompanyBusinessUnitConcreteProducts(array $businessUnitProducts): array
    {
        $priceProductStoreQuery = $this->queryPriceProductStoreByCompanyBusinessUnitProducts($businessUnitProducts);
        $priceProductStoreQuery = $this->queryProducts($priceProductStoreQuery)
            ->select([
                static::COL_PRODUCT_CONCRETE_SKU,
                static::COL_PRODUCT_CONCRETE_ID_PRODUCT,
                SpyPriceProductStoreTableMap::COL_FK_STORE,
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
     * @param array $concreteProducts
     *
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage[]
     */
    public function findExistingPriceProductConcreteMerchantRelationshipStorageEntities(array $concreteProducts): array
    {
        $query = $this->getFactory()->createPriceProductConcreteMerchantRelationshipStorageQuery();

        $whereGroups = [];
        foreach ($concreteProducts as $index => $product) {
            $merchantRelationshipConditionName = 'b_cond' . $index;
            $productConditionName = 'p_cond' . $index;
            $combineConditionName = 'and_cond' . $index;

            $query->condition(
                $merchantRelationshipConditionName,
                SpyPriceProductConcreteMerchantRelationshipStorageTableMap::COL_FK_COMPANY_BUSINESS_UNIT . ' = ?',
                (int)$product[MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT]
            )->condition(
                $productConditionName,
                SpyPriceProductConcreteMerchantRelationshipStorageTableMap::COL_FK_PRODUCT . ' = ?',
                $product[static::COL_PRODUCT_CONCRETE_ID_PRODUCT]
            )->combine(
                [$merchantRelationshipConditionName, $productConditionName],
                Criteria::LOGICAL_AND,
                $combineConditionName
            );

            $whereGroups[] = $combineConditionName;
        }

        $query->where($whereGroups, Criteria::LOGICAL_OR);
        $priceProductMerchantRelationshipStorageEntities = $query->find();

        $priceProductMerchantRelationshipStorageEntityMap = [];
        foreach ($priceProductMerchantRelationshipStorageEntities as $priceProductMerchantRelationshipStorageEntity) {
            $identifier = $priceProductMerchantRelationshipStorageEntity->getPriceKey();
            $priceProductMerchantRelationshipStorageEntityMap[$identifier] = $priceProductMerchantRelationshipStorageEntity;
        }

        return $priceProductMerchantRelationshipStorageEntityMap;
    }

    /**
     * @param array $concreteProducts
     *
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage[]
     */
    public function findExistingPriceProductAbstractMerchantRelationshipStorageEntities(array $concreteProducts): array
    {
        $query = $this->getFactory()->createPriceProductAbstractMerchantRelationshipStorageQuery();

        $whereGroups = [];
        foreach ($concreteProducts as $index => $product) {
            $merchantRelationshipConditionName = 'b_cond' . $index;
            $productConditionName = 'p_cond' . $index;
            $combineConditionName = 'and_cond' . $index;

            $query->condition(
                $merchantRelationshipConditionName,
                SpyPriceProductAbstractMerchantRelationshipStorageTableMap::COL_FK_COMPANY_BUSINESS_UNIT . ' = ?',
                (int)$product[MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT]
            )->condition(
                $productConditionName,
                SpyPriceProductAbstractMerchantRelationshipStorageTableMap::COL_FK_PRODUCT_ABSTRACT . ' = ?',
                (int)$product[static::COL_PRODUCT_ABSTRACT_ID_PRODUCT]
            )->combine(
                [$merchantRelationshipConditionName, $productConditionName],
                Criteria::LOGICAL_AND,
                $combineConditionName
            );

            $whereGroups[] = $combineConditionName;
        }

        $priceProductMerchantRelationshipStorageEntities = $query
            ->where($whereGroups, Criteria::LOGICAL_OR)
            ->find();

        $priceProductMerchantRelationshipStorageEntityMap = [];
        foreach ($priceProductMerchantRelationshipStorageEntities as $priceProductMerchantRelationshipStorageEntity) {
            $identifier = $priceProductMerchantRelationshipStorageEntity->getPriceKey();
            $priceProductMerchantRelationshipStorageEntityMap[$identifier] = $priceProductMerchantRelationshipStorageEntity;
        }

        return $priceProductMerchantRelationshipStorageEntityMap;
    }

    /**
     * @param array $businessUnitProducts
     *
     * @return \Propel\Runtime\ActiveQuery\Criteria
     */
    protected function queryPriceProductStoreByCompanyBusinessUnitProducts(array $businessUnitProducts): Criteria
    {
        return $this->getFactory()
            ->getPropelPriceProductStoreQuery()
            ->joinWithPriceProductMerchantRelationship()
            ->addJoin(
                SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP,
                SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_MERCHANT_RELATIONSHIP,
                Criteria::INNER_JOIN
            )->addCond(
                'cond1',
                SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT,
                array_keys($businessUnitProducts),
                Criteria::IN
            )->combine(['cond1']);
    }

    /**
     * @param array $priceProductStoreIds
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    protected function queryPriceProductStoreByIds(array $priceProductStoreIds): SpyPriceProductStoreQuery
    {
        return $this->getFactory()
            ->getPropelPriceProductStoreQuery()
            ->joinWithPriceProductMerchantRelationship()
            ->addJoin(
                SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP,
                SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_MERCHANT_RELATIONSHIP,
                Criteria::LEFT_JOIN
            )
            ->filterByIdPriceProductStore($priceProductStoreIds, Criteria::IN);
    }

    /**
     * @param int $idMerchantRelationship
     *
     * @return array
     */
    public function findCompanyBusinessUnitIdsByMerchantRelationship(int $idMerchantRelationship): array
    {
        return $this->getFactory()
            ->getPropelMerchantRelationshipToCompanyBusinessUnitQuery()
            ->select([
                SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT,
            ])
            ->filterByFkMerchantRelationship($idMerchantRelationship)
            ->find()
            ->toArray();
    }

    /**
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
}
