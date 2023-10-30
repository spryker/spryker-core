<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Reader;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Spryker\Zed\Category\Business\Expander\CategoryNodeRelationExpanderInterface;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;

class CategoryNodeReader implements CategoryNodeReaderInterface
{
    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected CategoryRepositoryInterface $categoryRepository;

    /**
     * @var \Spryker\Zed\Category\Business\Expander\CategoryNodeRelationExpanderInterface
     */
    protected CategoryNodeRelationExpanderInterface $categoryNodeRelationExpander;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     * @param \Spryker\Zed\Category\Business\Expander\CategoryNodeRelationExpanderInterface $categoryNodeRelationExpander
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        CategoryNodeRelationExpanderInterface $categoryNodeRelationExpander
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->categoryNodeRelationExpander = $categoryNodeRelationExpander;
    }

    /**
     * @param int $idCategory
     *
     * @return array<\Generated\Shared\Transfer\NodeTransfer>
     */
    public function getAllNodesByIdCategory(int $idCategory): array
    {
        $categoryNodeCriteriaTransfer = (new CategoryNodeCriteriaTransfer())
            ->addIdCategory($idCategory);

        return $this->categoryRepository
            ->getCategoryNodes($categoryNodeCriteriaTransfer)
            ->getNodes()
            ->getArrayCopy();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\NodeCollectionTransfer
     */
    public function getCategoryNodesWithRelativeNodes(
        CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer
    ): NodeCollectionTransfer {
        $categoryNodeCollectionTransfer = $this->categoryRepository->getCategoryNodesWithRelativeNodes($categoryNodeCriteriaTransfer);
        $categoryNodeCollectionTransfer = $this->categoryNodeRelationExpander->expandNodeCollectionWithRelations($categoryNodeCollectionTransfer);

        return $categoryNodeCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\NodeCollectionTransfer
     */
    public function getCategoryNodeCollection(
        CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer
    ): NodeCollectionTransfer {
        $nodeCollectionTransfer = $this->categoryRepository->getCategoryNodes($categoryNodeCriteriaTransfer);

        if (!$categoryNodeCriteriaTransfer->getWithRelations()) {
            return $nodeCollectionTransfer;
        }

        return $this->categoryNodeRelationExpander->expandNodeCollectionWithRelations($nodeCollectionTransfer);
    }
}
