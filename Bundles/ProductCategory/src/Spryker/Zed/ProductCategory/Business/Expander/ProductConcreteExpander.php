<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\CategoryConditionsTransfer;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\ProductCategoryConditionsTransfer;
use Generated\Shared\Transfer\ProductCategoryCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryInterface;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface;

class ProductConcreteExpander implements ProductConcreteExpanderInterface
{
    /**
     * @var array<int, \Generated\Shared\Transfer\NodeTransfer|null>
     */
    protected static $categoryCache = [];

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
        $productCategoryTransferListGroupedByAbstractId = $this->findProductCategoriesIndexedByIdAbstract($productConcreteTransfers);

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            /** @var \ArrayObject<int, \Generated\Shared\Transfer\ProductCategoryTransfer>|null $productCategoryTransferList */
            $productCategoryTransferList = $productCategoryTransferListGroupedByAbstractId[$productConcreteTransfer->getFkProductAbstract()] ?? null;

            if ($productCategoryTransferList === null) {
                continue;
            }

            $productConcreteTransfer->setProductCategories($productCategoryTransferList);

            $productCategoryIds = [];
            foreach ($productCategoryTransferList as $productCategoryTransfer) {
                $productCategoryIds[] = $productCategoryTransfer->getFkCategory();
            }

            $productConcreteTransfer->setRelatedCategoryTreeNodes($this->getRelatedCategoryTreeNodes($productCategoryIds));
        }

        return $productConcreteTransfers;
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<int, \ArrayObject<int, \Generated\Shared\Transfer\ProductCategoryTransfer>>
     */
    protected function findProductCategoriesIndexedByIdAbstract(array $productConcreteTransfers): array
    {
        $productAbstractIds = $this->getProductAbstractIdsFromProductConcreteCollection($productConcreteTransfers);

        if ($productAbstractIds === []) {
            return [];
        }

        // Reading child categories for abstract products
        $productCategoryCriteriaTransfer = (new ProductCategoryCriteriaTransfer())
            ->setProductCategoryConditions(
                (new ProductCategoryConditionsTransfer())
                    ->setProductAbstractIds($productAbstractIds),
            );
        $productCategoryCollectionTransfer = $this->productCategoryRepository->getProductCategoryCollection($productCategoryCriteriaTransfer);
        $productCategories = [];
        foreach ($productCategoryCollectionTransfer->getProductCategories() as $productCategoryTransfer) {
            if (!isset($productCategories[$productCategoryTransfer->getFkProductAbstractOrFail()])) {
                $productCategories[$productCategoryTransfer->getFkProductAbstractOrFail()] = new ArrayObject();
            }
            $productCategories[$productCategoryTransfer->getFkProductAbstractOrFail()]->append($productCategoryTransfer);
        }

        return $productCategories;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<int>
     */
    protected function getProductAbstractIdsFromProductConcreteCollection(array $productConcreteTransfers): array
    {
        $productAbstractIds = array_map(fn (ProductConcreteTransfer $productConcreteTransfer) => $productConcreteTransfer->getFkProductAbstract(), $productConcreteTransfers);

         return array_unique(array_filter($productAbstractIds));
    }

    /**
     * @param array<int> $productCategoryIds
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\NodeTransfer>
     */
    protected function getRelatedCategoryTreeNodes(array $productCategoryIds): ArrayObject
    {
        $nodeTransferList = new ArrayObject();

        if (!$productCategoryIds) {
            return $nodeTransferList;
        }

        // Reading and setting categories nested tree by child nodes
        foreach ($productCategoryIds as $productCategoryId) {
            $nodeTransfers = [];
            $rootNode = null;
            while ($productCategoryId !== null) {
                if (!isset(static::$categoryCache[$productCategoryId])) {
                    $categoryTransfer = $this->categoryFacade->findCategory(
                        (new CategoryCriteriaTransfer())
                            ->setCategoryConditions(
                                (new CategoryConditionsTransfer())->setCategoryIds([$productCategoryId]),
                            ),
                    );
                    static::$categoryCache[$productCategoryId] = $categoryTransfer !== null ? $categoryTransfer->getCategoryNode() : null;
                }
                if (static::$categoryCache[$productCategoryId] === null) {
                    $productCategoryId = null;

                    break;
                }

                $nodeTransfers[] = (new NodeTransfer())->fromArray(static::$categoryCache[$productCategoryId]->toArray());
                if (static::$categoryCache[$productCategoryId]->getIsRoot()) {
                    $rootNode = (new NodeTransfer())->fromArray(static::$categoryCache[$productCategoryId]->toArray());
                }
                // When the category doesn't have a parent, it is the root node and we continue with the next category.
                $productCategoryId = static::$categoryCache[$productCategoryId]->getFkParentCategoryNode();
            }

            if (!$nodeTransfers || !$rootNode) {
                continue;
            }

            $nodeTransferList->append($this->buildNodeTree($rootNode, $nodeTransfers));
        }

        return $nodeTransferList;
    }

    /**
     * Build category nested tree from category nodes.
     *
     * @param \Generated\Shared\Transfer\NodeTransfer $rootNode
     * @param array<\Generated\Shared\Transfer\NodeTransfer> $nodeTransfers
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    protected function buildNodeTree(NodeTransfer $rootNode, array $nodeTransfers): NodeTransfer
    {
        foreach ($nodeTransfers as $nodeTransfer) {
            if ($rootNode->getCategory()->getIdCategoryOrFail() !== $nodeTransfer->getFkParentCategoryNode()) {
                continue;
            }
            if ($rootNode->getChildrenNodes() == null) {
                $rootNode->setChildrenNodes(new NodeCollectionTransfer());
            }
            $rootNode->getChildrenNodes()->addNode($this->buildNodeTree($nodeTransfer, $nodeTransfers));
        }

        return $rootNode;
    }
}
