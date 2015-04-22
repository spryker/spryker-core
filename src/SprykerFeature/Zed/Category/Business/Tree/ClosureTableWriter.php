<?php

namespace SprykerFeature\Zed\Category\Business\Tree;

use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Shared\Category\Transfer\CategoryNode;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryClosureTableTableMap;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;

class ClosureTableWriter implements ClosureTableWriterInterface
{

    /**
     * @var CategoryQueryContainer
     */
    protected $queryContainer;

    /**
     * @var LocatorLocatorInterface|AutoCompletion
     */
    protected $locator;

    /**
     * @param LocatorLocatorInterface $locator
     * @param CategoryQueryContainer $categoryTreeRepository
     */
    public function __construct(
        LocatorLocatorInterface $locator,
        CategoryQueryContainer $categoryTreeRepository
    ) {
        $this->locator = $locator;
        $this->queryContainer = $categoryTreeRepository;
    }

    /**
     * @param CategoryNode $categoryNode
     */
    public function create(CategoryNode $categoryNode)
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
     * @param CategoryNode $categoryNode
     * @throws PropelException
     */
    protected function createRootNode(CategoryNode $categoryNode)
    {
        $nodeId = $categoryNode->getIdCategoryNode();

        $pathEntity = $this->locator->category()->entitySpyCategoryClosureTable();
        $pathEntity->setFkCategoryNode($nodeId);
        $pathEntity->setFkCategoryNodeDescendant($nodeId);
        $pathEntity->setDepth(0);

        $pathEntity->save();
    }

    /**
     * @param $nodeId
     * @param $parentId
     */
    protected function persistNode($nodeId, $parentId)
    {
        $connection = Propel::getConnection();
        $queryString = 'INSERT INTO %1$s (%2$s, %3$s, %4$s) ';
        $queryString .= 'SELECT %2$s, ?, (%4$s + 1) FROM %1$s WHERE %3$s = ? UNION ALL SELECT ?, ?, 0';
        $query = sprintf(
            $queryString,
            SpyCategoryClosureTableTableMap::TABLE_NAME,
            SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE,
            SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE_DESCENDANT,
            SpyCategoryClosureTableTableMap::COL_DEPTH
        );
        $statement = $connection->prepare((string)$query);
        $statement->execute([$nodeId, $parentId, $nodeId, $nodeId]);
    }
}
