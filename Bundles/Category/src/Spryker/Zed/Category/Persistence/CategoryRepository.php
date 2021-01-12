<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryNodeUrlFilterTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\CategoryUrlPathCriteriaTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryClosureTableTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Category\Persistence\CategoryPersistenceFactory getFactory()
 */
class CategoryRepository extends AbstractRepository implements CategoryRepositoryInterface
{
    protected const KEY_FK_CATEGORY = 'fk_category';
    protected const KEY_ID_CATEGORY_NODE = 'id_category_node';
    protected const KEY_FK_CATEGORY_NODE_DESCENDANT = 'fk_category_node_descendant';
    protected const KEY_NAME = 'name';
    protected const KEY_CATEGORY_KEY = 'category_key';
    protected const COL_FK_LOCALE = 'fk_locale';

    public const NODE_PATH_GLUE = '/';
    public const CATEGORY_NODE_PATH_GLUE = ' / ';
    public const EXCLUDE_NODE_PATH_ROOT = true;
    public const NODE_PATH_NULL_DEPTH = null;
    public const NODE_PATH_ZERO_DEPTH = 0;
    public const IS_NOT_ROOT_NODE = 0;
    protected const COL_CATEGORY_NAME = 'name';

    /**
     * @uses \Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap::COL_LOCALE_NAME
     */
    protected const COL_LOCALE_NAME = 'spy_locale.locale_name';

    protected const DEPTH_WITH_CHILDREN_RELATIONS = 1;

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getAllCategoryCollection(LocaleTransfer $localeTransfer): CategoryCollectionTransfer
    {
        $categoryQuery = SpyCategoryQuery::create();
        $spyCategories = $categoryQuery
            ->joinWithAttribute()
            ->leftJoinNode()
            ->addAnd(
                SpyCategoryAttributeTableMap::COL_FK_LOCALE,
                $localeTransfer->getIdLocale(),
                Criteria::EQUAL
            )
            ->find();

        return $this->getFactory()
            ->createCategoryMapper()
            ->mapCategoryCollection($spyCategories, new CategoryCollectionTransfer());
    }

    /**
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getNodePath(int $idCategoryNode, LocaleTransfer $localeTransfer)
    {
        $nodePathQuery = $this->queryNodePathWithRootNode(
            $idCategoryNode,
            $localeTransfer->getIdLocale(),
            static::NODE_PATH_ZERO_DEPTH
        );

        return $this->generateNodePathString($nodePathQuery, static::NODE_PATH_GLUE);
    }

    /**
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getCategoryNodePath(int $idNode, LocaleTransfer $localeTransfer): string
    {
        $nodePathQuery = $this->queryNodePathWithoutRootNode(
            $idNode,
            $localeTransfer->getIdLocale(),
            static::NODE_PATH_NULL_DEPTH
        );

        return $this->generateNodePathString($nodePathQuery, static::CATEGORY_NODE_PATH_GLUE);
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery $nodePathQuery
     * @param string $glue
     *
     * @return string
     */
    protected function generateNodePathString(SpyCategoryNodeQuery $nodePathQuery, string $glue): string
    {
        $nodePathQuery = $nodePathQuery
            ->clearSelectColumns()
            ->addSelectColumn(static::COL_CATEGORY_NAME);

        /** @var string[] $pathTokens */
        $pathTokens = $nodePathQuery->find();

        return implode($glue, $pathTokens);
    }

    /**
     * @param int $idNode
     * @param int $idLocale
     * @param int|null $depth
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    protected function queryNodePathWithRootNode(
        int $idNode,
        int $idLocale,
        ?int $depth = self::NODE_PATH_NULL_DEPTH
    ): SpyCategoryNodeQuery {
        $categoryNodeQuery = $this->getFactory()->createCategoryNodeQuery();
        $categoryNodeQuery
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
            ->endUse()
            ->setFormatter(new PropelArraySetFormatter());

        return $categoryNodeQuery;
    }

    /**
     * @param int $idNode
     * @param int $idLocale
     * @param int|null $depth
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    protected function queryNodePathWithoutRootNode(
        int $idNode,
        int $idLocale,
        ?int $depth = self::NODE_PATH_NULL_DEPTH
    ): SpyCategoryNodeQuery {
        return $this->queryNodePathWithRootNode($idNode, $idLocale, $depth)
            ->filterByIsRoot(static::IS_NOT_ROOT_NODE);
    }

    /**
     * @param string $nodeName
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return bool
     */
    public function checkSameLevelCategoryByNameExists(string $nodeName, CategoryTransfer $categoryTransfer): bool
    {
        return $this->getFactory()->createCategoryNodeQuery()
            ->setIgnoreCase(true)
            ->filterByFkParentCategoryNode($categoryTransfer->getParentCategoryNode()->getIdCategoryNode())
            ->useCategoryQuery()
                ->filterByIdCategory($categoryTransfer->getIdCategory(), Criteria::NOT_EQUAL)
                ->useAttributeQuery()
                    ->filterByName($nodeName)
                ->endUse()
            ->endUse()
            ->exists();
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function findCategoryById(int $idCategory): ?CategoryTransfer
    {
        $spyCategoryEntity = $this->getFactory()
            ->createCategoryQuery()
            ->leftJoinWithNode()
            ->leftJoinWithAttribute()
            ->findByIdCategory($idCategory)
            ->getFirst();

        if ($spyCategoryEntity === null) {
            return null;
        }

        return $this->getFactory()->createCategoryMapper()->mapCategoryWithRelations(
            $spyCategoryEntity,
            new CategoryTransfer()
        );
    }

    /**
     * @param int $idCategoryNode
     *
     * @return int[]
     */
    public function getChildCategoryNodeIdsByCategoryNodeId(int $idCategoryNode): array
    {
        return $this->getFactory()
            ->createCategoryClosureTableQuery()
            ->select(SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE_DESCENDANT)
            ->findByFkCategoryNode($idCategoryNode)
            ->getData();
    }

    /**
     * @param int $idCategoryNode
     *
     * @return int[]
     */
    public function getParentCategoryNodeIdsByCategoryNodeId(int $idCategoryNode): array
    {
        return $this->getFactory()
            ->createCategoryClosureTableQuery()
            ->select(SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE)
            ->findByFkCategoryNodeDescendant($idCategoryNode)
            ->getData();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryCriteriaTransfer $categoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function findCategoryByCriteria(CategoryCriteriaTransfer $categoryCriteriaTransfer): ?CategoryTransfer
    {
        $categoryQuery = $this->getFactory()->createCategoryQuery();
        $categoryQuery = $this->applyCategoryFilters($categoryQuery, $categoryCriteriaTransfer);

        $categoryEntity = $categoryQuery->leftJoinWithAttribute()->find()->getFirst();
        if ($categoryEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createCategoryMapper()
            ->mapCategoryWithRelations($categoryEntity, new CategoryTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\CategoryCriteriaTransfer $categoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[][]
     */
    public function getCategoryNodeChildNodesCollectionIndexedByParentNodeId(
        CategoryTransfer $categoryTransfer,
        CategoryCriteriaTransfer $categoryCriteriaTransfer
    ): array {
        $categoryClosureTableQuery = $this->getFactory()
            ->createCategoryClosureTableQuery()
            ->leftJoinWithDescendantNode()
            ->useNodeQuery('node')
                ->filterByFkCategory($categoryTransfer->getIdCategoryOrFail())
            ->endUse()
            ->useDescendantNodeQuery()
                ->leftJoinWithCategory()
                ->orderByNodeOrder(Criteria::DESC)
            ->endUse();

        $this->applyCategoryClosureTableFilters($categoryClosureTableQuery, $categoryCriteriaTransfer);

        $categoryClosureTableEntities = $categoryClosureTableQuery->find();

        if (!$categoryClosureTableEntities->count()) {
            return [];
        }

        $categoryMapper = $this->getFactory()->createCategoryMapper();
        $categoryNodes = [];
        foreach ($categoryClosureTableEntities as $categoryClosureTable) {
            $nodeTransfer = $categoryMapper->mapCategoryNodeEntityToNodeTransferWithCategoryRelation(
                $categoryClosureTable->getDescendantNode(),
                new NodeTransfer()
            );
            $categoryNodes[$nodeTransfer->getFkParentCategoryNode()][] = $nodeTransfer;
        }

        return $categoryNodes;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeUrlFilterTransfer $categoryNodeFilterTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer[]
     */
    public function getCategoryNodeUrls(CategoryNodeUrlFilterTransfer $categoryNodeFilterTransfer): array
    {
        $urlQuery = $this->getFactory()
            ->createUrlQuery()
            ->joinSpyLocale()
            ->withColumn(static::COL_LOCALE_NAME);

        if ($categoryNodeFilterTransfer->getCategoryNodeIds()) {
            $urlQuery->filterByFkResourceCategorynode_In(array_unique($categoryNodeFilterTransfer->getCategoryNodeIds()));
        }

        $urlTransfers = [];

        foreach ($urlQuery->find() as $urlEntity) {
            $urlTransfers[] = (new UrlTransfer())->fromArray($urlEntity->toArray(), true);
        }

        return $urlTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryUrlPathCriteriaTransfer $categoryUrlPathCriteriaTransfer
     *
     * @return array
     */
    public function getCategoryUrlPathParts(CategoryUrlPathCriteriaTransfer $categoryUrlPathCriteriaTransfer): array
    {
        $depth = $categoryUrlPathCriteriaTransfer->getOnlyParents() ? 0 : null;

        $nodeQuery = $this->getFactory()->createCategoryNodeQuery();
        if ($categoryUrlPathCriteriaTransfer->getExcludeRootNode()) {
            $nodeQuery->filterByIsRoot(false);
        }

        $nodeQuery
            ->useClosureTableQuery()
                ->orderByFkCategoryNodeDescendant(Criteria::DESC)
                ->orderByDepth(Criteria::DESC)
                ->filterByFkCategoryNodeDescendant($categoryUrlPathCriteriaTransfer->getIdCategoryNodeOrFail())
                ->filterByDepth($depth, Criteria::NOT_EQUAL)
            ->endUse()
            ->useCategoryQuery()
                ->useAttributeQuery()
                    ->filterByFkLocale($categoryUrlPathCriteriaTransfer->getIdLocaleOrFail())
                ->endUse()
            ->endUse()
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_CATEGORY, static::KEY_FK_CATEGORY)
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, static::KEY_ID_CATEGORY_NODE)
            ->withColumn(SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE_DESCENDANT, static::KEY_FK_CATEGORY_NODE_DESCENDANT)
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, static::KEY_NAME)
            ->withColumn(SpyCategoryTableMap::COL_CATEGORY_KEY, static::KEY_CATEGORY_KEY)
            ->withColumn(SpyCategoryAttributeTableMap::COL_FK_LOCALE, static::COL_FK_LOCALE);

        return $nodeQuery->find()->toArray();
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryQuery $categoryQuery
     * @param \Generated\Shared\Transfer\CategoryCriteriaTransfer $categoryCriteriaTransfer
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    protected function applyCategoryFilters(SpyCategoryQuery $categoryQuery, CategoryCriteriaTransfer $categoryCriteriaTransfer): SpyCategoryQuery
    {
        if ($categoryCriteriaTransfer->getIdCategory()) {
            $categoryQuery->filterByIdCategory($categoryCriteriaTransfer->getIdCategory());
        }

        if ($categoryCriteriaTransfer->getIsMain() !== null) {
            $categoryQuery
                ->useNodeQuery('node', Criteria::LEFT_JOIN)
                    ->filterByIsMain($categoryCriteriaTransfer->getIsMain())
                ->endUse();
        }

        if ($categoryCriteriaTransfer->getLocaleName()) {
            $categoryQuery
                ->useAttributeQuery(null, Criteria::LEFT_JOIN)
                    ->useLocaleQuery()
                        ->filterByLocaleName($categoryCriteriaTransfer->getLocaleName())
                    ->endUse()
                ->endUse();
        }

        return $categoryQuery;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery $categoryClosureTableQuery
     * @param \Generated\Shared\Transfer\CategoryCriteriaTransfer $categoryCriteriaTransfer
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    protected function applyCategoryClosureTableFilters(
        SpyCategoryClosureTableQuery $categoryClosureTableQuery,
        CategoryCriteriaTransfer $categoryCriteriaTransfer
    ): SpyCategoryClosureTableQuery {
        if ($categoryCriteriaTransfer->getLocaleName()) {
            $categoryClosureTableQuery
                ->useDescendantNodeQuery()
                    ->useCategoryQuery()
                        ->joinWithAttribute()
                        ->useAttributeQuery()
                            ->useLocaleQuery()
                                ->filterByLocaleName($categoryCriteriaTransfer->getLocaleName())
                            ->endUse()
                        ->endUse()
                    ->endUse()
                ->endUse();
        }

        if ($categoryCriteriaTransfer->getWithChildren()) {
            $categoryClosureTableQuery->filterByDepth(static::DEPTH_WITH_CHILDREN_RELATIONS);
        }

        return $categoryClosureTableQuery;
    }
}
