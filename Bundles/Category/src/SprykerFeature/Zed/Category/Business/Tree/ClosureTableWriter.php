<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\NodeTransfer;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryNodeTableMap;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryClosureTable;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryClosureTableQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryNodeQuery;

class ClosureTableWriter implements ClosureTableWriterInterface
{

    /**
     * @var CategoryQueryContainer
     */
    protected $queryContainer;

    /**
     * @param CategoryQueryContainer $categoryTreeRepository
     */
    public function __construct(
        CategoryQueryContainer $categoryTreeRepository
    ) {
        $this->queryContainer = $categoryTreeRepository;
    }

    /**
     * @param NodeTransfer $categoryNode
     *
     * @return void
     */
    public function create(NodeTransfer $categoryNode)
    {
        $nodeId = $categoryNode->getIdCategoryNode();
        $parentId = $categoryNode->getFkParentCategoryNode();

        if (is_null($parentId)) {
            $this->createRootNode($categoryNode);
        } else {
            $this->persistNode($nodeId, $parentId);
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
            ->delete()
        ;
    }

    /**
     * @param NodeTransfer $categoryNode
     *
     * @throws PropelException
     *
     * @return void
     */
    public function moveNode(NodeTransfer $categoryNode)
    {
        $obsoleteEntities = $this->queryContainer
            ->queryClosureTableParentEntries($categoryNode->getIdCategoryNode())
            ->find()
        ;

        foreach ($obsoleteEntities as $obsoleteEntity) {
            $obsoleteEntity->delete();
        }

        $nodeEntities = $this->queryContainer
            ->queryClosureTableFilterByIdNode($categoryNode->getIdCategoryNode())
            ->find()
        ;

        $parentEntities = $this->queryContainer
            ->queryClosureTableFilterByIdNodeDescendant($categoryNode->getFkParentCategoryNode())
            ->find()
        ;

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
     * @param NodeTransfer $categoryNode
     *
     * @throws PropelException
     *
     * @return void
     */
    protected function createRootNode(NodeTransfer $categoryNode)
    {
        $nodeId = $categoryNode->getIdCategoryNode();

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
        $closureQuery= new SpyCategoryClosureTableQuery();
        $nodes = $closureQuery->findByFkCategoryNodeDescendant($parentId);

        foreach ($nodes as $node) {
            $entity = new SpyCategoryClosureTable();
            $entity->setFkCategoryNode($node->getFkCategoryNode());
            $entity->setFkCategoryNodeDescendant($nodeId);
            $entity->setDepth($node->getDepth() + 1);
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
     * @return void
     */
    public function rebuildCategoryNodes()
    {
        $connection = Propel::getConnection();

        echo '<pre>';

        $this->removeCircularRelations();

        $query = SpyCategoryNodeQuery::create();
        $query
            ->orderByFkParentCategoryNode()
            ->orderByNodeOrder('DESC')
        ;

        $categoryNodes = $query->find();

        foreach ($categoryNodes as $nodeEntity) {
            if ($nodeEntity->isRoot()) {
                continue;
            }

            echo 'Updated node closure table for: ' . $nodeEntity->getIdCategoryNode() . ', parent: ' . $nodeEntity->getFkParentCategoryNode() . "<br/>\n";

            $nodeToMove = (new NodeTransfer())->fromArray($nodeEntity->toArray());
            $this->delete($nodeToMove->getIdCategoryNode());
            $this->create($nodeToMove);
            $this->moveNode($nodeToMove);
        }

        echo 'Done updated ' . $categoryNodes->count() . " nodes.<br/>\n";

        echo '</pre>';

        $connection->commit();
    }

    /**
     * Fix problem when category node has its fk_parent_category_node set to itself
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
            ->endUse()
            ->orderByFkParentCategoryNode()
            ->orderByNodeOrder('DESC')
            ->where(SpyCategoryNodeTableMap::COL_FK_CATEGORY . ' = ' . SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE)
            ->_and()
            ->where(SpyCategoryNodeTableMap::COL_IS_ROOT . ' = false')
        ;

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
                ->endUse()
                ->orderByFkParentCategoryNode()
                ->orderByNodeOrder('DESC')
                ->where(SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE . ' = ?', $entityToMoveToRoot->getIdCategoryNode())
                ->_and()
                ->where(SpyCategoryNodeTableMap::COL_IS_ROOT . ' = false')
            ;

            $badChildrenToRemove = $query->find();
            foreach ($badChildrenToRemove as $badChild) {
                $this->delete($badChild->getIdCategoryNode());
                $badChild->delete();
            }

            $entityToMoveToRoot->delete();
        }
    }

}
