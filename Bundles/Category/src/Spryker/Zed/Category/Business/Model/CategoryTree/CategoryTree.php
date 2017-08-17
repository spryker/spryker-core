<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\CategoryTree;

use ArrayObject;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\Category\Business\CategoryFacadeInterface;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class CategoryTree implements CategoryTreeInterface
{

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Category\Business\CategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Category\Business\CategoryFacadeInterface $categoryFacade
     */
    public function __construct(
        CategoryQueryContainerInterface $queryContainer,
        CategoryFacadeInterface $categoryFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @param int $idSourceCategoryNode
     * @param int $idDestinationCategoryNode
     *
     * @return void
     */
    public function moveSubTree($idSourceCategoryNode, $idDestinationCategoryNode)
    {
        $firstLevelChildNodeCollection = $this
            ->queryContainer
            ->queryFirstLevelChildren($idSourceCategoryNode)
            ->find();

        $destinationCategoryNodeEntity = $this->queryContainer
            ->queryNodeById($idDestinationCategoryNode)
            ->findOne();

        foreach ($firstLevelChildNodeCollection as $childNodeEntity) {
            if ($childNodeEntity->getFkCategory() === $destinationCategoryNodeEntity->getFkCategory()) {
                $this->categoryFacade->deleteNodeById($childNodeEntity->getIdCategoryNode());
                continue;
            }

            $categoryTransfer = $this->categoryFacade->read($childNodeEntity->getFkCategory());

            if ($childNodeEntity->getIsMain()) {
                $this->moveMainCategoryNodeSubTree($categoryTransfer, $idDestinationCategoryNode);
                continue;
            }

            $this->moveExtraParentCategoryNodeSubTree(
                $categoryTransfer,
                $idDestinationCategoryNode,
                $idSourceCategoryNode
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param int $idDestinationCategoryNode
     *
     * @return void
     */
    protected function moveMainCategoryNodeSubTree(CategoryTransfer $categoryTransfer, $idDestinationCategoryNode)
    {
        $categoryNodeTransfer = $categoryTransfer->requireCategoryNode()->getCategoryNode();
        $categoryNodeTransfer->setFkParentCategoryNode($idDestinationCategoryNode);
        $categoryTransfer->setCategoryNode($categoryNodeTransfer);

        $categoryParentNodeTransfer = $categoryTransfer->requireParentCategoryNode()->getParentCategoryNode();
        $categoryParentNodeTransfer->setIdCategoryNode($idDestinationCategoryNode);
        $categoryTransfer->setParentCategoryNode($categoryParentNodeTransfer);

        $this->categoryFacade->update($categoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param int $idDestinationCategoryNode
     * @param int $idSourceCategoryNode
     *
     * @return void
     */
    protected function moveExtraParentCategoryNodeSubTree(
        CategoryTransfer $categoryTransfer,
        $idDestinationCategoryNode,
        $idSourceCategoryNode
    ) {
        $extraParentNodeTransferCollection = $categoryTransfer->getExtraParents();
        $updatedParentNodeTransferCollection = new ArrayObject();

        foreach ($extraParentNodeTransferCollection as $extraParentNodeTransfer) {
            if ($extraParentNodeTransfer->requireIdCategoryNode()->getIdCategoryNode() === $idSourceCategoryNode) {
                $extraParentNodeTransfer = new NodeTransfer();
                $extraParentNodeTransfer->setIdCategoryNode($idDestinationCategoryNode);
            }

            $updatedParentNodeTransferCollection->append($extraParentNodeTransfer);
        }

        $categoryTransfer->setExtraParents($updatedParentNodeTransferCollection);

        $this->categoryFacade->update($categoryTransfer);
    }

}
