<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Shared\Category\CategoryConstants;
use Spryker\Zed\Category\Business\Manager\NodeUrlManagerInterface;
use Spryker\Zed\Category\Dependency\Facade\CategoryToTouchInterface;

class CategoryTreeWriter
{

    /**
     * @var \Spryker\Zed\Category\Business\Tree\NodeWriterInterface
     */
    protected $nodeWriter;

    /**
     * @var \Spryker\Zed\Category\Business\Tree\ClosureTableWriterInterface
     */
    protected $closureTableWriter;

    /**
     * @var \Spryker\Zed\Category\Business\Tree\CategoryTreeReaderInterface
     */
    protected $categoryTreeReader;

    /**
     * @var \Spryker\Zed\Category\Business\Manager\NodeUrlManagerInterface
     */
    protected $nodeUrlManager;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * @param \Spryker\Zed\Category\Business\Tree\NodeWriterInterface $nodeWriter
     * @param \Spryker\Zed\Category\Business\Tree\ClosureTableWriterInterface $closureTableWriter
     * @param \Spryker\Zed\Category\Business\Tree\CategoryTreeReaderInterface $categoryTreeReader
     * @param \Spryker\Zed\Category\Business\Manager\NodeUrlManagerInterface $nodeUrlManager
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToTouchInterface $touchFacade
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
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
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
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
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
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
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param bool $deleteChildren
     *
     * @return int
     */
    public function deleteNode($idNode, LocaleTransfer $locale, $deleteChildren = false)
    {
        $this->connection->beginTransaction();

        // Order of execution matters, these must be called before node is deleted
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
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNode
     *
     * @return void
     */
    protected function touchCategoryActiveRecursive(NodeTransfer $categoryNode)
    {
        $closureQuery = new SpyCategoryClosureTableQuery();
        $nodes = $closureQuery->findByFkCategoryNode($categoryNode->getIdCategoryNode());

        foreach ($nodes as $node) {
            $this->touchCategoryActive($node->getFkCategoryNodeDescendant());
        }

        $this->touchCategoryActive($categoryNode->getIdCategoryNode());
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNode
     *
     * @return void
     */
    protected function touchCategoryDeletedRecursive(NodeTransfer $categoryNode)
    {
        $closureQuery = new SpyCategoryClosureTableQuery();
        $nodes = $closureQuery->findByFkCategoryNode($categoryNode->getIdCategoryNode());

        foreach ($nodes as $node) {
            $this->touchCategoryDeleted($node->getFkCategoryNodeDescendant());
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
        $this->touchFacade->touchActive(CategoryConstants::RESOURCE_TYPE_CATEGORY_NODE, $idCategoryNode);
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    protected function touchCategoryDeleted($idCategoryNode)
    {
        $this->touchFacade->touchDeleted(CategoryConstants::RESOURCE_TYPE_CATEGORY_NODE, $idCategoryNode);
    }

    /**
     * @return void
     */
    protected function touchNavigationActive()
    {
        $navigationItems = $this->touchFacade->getItemsByType(CategoryConstants::RESOURCE_TYPE_NAVIGATION);

        $itemIds = [];
        foreach ($navigationItems as $touchTransfer) {
            $itemIds[] = $touchTransfer->getItemId();
        }

        $this->touchFacade->bulkTouchActive(CategoryConstants::RESOURCE_TYPE_NAVIGATION, $itemIds);
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
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return void
     */
    protected function removeNodeUrl($idCategoryNode, LocaleTransfer $locale)
    {
        $nodeEntity = $this->categoryTreeReader->getNodeById($idCategoryNode);
        if (!$nodeEntity) {
            return;
        }

        $nodeTransfer = (new NodeTransfer())
            ->fromArray($nodeEntity->toArray());

        $this->nodeUrlManager->removeUrl($nodeTransfer, $locale);
    }

}
