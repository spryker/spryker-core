<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\Connection\ConnectionInterface;
use SprykerFeature\Shared\Category\CategoryConfig;
use SprykerFeature\Zed\Category\Business\Manager\NodeUrlManagerInterface;
use SprykerFeature\Zed\Category\Dependency\Facade\CategoryToTouchInterface;
use Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery;

class CategoryTreeWriter
{

    /**
     * @var NodeWriterInterface
     */
    protected $nodeWriter;

    /**
     * @var ClosureTableWriterInterface
     */
    protected $closureTableWriter;

    /**
     * @var CategoryTreeReaderInterface
     */
    protected $categoryTreeReader;

    /**
     * @var NodeUrlManagerInterface
     */
    protected $nodeUrlManager;

    /**
     * @var CategoryToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @param NodeWriterInterface $nodeWriter
     * @param ClosureTableWriterInterface $closureTableWriter
     * @param CategoryTreeReaderInterface $categoryTreeReader
     * @param NodeUrlManagerInterface $nodeUrlManager
     * @param CategoryToTouchInterface $touchFacade
     * @param ConnectionInterface $connection
     */
    public function __construct(
        NodeWriterInterface $nodeWriter,
        ClosureTableWriterInterface $closureTableWriter,
        CategoryTreeReaderInterface $categoryTreeReader,
        NodeUrlManagerInterface $nodeUrlManager,
        CategoryToTouchInterface  $touchFacade,
        ConnectionInterface $connection
    ) {
        $this->nodeWriter = $nodeWriter;
        $this->closureTableWriter = $closureTableWriter;
        $this->categoryTreeReader = $categoryTreeReader;
        $this->nodeUrlManager = $nodeUrlManager;
        $this->touchFacade = $touchFacade;
        $this->connection = $connection;
    }

    /**
     * @param NodeTransfer $categoryNode
     * @param LocaleTransfer $locale
     * @param bool $createUrlPath
     *
     * @return int
     */
    public function createCategoryNode(
        NodeTransfer $categoryNode,
        LocaleTransfer $locale,
        $createUrlPath = true
    ) {
        $this->connection->beginTransaction();

        $idNode = $this->nodeWriter->create($categoryNode);
        $this->closureTableWriter->create($categoryNode);

        $this->touchNavigationActive();

        $this->touchCategoryActiveRecursive($categoryNode);

        if ($createUrlPath) {
            $this->nodeUrlManager->createUrl($categoryNode, $locale);
        }

        $this->connection->commit();

        return $idNode;
    }

    /**
     * @param NodeTransfer $categoryNodeTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function updateNode(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer)
    {
        $this->connection->beginTransaction();

        $this->nodeWriter->update($categoryNodeTransfer);
        $this->closureTableWriter->moveNode($categoryNodeTransfer);
        $this->nodeUrlManager->updateUrl($categoryNodeTransfer, $localeTransfer);

        $this->touchCategoryActiveRecursive($categoryNodeTransfer);
        $this->touchNavigationActive();

        $this->connection->commit();
    }

    /**
     * @param int $idNode
     * @param LocaleTransfer $locale
     * @param bool $deleteChildren
     *
     * @return int
     */
    public function deleteNode($idNode, LocaleTransfer $locale, $deleteChildren = false)
    {
        $this->connection->beginTransaction();

        //order of execution matters, these must be called before node is deleted
        $this->removeNodeUrl($idNode, $locale);
        $this->touchCategoryDeleted($idNode);

        $hasChildren = $this->categoryTreeReader->hasChildren($idNode);

        if ($deleteChildren && $hasChildren) {
            $childNodes = $this->categoryTreeReader->getChildren($idNode, $locale);
            foreach ($childNodes as $childNode) {
                $this->deleteNode($childNode->getIdCategoryNode(), $locale, true);
            }
        }

        $result = $this->closureTableWriter->delete($idNode);

        $hasChildren = $this->categoryTreeReader->hasChildren($idNode);
        if (!$hasChildren) {
            $result = $this->nodeWriter->delete($idNode);
        }

        $this->touchNavigationUpdated();

        $this->connection->commit();

        return $result;
    }

    /**
     * @return void
     */
    public function rebuildClosureTable()
    {
        $this->closureTableWriter->rebuildCategoryNodes();
    }

    /**
     * @param NodeTransfer $categoryNode
     *
     * @return void
     */
    protected function touchCategoryActiveRecursive(NodeTransfer $categoryNode)
    {
        $closureQuery= new SpyCategoryClosureTableQuery();
        $nodes = $closureQuery->findByFkCategoryNodeDescendant($categoryNode->getFkParentCategoryNode());

        foreach ($nodes as $node) {
            $this->touchCategoryActive($node->getFkCategoryNode());
        }

        $this->touchCategoryActive($categoryNode->getIdCategoryNode());
    }

    /**
     * @param NodeTransfer $categoryNode
     *
     * @return void
     */
    protected function touchCategoryDeletedRecursive(NodeTransfer $categoryNode)
    {
        $closureQuery= new SpyCategoryClosureTableQuery();
        $nodes = $closureQuery->findByFkCategoryNodeDescendant($categoryNode->getFkParentCategoryNode());

        foreach ($nodes as $node) {
            $this->touchCategoryDeleted($node->getFkCategoryNode());
        }

        $this->touchCategoryDeleted($categoryNode->getIdCategoryNode());
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    protected function touchCategoryActive($idCategoryNode)
    {
        $this->touchFacade->touchActive(CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE, $idCategoryNode);
    }

    /**
     * @param $idCategoryNode
     *
     * @return void
     */
    protected function touchCategoryDeleted($idCategoryNode)
    {
        $this->touchFacade->touchDeleted(CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE, $idCategoryNode);
    }

    /**
     * @return void
     */
    protected function touchNavigationActive()
    {
        $navigationItems = $this->touchFacade->getItemsByType(CategoryConfig::RESOURCE_TYPE_NAVIGATION);

        $itemIds = array_keys($navigationItems);

        $this->touchFacade->bulkTouchActive(CategoryConfig::RESOURCE_TYPE_NAVIGATION, $itemIds);
    }

    /**
     * @return void
     */
    protected function touchNavigationUpdated()
    {
        $this->touchNavigationActive();
    }

    /**
     * @param int $idCategoryNode
     * @param LocaleTransfer $locale
     *
     * @return void
     */
    protected function removeNodeUrl($idCategoryNode, LocaleTransfer $locale)
    {
        $node = $this->categoryTreeReader->getNodeById($idCategoryNode);
        $nodeTransfer = (new NodeTransfer())
            ->fromArray($node->toArray());

        $this->nodeUrlManager->removeUrl($nodeTransfer, $locale);
    }

}
