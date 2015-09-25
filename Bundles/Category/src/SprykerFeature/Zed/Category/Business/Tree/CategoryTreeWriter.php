<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\Propel;
use SprykerFeature\Shared\Category\CategoryConfig;
use SprykerFeature\Zed\Category\Business\Manager\NodeUrlManagerInterface;
use SprykerFeature\Zed\Category\Business\Model\CategoryWriterInterface;
use SprykerFeature\Zed\Category\Dependency\Facade\CategoryToTouchInterface;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryClosureTableQuery;

class CategoryTreeWriter
{

    const ID_NAVIGATION = 1;

    /**
     * @var CategoryWriterInterface
     */
    protected $categoryWriter;

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
     * @param CategoryWriterInterface $categoryWriter
     * @param NodeWriterInterface $nodeWriter
     * @param ClosureTableWriterInterface $closureTableWriter
     * @param CategoryTreeReaderInterface $categoryTreeReader
     * @param NodeUrlManagerInterface $nodeUrlManager
     * @param CategoryToTouchInterface $touchFacade
     */
    public function __construct(
        CategoryWriterInterface $categoryWriter,
        NodeWriterInterface $nodeWriter,
        ClosureTableWriterInterface $closureTableWriter,
        CategoryTreeReaderInterface $categoryTreeReader,
        NodeUrlManagerInterface $nodeUrlManager,
        CategoryToTouchInterface  $touchFacade
    ) {
        $this->categoryWriter = $categoryWriter;
        $this->nodeWriter = $nodeWriter;
        $this->closureTableWriter = $closureTableWriter;
        $this->categoryTreeReader = $categoryTreeReader;
        $this->nodeUrlManager = $nodeUrlManager;
        $this->touchFacade = $touchFacade;
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
        $connection = Propel::getConnection();
        $connection->beginTransaction();

        $idNode = $this->nodeWriter->create($categoryNode);
        $this->closureTableWriter->create($categoryNode);
        
        $this->touchNavigationActive();

        $this->touchCategoryActiveRecursive($categoryNode);
        
        if ($createUrlPath) {
            $this->nodeUrlManager->createUrl($categoryNode, $locale);
        }

        $connection->commit();

        return $idNode;
    }

    /**
     * @param int $idNode
     * @param LocaleTransfer $locale
     *
     * @return bool
     */
    public function deleteCategoryByNodeId($idNode, LocaleTransfer $locale)
    {
        $connection = Propel::getConnection();
        $connection->beginTransaction();

        //order of execution matters, this must be called before node is deleted
        $this->touchUrlDeleted($idNode, $locale);

        $nodeEntity = $this->categoryTreeReader->getNodeById($idNode);
        $idCategory = $nodeEntity->getFkCategory();
        $categoryNodes = $this->categoryTreeReader->getAllNodesByIdCategory($idCategory);

        foreach ($categoryNodes as $node) {
            $this->deleteNode($node->getPrimaryKey(), $locale, true);
        }
        $this->categoryWriter->delete($idCategory);
        
        $this->touchCategoryDeleted($idNode);

        $connection->commit();

        return true;
    }

    /**
     * @param NodeTransfer $categoryNode
     * @param LocaleTransfer $locale
     */
    public function updateNode(NodeTransfer $categoryNode, LocaleTransfer $locale)
    {
        $connection = Propel::getConnection();
        $connection->beginTransaction();

        $this->nodeWriter->update($categoryNode);
        $this->closureTableWriter->moveNode($categoryNode);
        $this->nodeUrlManager->updateUrl($categoryNode, $locale);

        $this->touchCategoryActiveRecursive($categoryNode);
        $this->touchNavigationActive();

        $connection->commit();
    }

    /**
     * @param NodeTransfer $categoryNode
     */
    protected function touchCategoryActiveRecursive(NodeTransfer $categoryNode)
    {
        $closureQuery= new SpyCategoryClosureTableQuery();
        $nodes = $closureQuery->findByFkCategoryNodeDescendant($categoryNode->getFkParentCategoryNode());

        foreach($nodes as $node) {
            $this->touchCategoryActive($node->getFkCategoryNode());
        }

        $this->touchCategoryActive($categoryNode->getIdCategoryNode());
    }

    /**
     * @param NodeTransfer $categoryNode
     */
    protected function touchCategoryDeletedRecursive(NodeTransfer $categoryNode)
    {
        $closureQuery= new SpyCategoryClosureTableQuery();
        $nodes = $closureQuery->findByFkCategoryNodeDescendant($categoryNode->getFkParentCategoryNode());

        foreach($nodes as $node) {
            $this->touchCategoryDeleted($node->getFkCategoryNode());
        }

        $this->touchCategoryDeleted($categoryNode->getIdCategoryNode());
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
        //order of execution matters, this must be called before node is deleted
        $this->touchUrlDeleted($idNode, $locale);
        
        if ($this->categoryTreeReader->hasChildren($idNode) && $deleteChildren) {
            $childNodes = $this->categoryTreeReader->getChildren($idNode, $locale);
            foreach ($childNodes as $childNode) {
                $this->deleteNode($childNode->getIdCategoryNode(), $locale, true);
            }
        }

        $this->touchCategoryDeleted($idNode);

        $this->closureTableWriter->delete($idNode);

        $this->touchCategoryDeleted($idNode);
        $this->touchNavigationDeleted();

        return $this->nodeWriter->delete($idNode);
    }

    /**
     * @param int $idCategoryNode
     */
    protected function touchCategoryActive($idCategoryNode)
    {
        $this->touchFacade->touchActive(CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE, $idCategoryNode);
    }

    /**
     * @param $idCategoryNode
     */
    protected function touchCategoryDeleted($idCategoryNode)
    {
        $this->touchFacade->touchDeleted(CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE, $idCategoryNode);
    }

    protected function touchNavigationActive()
    {
        $this->touchFacade->touchActive(CategoryConfig::RESOURCE_TYPE_NAVIGATION, self::ID_NAVIGATION);
    }

    protected function touchNavigationDeleted()
    {
        $this->touchFacade->touchDeleted(CategoryConfig::RESOURCE_TYPE_NAVIGATION, self::ID_NAVIGATION);
    }

    /**
     * @param $idCategoryNode
     */
    protected function touchUrlDeleted($idCategoryNode, LocaleTransfer $locale)
    {
        $node = $this->categoryTreeReader->getNodeById($idCategoryNode);
        $nodeTransfer = (new NodeTransfer())->fromArray($node->toArray());
        $this->nodeUrlManager->removeUrl($nodeTransfer, $locale);
    }
}
