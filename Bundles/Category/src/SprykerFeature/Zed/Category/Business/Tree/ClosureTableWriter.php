<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\NodeTransfer;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryClosureTableTableMap;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryClosureTable;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryClosureTableQuery;

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
     */
    public function delete($nodeId)
    {
        $this->queryContainer
            ->queryClosureTableByNodeId($nodeId)
            ->delete()
        ;
    }

    /**
     * @param NodeTransfer $categoryNode
     *
     * @throws PropelException
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
     */
    protected function persistNode($nodeId, $parentId)
    {
        $closureQuery= new SpyCategoryClosureTableQuery();
        $nodes = $closureQuery->findByFkCategoryNodeDescendant($parentId);

        foreach($nodes as $node) {
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

}
