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
        $this->touchCategoryActive($categoryNode->getIdCategoryNode());
        $this->touchNavigationActive();
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

        $nodeEntity = $this->categoryTreeReader->getNodeById($idNode);
        $idCategory = $nodeEntity->getFkCategory();
        $categoryNodes = $this->categoryTreeReader->getNodesByIdCategory($idCategory);

        foreach ($categoryNodes as $node) {
            $this->deleteNode($node->getPrimaryKey(), $locale, true);
        }
        $this->categoryWriter->delete($idCategory);

        $connection->commit();

        return true;
    }

    /**
     * @param NodeTransfer $categoryNode
     */
    public function moveNode(NodeTransfer $categoryNode)
    {
        $connection = Propel::getConnection();
        $connection->beginTransaction();

        $this->closureTableWriter->delete($categoryNode->getIdCategoryNode());
        $this->nodeWriter->update($categoryNode);
        $this->closureTableWriter->create($categoryNode);

        $this->touchCategoryActive($categoryNode->getIdCategoryNode());
        $this->touchNavigationActive();

        $connection->commit();
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
        if ($this->categoryTreeReader->hasChildren($idNode) && $deleteChildren) {
            $childNodes = $this->categoryTreeReader->getChildren($idNode, $locale);
            foreach ($childNodes as $childNode) {
                $this->deleteNode($childNode->getIdCategoryNode(), $locale, true);
            }
        }
        $this->closureTableWriter->delete($idNode);

        return $this->nodeWriter->delete($idNode);
    }

    /**
     * @param int $idCategoryNode
     */
    protected function touchCategoryActive($idCategoryNode)
    {
        $this->touchFacade->touchActive(CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE, $idCategoryNode);
    }

    protected function touchNavigationActive()
    {
        $this->touchFacade->touchActive(CategoryConfig::RESOURCE_TYPE_NAVIGATION, self::ID_NAVIGATION);
    }

}
