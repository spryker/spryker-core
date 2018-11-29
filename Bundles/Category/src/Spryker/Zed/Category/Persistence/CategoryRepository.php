<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
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
    protected const COL_CATEGORY_NAME = 'name';

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getAllCategoryCollection(LocaleTransfer $localeTransfer): CategoryCollectionTransfer
    {
        $spyCategories = SpyCategoryQuery::create()
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
        return $this->getNodePathBase($idCategoryNode, $localeTransfer, static::NODE_PATH_GLUE, !static::EXCLUDE_NODE_PATH_ROOT, static::NODE_PATH_ZERO_DEPTH);
    }

    /**
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $glue
     * @param bool $excludeRoot
     * @param int|null $depth
     *
     * @return string
     */
    public function getCategoryNodePath(
        int $idNode,
        LocaleTransfer $localeTransfer,
        string $glue = self::CATEGORY_NODE_PATH_GLUE,
        bool $excludeRoot = self::EXCLUDE_NODE_PATH_ROOT,
        ?int $depth = self::NODE_PATH_NULL_DEPTH
    ): string {

        return $this->getNodePathBase($idNode, $localeTransfer, $glue, $excludeRoot, $depth);
    }

    /**
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $glue
     * @param bool $excludeRoot
     * @param int|null $depth
     *
     * @return string
     */
    protected function getNodePathBase(
        int $idNode,
        LocaleTransfer $localeTransfer,
        string $glue = self::CATEGORY_NODE_PATH_GLUE,
        bool $excludeRoot = self::EXCLUDE_NODE_PATH_ROOT,
        ?int $depth = self::NODE_PATH_NULL_DEPTH
    ): string {
        /** @var \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery $nodePathQuery */
        $nodePathQuery = $this->queryNodePath($idNode, $localeTransfer->getIdLocale(), $excludeRoot, $depth)
            ->clearSelectColumns()
            ->addSelectColumn(static::COL_CATEGORY_NAME);

        /** @var string[] $pathTokens */
        $pathTokens = $nodePathQuery->find();

        return implode($glue, $pathTokens);
    }

    /**
     * @param int $idNode
     * @param int $idLocale
     * @param bool $excludeRootNode
     * @param int|null $depth
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    protected function queryNodePath(
        int $idNode,
        int $idLocale,
        bool $excludeRootNode = self::EXCLUDE_NODE_PATH_ROOT,
        ?int $depth = self::NODE_PATH_NULL_DEPTH
    ): SpyCategoryNodeQuery {
        $nodeQuery = $this->getFactory()->createCategoryNodeQuery();

        if ($excludeRootNode) {
            $nodeQuery->filterByIsRoot(static::IS_ROOT_NODE);
        }

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
            ->useNodeQuery(null, Criteria::LEFT_JOIN)
                ->filterByFkCategory($idCategory)
            ->endUse()
            ->leftJoinWithAttribute()
            ->useAttributeQuery(null, Criteria::LEFT_JOIN)
                ->filterByFkCategory($idCategory)
            ->endUse()
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
}
