<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Expander;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\ProductAbstractCategoryStorageCollectionTransfer;
use Spryker\Client\CategoryStorage\Storage\CategoryNodeStorageInterface;

class ProductCategoryStorageExpander implements ProductCategoryStorageExpanderInterface
{
    /**
     * @var \Spryker\Client\CategoryStorage\Storage\CategoryNodeStorageInterface
     */
    protected CategoryNodeStorageInterface $categoryNodeStorage;

    /**
     * @param \Spryker\Client\CategoryStorage\Storage\CategoryNodeStorageInterface $categoryNodeStorage
     */
    public function __construct(CategoryNodeStorageInterface $categoryNodeStorage)
    {
        $this->categoryNodeStorage = $categoryNodeStorage;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractCategoryStorageCollectionTransfer $productAbstractCategoryStorageCollectionTransfer
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageCollectionTransfer
     */
    public function expandProductCategoriesWithParentIds(
        ProductAbstractCategoryStorageCollectionTransfer $productAbstractCategoryStorageCollectionTransfer,
        string $localeName,
        string $storeName
    ): ProductAbstractCategoryStorageCollectionTransfer {
        $productCategoryStorageTransfers = [];
        foreach ($productAbstractCategoryStorageCollectionTransfer->getProductAbstractCategories() as $productAbstractCategoryStorageTransfer) {
            $productCategoryStorageTransfers = array_merge($productCategoryStorageTransfers, $productAbstractCategoryStorageTransfer->getCategories()->getArrayCopy());
        }

        if (!$productCategoryStorageTransfers) {
            return $productAbstractCategoryStorageCollectionTransfer;
        }

        $categoryNodeStorageTransfers = $this->categoryNodeStorage->getCategoryNodeByIds(
            $this->extractCategoryNodeIdsFromProductCategoryStorageTransfers($productCategoryStorageTransfers),
            $localeName,
            $storeName,
        );

        $this->setParentCategoryNodeIdsForProductCategoryStorageTransfer($productCategoryStorageTransfers, $categoryNodeStorageTransfers);

        return $productAbstractCategoryStorageCollectionTransfer;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer> $productCategoryStorageTransfers
     * @param array<int, \Generated\Shared\Transfer\CategoryNodeStorageTransfer> $categoryNodeStorageTransfers
     *
     * @return void
     */
    protected function setParentCategoryNodeIdsForProductCategoryStorageTransfer(
        array $productCategoryStorageTransfers,
        array $categoryNodeStorageTransfers
    ): void {
        foreach ($productCategoryStorageTransfers as $productCategoryStorageTransfer) {
            if (!isset($categoryNodeStorageTransfers[$productCategoryStorageTransfer->getCategoryNodeIdOrFail()])) {
                continue;
            }

            $categoryNodeStorageTransfer = $categoryNodeStorageTransfers[$productCategoryStorageTransfer->getCategoryNodeIdOrFail()];

            if ($categoryNodeStorageTransfer->getParents()->count()) {
                $productCategoryStorageTransfer->setParentCategoryIds(
                    $this->extractParentCategoryNodeIdsFromCategoryNodeStorageTransfer($categoryNodeStorageTransfer),
                );
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNodeStorageTransfer
     *
     * @return list<int>
     */
    protected function extractParentCategoryNodeIdsFromCategoryNodeStorageTransfer(CategoryNodeStorageTransfer $categoryNodeStorageTransfer): array
    {
        $parentCategoryIds = [];

        foreach ($categoryNodeStorageTransfer->getParents() as $parentCategoryNodeStorageTransfer) {
            $parentCategoryIds[] = $parentCategoryNodeStorageTransfer->getIdCategoryOrFail();
        }

        return $parentCategoryIds;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer> $productCategoryStorageTransfers
     *
     * @return list<int>
     */
    protected function extractCategoryNodeIdsFromProductCategoryStorageTransfers(
        array $productCategoryStorageTransfers
    ): array {
        $categoryNodeIds = [];

        foreach ($productCategoryStorageTransfers as $productCategoryStorageTransfer) {
            $categoryNodeIds[] = $productCategoryStorageTransfer->getCategoryNodeIdOrFail();
        }

        return $categoryNodeIds;
    }
}
