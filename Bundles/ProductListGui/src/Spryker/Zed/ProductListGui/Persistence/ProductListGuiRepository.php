<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductListGui\Persistence\ProductListGuiPersistenceFactory getFactory()
 */
class ProductListGuiRepository extends AbstractRepository implements ProductListGuiRepositoryInterface
{
    /** @see \Orm\Zed\Product\Persistence\Map\SpyProductTableMap::COL_ID_PRODUCT */
    public const COLUMN_ID_PRODUCT = 'id_product';
    /** @see \Orm\Zed\Product\Persistence\Map\SpyProductTableMap::COL_SKU */
    public const COLUMN_SKU = 'sku';

    /**
     * @return array
     */
    public function getCategoriesWithPaths(): array
    {
        $idLocale = $this->getIdLocale();
        $categoryEntityList = $this->queryCategory($idLocale)->find();

        $categoryNodes = [];

        foreach ($categoryEntityList as $categoryEntity) {
            foreach ($categoryEntity->getNodes() as $nodeEntity) {
                $path = $this->buildPath($nodeEntity);
                $categoryName = $categoryEntity->getLocalisedAttributes($idLocale)->getFirst()->getName();
                $categoryNodes[$categoryEntity->getIdCategory()] = trim($path . '/' . $categoryName, '/');
            }
        }

        return $categoryNodes;
    }

    /**
     * @uses \Orm\Zed\Product\Persistence\SpyProductQuery
     *
     * @param string[] $sku
     *
     * @return int[]
     */
    public function findProductIdsByProductConcreteSku(array $sku): array
    {
        return $this->getFactory()
            ->getProductQuery()
            ->filterBySku_In($sku)
            ->select([
                static::COLUMN_ID_PRODUCT,
            ])
            ->find()
            ->getData();
    }

    /**
     * @uses \Orm\Zed\Product\Persistence\SpyProductQuery
     *
     * @param int[] $productIds
     *
     * @return string[]
     */
    public function findProductSkuByIdProductConcrete(array $productIds): array
    {
        return $this->getFactory()
            ->getProductQuery()
            ->filterByIdProduct_In($productIds)
            ->select([
                static::COLUMN_SKU,
            ])
            ->find()
            ->getData();
    }

    /**
     * @param int $idNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryPath(
        $idNode,
        $idLocale
    ): SpyCategoryNodeQuery {
        $depth = 0;
        $nodeQuery = $this->getFactory()->getCategoryNodePropelQuery();

        $nodeQuery
            ->useClosureTableQuery()
                ->orderByFkCategoryNodeDescendant(Criteria::DESC)
                ->orderByDepth(Criteria::DESC)
                ->filterByFkCategoryNodeDescendant($idNode)
                ->filterByDepth($depth, Criteria::NOT_EQUAL)
            ->endUse()
            ->useCategoryQuery()
                ->useAttributeQuery()
                    ->filterByFkLocale($idLocale)
                ->endUse()
            ->endUse();

        $nodeQuery->setFormatter(new PropelArraySetFormatter());

        return $nodeQuery;
    }

    /**
     * @module Product
     *
     * @param int[] $productIds
     *
     * @return array
     */
    public function findProductConcreteDataByIds(array $productIds): array
    {
        return $this->getFactory()
            ->getProductQuery()
            ->joinSpyProductLocalizedAttributes()
            ->useSpyProductLocalizedAttributesQuery()
                ->filterByFkLocale($this->getIdLocale())
            ->endUse()
            ->withColumn(SpyProductTableMap::COL_ID_PRODUCT, 'id_product')
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, 'name')
            ->withColumn(SpyProductTableMap::COL_SKU, 'sku')
            ->select(
                [
                    SpyProductTableMap::COL_ID_PRODUCT,
                    SpyProductTableMap::COL_SKU,
                    SpyProductLocalizedAttributesTableMap::COL_NAME,
                ]
            )
            ->filterByIdProduct_In($productIds)
            ->find()
            ->toArray();
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $categoryNodeEntity
     *
     * @return string
     */
    protected function buildPath(SpyCategoryNode $categoryNodeEntity)
    {
        $idLocale = $this->getIdLocale();
        $idCategoryNode = $categoryNodeEntity->getIdCategoryNode();
        $pathTokens = $this->queryCategoryPath($idCategoryNode, $idLocale)
            ->clearSelectColumns()
            ->addSelectColumn('name')
            ->find();

        return implode('/', $pathTokens);
    }

    /**
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    protected function queryCategory($idLocale): SpyCategoryQuery
    {
        return $this->getFactory()
            ->getCategoryPropelQuery()
            ->joinAttribute()
            ->innerJoinNode()
            ->addAnd(
                SpyCategoryAttributeTableMap::COL_FK_LOCALE,
                $idLocale,
                Criteria::EQUAL
            );
    }

    /**
     * @return int
     */
    protected function getIdLocale(): int
    {
        return $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale()
            ->getIdLocale();
    }
}
