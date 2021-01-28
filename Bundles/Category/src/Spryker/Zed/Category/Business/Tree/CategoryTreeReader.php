<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;

class CategoryTreeReader implements CategoryTreeReaderInterface
{
    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $categoryQueryContainer
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        CategoryQueryContainerInterface $categoryQueryContainer,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\CategoryCriteriaTransfer $categoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\NodeCollectionTransfer
     */
    public function getCategoryNodeCollectionTree(
        CategoryTransfer $categoryTransfer,
        CategoryCriteriaTransfer $categoryCriteriaTransfer
    ): NodeCollectionTransfer {
        $nodeCollectionTransfer = new NodeCollectionTransfer();
        $categoryNodes = $this->categoryRepository->getCategoryNodeChildNodesCollectionIndexedByParentNodeId(
            $categoryTransfer,
            $categoryCriteriaTransfer
        );

        if ($categoryNodes === []) {
            return $nodeCollectionTransfer;
        }

        $categoryNodeTransfer = $this->buildNodeTree($categoryNodes, $categoryTransfer->getCategoryNodeOrFail());

        return $nodeCollectionTransfer->addNode($categoryNodeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer[][] $categoryNodes
     * @param \Generated\Shared\Transfer\NodeTransfer $parentNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    protected function buildNodeTree(array $categoryNodes, NodeTransfer $parentNodeTransfer): NodeTransfer
    {
        $nodeCollectionTransfer = new NodeCollectionTransfer();
        $childrenNodes = $this->findChildrenNodes($categoryNodes, $parentNodeTransfer);
        foreach ($childrenNodes as $childrenNode) {
            $childNodeTransfer = $this->buildNodeTree($categoryNodes, $childrenNode);
            $nodeCollectionTransfer->addNode($childNodeTransfer);
        }

        return $parentNodeTransfer->setChildrenNodes($nodeCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer[][] $categoryNodesCollection
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNode
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    protected function findChildrenNodes(array $categoryNodesCollection, NodeTransfer $categoryNode): array
    {
        return $categoryNodesCollection[$categoryNode->getIdCategoryNode()] ?? [];
    }
}
