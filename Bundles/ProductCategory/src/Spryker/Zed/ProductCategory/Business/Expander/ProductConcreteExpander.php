<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\ProductCategoryCollectionTransfer;
use Generated\Shared\Transfer\ProductCategoryConditionsTransfer;
use Generated\Shared\Transfer\ProductCategoryCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryInterface;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface;

class ProductConcreteExpander implements ProductConcreteExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface
     */
    protected $productCategoryRepository;

    /**
     * @var \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryInterface
     */
    protected $categoryFacade;

    /**
     * @param \Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface $productCategoryRepository
     * @param \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryInterface $categoryFacade
     */
    public function __construct(ProductCategoryRepositoryInterface $productCategoryRepository, ProductCategoryToCategoryInterface $categoryFacade)
    {
        $this->productCategoryRepository = $productCategoryRepository;
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expandProductConcreteWithProductCategories(array $productConcreteTransfers): array
    {
        $rootCategory = $this->categoryFacade->findCategory(
            (new CategoryCriteriaTransfer())
                ->setIsRoot(true)
                ->setWithChildrenRecursively(true),
        );

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productCategoryCollectionTransfer = $this->findProductCategories($productConcreteTransfer);

            if ($productCategoryCollectionTransfer !== null) {
                $productConcreteTransfer->setProductCategories($productCategoryCollectionTransfer->getProductCategories());
            }

            $productCategoriesIds = [];
            foreach ($productConcreteTransfer->getProductCategories() as $productCategoryTransfer) {
                $productCategoriesIds[] = $productCategoryTransfer->getFkCategory();
            }

            $filteredCategoryTree = $this->filterCategoryTree($productCategoriesIds, $rootCategory->getNodeCollection());

            $productConcreteTransfer->setRelatedCategoryTreeNodes($filteredCategoryTree);
        }

        return $productConcreteTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryCollectionTransfer|null
     */
    protected function findProductCategories(ProductConcreteTransfer $productConcreteTransfer): ?ProductCategoryCollectionTransfer
    {
        if ($productConcreteTransfer->getFkProductAbstract() === null) {
            return null;
        }

        $productCategoryConditionsTransfer = (new ProductCategoryConditionsTransfer())
            ->setProductAbstractIds([$productConcreteTransfer->getFkProductAbstract()]);

        $productCategoryCriteriaTransfer = (new ProductCategoryCriteriaTransfer())
            ->setProductCategoryConditions($productCategoryConditionsTransfer);

        return $this->productCategoryRepository->getProductCategoryCollection($productCategoryCriteriaTransfer);
    }

    /**
     * @param array<int> $productCategoriesIds
     * @param \Generated\Shared\Transfer\NodeCollectionTransfer $nodeCollectionTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\NodeTransfer>
     */
    protected function filterCategoryTree(array $productCategoriesIds, NodeCollectionTransfer $nodeCollectionTransfer): ArrayObject
    {
        if (!$productCategoriesIds) {
            return new ArrayObject();
        }

        $filteredCategoryNodeStorageTransfers = new ArrayObject();

        foreach ($nodeCollectionTransfer->getNodes() as $nodeTransfer) {
            $include = false;
            $includeChildren = false;

            $nodeTransfer = (new NodeTransfer())
                ->fromArray($nodeTransfer->toArray());

            if (in_array($nodeTransfer->getCategory()->getIdCategoryOrFail(), $productCategoriesIds)) {
                // current category is one of assigned product categories so it should be included in the list
                $include = true;
            }

            // check if any children of current category should be included in the list
            $includedChildren = $this->getIncludedChildrenFromTreeNode($nodeTransfer, $productCategoriesIds);

            if (count($includedChildren)) {
                $nodeTransfer->setChildrenNodes(
                    (new NodeCollectionTransfer())->setNodes(new ArrayObject($includedChildren)),
                );
                $include = true;
                $includeChildren = true;
                // current category children nodes are one of assigned product categories so they should be included in the list
            }

            if (!$includeChildren) {
                $nodeTransfer->setChildrenNodes(new NodeCollectionTransfer());
            }

            if ($include) {
                $filteredCategoryNodeStorageTransfers->append($nodeTransfer);
            }
        }

        return $filteredCategoryNodeStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param array $productCategoriesIds
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\NodeTransfer>
     */
    protected function getIncludedChildrenFromTreeNode(NodeTransfer $nodeTransfer, array $productCategoriesIds): ArrayObject
    {
        if ($nodeTransfer->getChildrenNodes()->getNodes()->count()) {
            return $this->filterCategoryTree($productCategoriesIds, $nodeTransfer->getChildrenNodes());
        }

        return new ArrayObject();
    }
}
