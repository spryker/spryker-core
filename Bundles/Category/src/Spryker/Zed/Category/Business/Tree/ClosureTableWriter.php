<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\SpyCategoryClosureTable;
use Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Propel\Runtime\Propel;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class ClosureTableWriter implements ClosureTableWriterInterface
{
    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $categoryTreeRepository
     */
    public function __construct(CategoryQueryContainerInterface $categoryTreeRepository)
    {
        $this->queryContainer = $categoryTreeRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     *
     * @return void
     */
    public function create(NodeTransfer $categoryNodeTransfer)
    {
        $idCategoryNode = $categoryNodeTransfer->getIdCategoryNodeOrFail();
        $parentId = $categoryNodeTransfer->getFkParentCategoryNode();

        if ($parentId === null) {
            $this->createRootNode($categoryNodeTransfer);
        } else {
            $this->persistNode($idCategoryNode, $parentId);
        }
    }

    /**
     * @param int $nodeId
     *
     * @return int
     */
    public function delete($nodeId)
    {
        return $this->queryContainer
            ->queryClosureTableByNodeId($nodeId)
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     *
     * @return void
     */
    public function moveNode(NodeTransfer $categoryNodeTransfer)
    {
        $obsoleteEntities = $this->queryContainer
            ->queryClosureTableParentEntries($categoryNodeTransfer->getIdCategoryNodeOrFail())
            ->find();

        foreach ($obsoleteEntities as $obsoleteEntity) {
            $obsoleteEntity->delete();
        }

        $nodeEntities = $this->queryContainer
            ->queryClosureTableFilterByIdNode($categoryNodeTransfer->getIdCategoryNodeOrFail())
            ->find();

        $parentEntities = $this->queryContainer
            ->queryClosureTableFilterByIdNodeDescendant($categoryNodeTransfer->getFkParentCategoryNodeOrFail())
            ->find();

        foreach ($nodeEntities as $nodeEntity) {
            foreach ($parentEntities as $parentEntity) {
                $depth = $nodeEntity->getDepth() + $parentEntity->getDepth() + 1;

                $closureTableEntity = new SpyCategoryClosureTable();
                $closureTableEntity->setFkCategoryNode($parentEntity->getFkCategoryNode());
                $closureTableEntity->setFkCategoryNodeDescendant($nodeEntity->getFkCategoryNodeDescendant());
                $closureTableEntity->setDepth($depth);

                $closureTableEntity->save();
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNode
     *
     * @return void
     */
    protected function createRootNode(NodeTransfer $categoryNode)
    {
        $nodeId = $categoryNode->getIdCategoryNodeOrFail();

        $pathEntity = new SpyCategoryClosureTable();
        $pathEntity->setFkCategoryNode($nodeId);
        $pathEntity->setFkCategoryNodeDescendant($nodeId);
        $pathEntity->setDepth(0);

        $pathEntity->save();
    }

    /**
     * @param int $nodeId
     * @param int $parentId
     *
     * @return void
     */
    protected function persistNode($nodeId, $parentId)
    {
        $categoryClosureQuery = new SpyCategoryClosureTableQuery();
        $categoryClosureEntityCollection = $categoryClosureQuery->findByFkCategoryNodeDescendant($parentId);

        foreach ($categoryClosureEntityCollection as $categoryClosureEntity) {
            $entity = new SpyCategoryClosureTable();
            $entity->setFkCategoryNode($categoryClosureEntity->getFkCategoryNode());
            $entity->setFkCategoryNodeDescendant($nodeId);

            $entity->setDepth($categoryClosureEntity->getDepth() + 1);
            $entity->save();
        }

        $entity = new SpyCategoryClosureTable();
        $entity->setFkCategoryNode($nodeId);
        $entity->setFkCategoryNodeDescendant($nodeId);
        $entity->setDepth(0);
        $entity->save();
    }

    /**
     * Quick fix to regenerate broken closure table based on category node table
     *
     * @todo https://spryker.atlassian.net/browse/CD-575
     *
     * @return void
     */
    public function rebuildCategoryNodes()
    {
        $connection = Propel::getConnection();

        $this->removeCircularRelations();

        $query = SpyCategoryNodeQuery::create();
        $query
            ->orderByFkParentCategoryNode()
            ->orderByNodeOrder('DESC');

        $categoryNodes = $query->find();

        foreach ($categoryNodes as $nodeEntity) {
            if ($nodeEntity->isRoot()) {
                continue;
            }

            $nodeToMove = (new NodeTransfer())->fromArray($nodeEntity->toArray());
            $this->delete($nodeToMove->getIdCategoryNodeOrFail());
            $this->create($nodeToMove);
            $this->moveNode($nodeToMove);
        }

        $connection->commit();
    }

    /**
     * Quick fix for problem when category node has its fk_parent_category_node set to itself
     *
     * @todo https://spryker.atlassian.net/browse/CD-575
     *
     * @return void
     */
    protected function removeCircularRelations()
    {
        $query = SpyCategoryNodeQuery::create();
        $query
            ->innerJoinCategory()
            ->useCategoryQuery()
                ->innerJoinAttribute()
            ->endUse();

        $query
            ->orderByFkParentCategoryNode()
            ->orderByNodeOrder('DESC')
            ->where(SpyCategoryNodeTableMap::COL_FK_CATEGORY . ' = ' . SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE)
            ->_and()
            ->where(SpyCategoryNodeTableMap::COL_IS_ROOT . ' = false');

        $badNodes = $query->find();
        foreach ($badNodes as $entityToMoveToRoot) {
            if ($entityToMoveToRoot->isRoot()) {
                continue;
            }

            printf(
                "Removing circular referenced node: %s from [%d]<br/>\n",
                $entityToMoveToRoot->getCategory()->getAttributes()->getFirst()->getName(),
                $entityToMoveToRoot->getFkParentCategoryNode()
            );

            $this->delete($entityToMoveToRoot->getIdCategoryNode());

            $query = SpyCategoryNodeQuery::create();
            $query
                ->innerJoinCategory()
                ->useCategoryQuery()
                    ->innerJoinAttribute()
                ->endUse();

            $query
                ->orderByFkParentCategoryNode()
                ->orderByNodeOrder('DESC')
                ->where(SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE . ' = ?', $entityToMoveToRoot->getIdCategoryNode())
                ->_and()
                ->where(SpyCategoryNodeTableMap::COL_IS_ROOT . ' = false');

            $badChildrenToRemove = $query->find();
            foreach ($badChildrenToRemove as $badChild) {
                $this->delete($badChild->getIdCategoryNode());
                $badChild->delete();
            }

            $entityToMoveToRoot->delete();
        }
    }
}
