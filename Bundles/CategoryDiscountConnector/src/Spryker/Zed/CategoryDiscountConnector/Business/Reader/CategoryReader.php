<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDiscountConnector\Business\Reader;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryConditionsTransfer;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToCategoryFacadeInterface;
use Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToLocaleFacadeInterface;

class CategoryReader implements CategoryReaderInterface
{
    /**
     * @var array<int, list<string>>
     */
    protected static array $categoryKeysGroupedByIdCategoryNode = [];

    /**
     * @var \Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToCategoryFacadeInterface
     */
    protected CategoryDiscountConnectorToCategoryFacadeInterface $categoryFacade;

    /**
     * @var \Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToLocaleFacadeInterface
     */
    protected CategoryDiscountConnectorToLocaleFacadeInterface $localeFacade;

    /**
     * @param \Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        CategoryDiscountConnectorToCategoryFacadeInterface $categoryFacade,
        CategoryDiscountConnectorToLocaleFacadeInterface $localeFacade
    ) {
        $this->categoryFacade = $categoryFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return array<string, string>
     */
    public function getCategoryNamesIndexedByCategoryKey(): array
    {
        $indexedCategoryNames = [];
        $categoryCollectionTransfer = $this->getCategoryCollectionForCurrentLocale();

        foreach ($categoryCollectionTransfer->getCategories() as $categoryTransfer) {
            $indexedCategoryNames[$categoryTransfer->getCategoryKeyOrFail()] = $categoryTransfer->getNameOrFail();
        }

        return $indexedCategoryNames;
    }

    /**
     * @param array<int, list<\Generated\Shared\Transfer\ProductCategoryTransfer>> $productCategoryTransfersGroupedByIdProductAbstract
     *
     * @return array<int, list<string>>
     */
    public function getCategoryKeysGroupedByIdCategoryNode(array $productCategoryTransfersGroupedByIdProductAbstract): array
    {
        $categoryNodeIds = $this->extractCategoryNodeIds($productCategoryTransfersGroupedByIdProductAbstract);
        $categoryNodeIds = array_diff($categoryNodeIds, array_keys(static::$categoryKeysGroupedByIdCategoryNode));
        if (!$categoryNodeIds) {
            return static::$categoryKeysGroupedByIdCategoryNode;
        }

        $categoryNodeCriteriaTransfer = (new CategoryNodeCriteriaTransfer())
            ->setCategoryNodeIds($categoryNodeIds);

        static::$categoryKeysGroupedByIdCategoryNode += $this->categoryFacade
            ->getAscendantCategoryKeysGroupedByIdCategoryNode($categoryNodeCriteriaTransfer);

        return static::$categoryKeysGroupedByIdCategoryNode;
    }

    /**
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    protected function getCategoryCollectionForCurrentLocale(): CategoryCollectionTransfer
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();

        $categoryConditionsTransfer = (new CategoryConditionsTransfer())->addIdLocale($localeTransfer->getIdLocaleOrFail());
        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setCategoryConditions($categoryConditionsTransfer);

        return $this->categoryFacade->getCategoryCollection($categoryCriteriaTransfer);
    }

    /**
     * @param array<int, list<\Generated\Shared\Transfer\ProductCategoryTransfer>> $productCategoryTransfersGroupedByIdProductAbstract
     *
     * @return list<int>
     */
    protected function extractCategoryNodeIds(array $productCategoryTransfersGroupedByIdProductAbstract): array
    {
        $categoryNodeIds = [];

        foreach ($productCategoryTransfersGroupedByIdProductAbstract as $productCategoryTransfers) {
            foreach ($productCategoryTransfers as $productCategoryTransfer) {
                $categoryNodeIds[] = $productCategoryTransfer->getCategoryOrFail()->getCategoryNodeOrFail()->getIdCategoryNodeOrFail();
            }
        }

        return array_unique($categoryNodeIds);
    }
}
