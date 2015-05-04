<?php

namespace SprykerFeature\Zed\Category\Business\Tree;

use Generated\Zed\Ide\AutoCompletion;
use Generated\Shared\Transfer\CategoryCategoryNodeTransfer;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Category\Business\Tree\Exception\NodeNotFoundException;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use Propel\Runtime\Exception\PropelException;

class NodeWriter implements NodeWriterInterface
{

    const CATEGORY_URL_IDENTIFIER_LENGTH = 4;

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
     * @param CategoryQueryContainer $queryContainer
     */
    public function __construct(
        LocatorLocatorInterface $locator,
        CategoryQueryContainer $queryContainer
    ) {
        $this->locator = $locator;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param CategoryNode $categoryNode
     * @return int
     * @throws PropelException
     */
    public function create(CategoryNode $categoryNode)
    {
        $nodeEntity = $this->locator->category()->entitySpyCategoryNode();
        $nodeEntity->fromArray($categoryNode->toArray());
        $nodeEntity->save();

        $nodeId = $nodeEntity->getIdCategoryNode();
        $categoryNode->setIdCategoryNode($nodeId);

        return $nodeId;
    }

    /**
     * @param int $nodeId
     * @return int
     * @throws NodeNotFoundException
     * @throws PropelException
     */
    public function delete($nodeId)
    {
        $nodeEntity = $this->queryContainer
            ->queryNodeById($nodeId)
            ->findOne()
        ;
        if (!$nodeEntity) {
            throw new NodeNotFoundException();
        }
        $categoryId = $nodeEntity->getFkCategory();
        $nodeEntity->delete();

        return $categoryId;
    }

    /**
     * @param CategoryNode $categoryNode
     * @throws PropelException
     */
    public function update(CategoryNode $categoryNode)
    {
        $nodeEntity = $this->queryContainer
            ->queryNodeById($categoryNode->getIdCategoryNode())
            ->findOne()
        ;
        if ($nodeEntity) {
            $nodeEntity->setFkParentCategoryNode($categoryNode->getFkParentCategoryNode());
            $nodeEntity->save();
        }
    }
}
