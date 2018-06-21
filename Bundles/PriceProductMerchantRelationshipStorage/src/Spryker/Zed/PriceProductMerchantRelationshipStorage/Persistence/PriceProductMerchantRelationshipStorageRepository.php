<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence;

use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\Map\SpyPriceProductMerchantRelationshipTableMap;
use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\Map\SpyPriceProductAbstractMerchantRelationshipStorageTableMap;
use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\Map\SpyPriceProductConcreteMerchantRelationshipStorageTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStoragePersistenceFactory getFactory()
 */
class PriceProductMerchantRelationshipStorageRepository extends AbstractRepository implements PriceProductMerchantRelationshipStorageRepositoryInterface
{
    public const COL_STORE_ID = 'spy_price_product_store.fk_store';
    public const COL_PRODUCT_CONCRETE_SKU = 'spy_product.sku';
    public const COL_PRODUCT_CONCRETE_ID_PRODUCT = 'spy_product.id_product';
    public const COL_PRODUCT_ABSTRACT_SKU = 'spy_product_abstract.sku';
    public const COL_PRODUCT_ABSTRACT_ID_PRODUCT = 'spy_product_abstract.id_product_abstract';

    /**
     * @api
     *
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
                static::COL_STORE_ID,
                SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP,
            ])->groupBy(static::COL_PRODUCT_CONCRETE_SKU);

        return $priceProductStoreQuery->find()->toArray();
    }

    /**
     * @api
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
                static::COL_STORE_ID,
                SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP,
            ])->groupBy(static::COL_PRODUCT_ABSTRACT_SKU);

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
     * @api
     *
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
                SpyPriceProductConcreteMerchantRelationshipStorageTableMap::COL_FK_MERCHANT_RELATIONSHIP . ' = ?',
                $product[SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP]
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
        $PriceProductMerchantRelationshipStorageEntityCollection = $query->find();

        $PriceProductMerchantRelationshipStorageEntityMap = [];
        foreach ($PriceProductMerchantRelationshipStorageEntityCollection as $PriceProductMerchantRelationshipStorageEntity) {
            $identifier = $PriceProductMerchantRelationshipStorageEntity->getPriceKey();
            $PriceProductMerchantRelationshipStorageEntityMap[$identifier] = $PriceProductMerchantRelationshipStorageEntity;
        }

        return $PriceProductMerchantRelationshipStorageEntityMap;
    }

    /**
     * @api
     *
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
                SpyPriceProductAbstractMerchantRelationshipStorageTableMap::COL_FK_MERCHANT_RELATIONSHIP . ' = ?',
                (int)$product[SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP]
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

        $PriceProductMerchantRelationshipStorageEntityCollection = $query
            ->where($whereGroups, Criteria::LOGICAL_OR)
            ->find();

        $PriceProductMerchantRelationshipStorageEntityMap = [];
        foreach ($PriceProductMerchantRelationshipStorageEntityCollection as $PriceProductMerchantRelationshipStorageEntity) {
            $identifier = $PriceProductMerchantRelationshipStorageEntity->getPriceKey();
            $PriceProductMerchantRelationshipStorageEntityMap[$identifier] = $PriceProductMerchantRelationshipStorageEntity;
        }

        return $PriceProductMerchantRelationshipStorageEntityMap;
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
            ->filterByIdPriceProductStore($priceProductStoreIds, Criteria::IN);
    }
}
