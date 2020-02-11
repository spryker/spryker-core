<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryClosureTableTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStoragePersistenceFactory getFactory()
 */
class ProductCategoryStorageQueryContainer extends AbstractQueryContainer implements ProductCategoryStorageQueryContainerInterface
{
    public const FK_CATEGORY = 'fkCategory';

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductCategoryMappings($idProductAbstract)
    {
        return $this->getFactory()
            ->getProductCategoryQueryContainer()
            ->queryLocalizedProductCategoryMappingByIdProduct($idProductAbstract)
            ->innerJoinSpyCategory()
            ->addAnd(
                SpyCategoryTableMap::COL_IS_ACTIVE,
                true,
                Criteria::EQUAL
            )
            ->orderByProductOrder();
    }

    /**
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @param int[] $productCategoryIds
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductCategoryByIds($productCategoryIds)
    {
        return $this->getFactory()
            ->getProductCategoryQueryContainer()
            ->queryProductCategoryMappings()
            ->innerJoinSpyCategory()
            ->addAnd(
                SpyCategoryTableMap::COL_IS_ACTIVE,
                true,
                Criteria::EQUAL
            )
            ->filterByIdProductCategory_In($productCategoryIds)
            ->orderByProductOrder();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productCategoryIds
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductCategoryByProductCategoryIds($productCategoryIds): SpyProductCategoryQuery
    {
        return $this->getFactory()
            ->getProductCategoryQueryContainer()
            ->queryProductCategoryMappings()
            ->filterByIdProductCategory_In($productCategoryIds);
    }

    /**
     * @api
     *
     * @param int $idNode
     * @param int $idLocale
     * @param bool $excludeRootNode
     * @param bool $onlyParents
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryPath($idNode, $idLocale, $excludeRootNode = true, $onlyParents = false)
    {
        return $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryPath($idNode, $idLocale, $excludeRootNode, $onlyParents);
    }

    /**
     * @api
     *
     * @param int $idCategoryNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlByIdCategoryNode($idCategoryNode, $idLocale)
    {
        return $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryUrlByIdCategoryNode($idCategoryNode)
            ->filterByFkLocale($idLocale);
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorageQuery
     */
    public function queryProductAbstractCategoryStorageByIds(array $productAbstractIds)
    {
        return $this
            ->getFactory()
            ->createSpyProductAbstractCategoryStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds);
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryProductAbstractLocalizedByIds(array $productAbstractIds)
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryAllProductAbstractLocalizedAttributes()
            ->joinWithLocale()
            ->filterByFkProductAbstract_In($productAbstractIds);
    }

    /**
     * @api
     *
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function queryAllCategoryIdsByNodeId($idNode)
    {
        return $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryClosureTableByNodeId($idNode)
            ->joinDescendantNode()
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_CATEGORY, static::FK_CATEGORY)
            ->select([static::FK_CATEGORY]);
    }

    /**
     * @api
     *
     * @param array $categoryIds
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductAbstractIdsByCategoryIds(array $categoryIds)
    {
        return $this->getFactory()
            ->getProductCategoryQueryContainer()
            ->queryProductCategoryMappings()
            ->filterByFkCategory_In($categoryIds)
            ->select(SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT);
    }

    /**
     * @api
     *
     * @param array $nodeIds
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryIdsByNodeIds(array $nodeIds)
    {
        return $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryAllCategoryNodes()
            ->filterByIdCategoryNode_In($nodeIds)
            ->select(SpyCategoryNodeTableMap::COL_FK_CATEGORY);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryAllCategoriesWithAttributesAndOrderByDescendant()
    {
        $nodeQuery = $this->getFactory()->getCategoryQueryContainer()->queryAllCategoryNodes();
        $nodeQuery->addJoin(
            SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE,
            SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE,
            Criteria::LEFT_JOIN
        );

        $nodeQuery->where(SpyUrlTableMap::COL_FK_LOCALE . ' = ' . SpyCategoryAttributeTableMap::COL_FK_LOCALE);

        $nodeQuery
            ->useClosureTableQuery()
            ->orderByFkCategoryNodeDescendant(Criteria::DESC)
            ->orderByDepth(Criteria::DESC)
            ->filterByDepth(null, Criteria::NOT_EQUAL)
            ->endUse()
            ->useCategoryQuery()
            ->useAttributeQuery()
            ->endUse()
            ->endUse();

        $nodeQuery->filterByIsRoot(false);

        $nodeQuery
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_CATEGORY, 'fk_category')
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, 'id_category_node')
            ->withColumn(SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE_DESCENDANT, 'fk_category_node_descendant')
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, 'name')
            ->withColumn(SpyCategoryTableMap::COL_CATEGORY_KEY, 'category_key')
            ->withColumn(SpyCategoryAttributeTableMap::COL_FK_LOCALE, 'fk_locale')
            ->withColumn(SpyUrlTableMap::COL_URL, 'url');

        $nodeQuery->setFormatter(new PropelArraySetFormatter());

        return $nodeQuery;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryAllCategoryNodes()
    {
        return $this->getFactory()->getCategoryQueryContainer()->queryAllCategoryNodes();
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductCategoryWithCategoryNodes(array $productAbstractIds)
    {
        return $this->getFactory()->getProductCategoryQueryContainer()
            ->queryProductCategoryMappings()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->innerJoinSpyCategory()
            ->addAnd(
                SpyCategoryTableMap::COL_IS_ACTIVE,
                true,
                Criteria::EQUAL
            )
            ->joinWithSpyCategory()
            ->joinWith('SpyCategory.Node')
            ->orderByProductOrder();
    }
}
