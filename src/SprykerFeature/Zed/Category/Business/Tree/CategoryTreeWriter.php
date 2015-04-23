<?php

namespace SprykerFeature\Zed\Category\Business\Tree;

use SprykerFeature\Shared\Category\CategoryResourceSettings;
use SprykerFeature\Shared\Category\Transfer\CategoryNode;
use SprykerFeature\Zed\Category\Business\Manager\NodeUrlManagerInterface;
use SprykerFeature\Zed\Category\Business\Model\CategoryWriterInterface;
use SprykerFeature\Zed\Category\Dependency\Facade\CategoryToTouchInterface;
use Propel\Runtime\Propel;

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
     * @param CategoryNode $categoryNode
     * @param int $idLocale
     * @param bool $createUrlPath
     *
     * @return int
     */
    public function createCategoryNode(CategoryNode $categoryNode, $idLocale, $createUrlPath = true)
    {
        $connection = Propel::getConnection();
        $connection->beginTransaction();

        $idNode = $this->nodeWriter->create($categoryNode);
        $this->closureTableWriter->create($categoryNode);
        $this->touchCategoryActive($categoryNode->getIdCategoryNode());
        $this->touchNavigationActive();
        if ($createUrlPath) {
            $this->nodeUrlManager->createUrl($categoryNode, $idLocale, $idNode);
        }

        $connection->commit();

        return $idNode;
    }

    /**
     * @param int $idNode
     * @param int $idLocale
     *
     * @return bool
     */
    public function deleteCategoryByNodeId($idNode, $idLocale)
    {
        $connection = Propel::getConnection();
        $connection->beginTransaction();

        $nodeEntity = $this->categoryTreeReader->getNodeById($idNode);
        $idCategory = $nodeEntity->getFkCategory();
        $categoryNodes = $this->categoryTreeReader->getNodesByIdCategory($idCategory);

        foreach ($categoryNodes as $node) {
            $this->deleteNode($node->getPrimaryKey(), $idLocale, true);
        }
        $this->categoryWriter->delete($idCategory);

        $connection->commit();

        return true;
    }

    /**
     * @param CategoryNode $categoryNode
     */
    public function moveNode(CategoryNode $categoryNode)
    {
        $connection = Propel::getConnection();
        $connection->beginTransaction();

        $this->closureTableWriter->delete($categoryNode->getIdCategoryNode());
        $this->nodeWriter->update($categoryNode);
        $this->closureTableWriter->create($categoryNode);

        $connection->commit();
    }

    /**
     * @param int $idNode
     * @param int $idLocale
     * @param bool $deleteChildren
     *
     * @return int
     */
    public function deleteNode($idNode, $idLocale, $deleteChildren = false)
    {
        if ($this->categoryTreeReader->hasChildren($idNode) && $deleteChildren) {
            $childNodes = $this->categoryTreeReader->getChildren($idNode, $idLocale);
            foreach ($childNodes as $childNode) {
                $this->deleteNode($childNode->getIdCategoryNode(), $idLocale, true);
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
        $this->touchFacade->touchActive(CategoryResourceSettings::RESOURCE_TYPE_CATEGORY_NODE, $idCategoryNode);
    }

    protected function touchNavigationActive()
    {
        $this->touchFacade->touchActive(CategoryResourceSettings::RESOURCE_TYPE_NAVIGATION, self::ID_NAVIGATION);
    }
}
