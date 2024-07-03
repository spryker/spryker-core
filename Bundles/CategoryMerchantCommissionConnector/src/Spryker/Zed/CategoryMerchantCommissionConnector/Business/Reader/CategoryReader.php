<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryMerchantCommissionConnector\Business\Reader;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Spryker\Zed\CategoryMerchantCommissionConnector\Dependency\Facade\CategoryMerchantCommissionConnectorToCategoryFacadeInterface;

class CategoryReader implements CategoryReaderInterface
{
    /**
     * @var \Spryker\Zed\CategoryMerchantCommissionConnector\Dependency\Facade\CategoryMerchantCommissionConnectorToCategoryFacadeInterface
     */
    protected CategoryMerchantCommissionConnectorToCategoryFacadeInterface $categoryFacade;

    /**
     * @var array<int, list<string>>
     */
    protected static array $categoryKeysGroupedByIdCategoryNode = [];

    /**
     * @param \Spryker\Zed\CategoryMerchantCommissionConnector\Dependency\Facade\CategoryMerchantCommissionConnectorToCategoryFacadeInterface $categoryFacade
     */
    public function __construct(CategoryMerchantCommissionConnectorToCategoryFacadeInterface $categoryFacade)
    {
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @param array<string, list<\Generated\Shared\Transfer\ProductCategoryTransfer>> $productCategoryTransfersGroupedBySku
     *
     * @return array<int, list<string>>
     */
    public function getCategoryKeysGroupedByIdCategoryNode(array $productCategoryTransfersGroupedBySku): array
    {
        $categoryNodeIds = $this->extractCategoryNodeIds($productCategoryTransfersGroupedBySku);
        $notCachedCategoryNodeIds = $this->filterOutCachedCategoryNodeIds($categoryNodeIds);
        if ($notCachedCategoryNodeIds !== []) {
            $this->addCategoryKeysToCache($notCachedCategoryNodeIds);
        }

        return array_intersect_key(static::$categoryKeysGroupedByIdCategoryNode, array_flip($categoryNodeIds));
    }

    /**
     * @param list<int> $categoryNodeIds
     *
     * @return void
     */
    public function addCategoryKeysToCache(array $categoryNodeIds): void
    {
        $categoryNodeCriteriaTransfer = (new CategoryNodeCriteriaTransfer())
            ->setCategoryNodeIds($categoryNodeIds);

        static::$categoryKeysGroupedByIdCategoryNode += $this->categoryFacade
            ->getAscendantCategoryKeysGroupedByIdCategoryNode($categoryNodeCriteriaTransfer);
    }

    /**
     * @param array<string, list<\Generated\Shared\Transfer\ProductCategoryTransfer>> $productCategoryTransfersGroupedBySku
     *
     * @return list<int>
     */
    protected function extractCategoryNodeIds(array $productCategoryTransfersGroupedBySku): array
    {
        $categoryNodeIds = [];

        foreach ($productCategoryTransfersGroupedBySku as $productCategoryTransfers) {
            foreach ($productCategoryTransfers as $productCategoryTransfer) {
                $categoryNodeIds[] = $productCategoryTransfer->getCategoryOrFail()->getCategoryNodeOrFail()->getIdCategoryNodeOrFail();
            }
        }

        return array_unique($categoryNodeIds);
    }

    /**
     * @param list<int> $categoryNodeIds
     *
     * @return list<int>
     */
    protected function filterOutCachedCategoryNodeIds(array $categoryNodeIds): array
    {
        return array_diff($categoryNodeIds, array_keys(static::$categoryKeysGroupedByIdCategoryNode));
    }
}
