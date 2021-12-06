<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDiscountConnector\Business\Reader;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToCategoryFacadeInterface;
use Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToLocaleFacadeInterface;

class CategoryReader implements CategoryReaderInterface
{
    /**
     * @var \Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToLocaleFacadeInterface
     */
    protected $localeFacade;

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
     * @param array<int, array<\Generated\Shared\Transfer\ProductCategoryTransfer>> $groupedProductCategoryTransfers
     *
     * @return array<int, array<string>>
     */
    public function getCategoryKeysGroupedByIdCategoryNode(array $groupedProductCategoryTransfers): array
    {
        $categoryNodeCriteriaTransfer = (new CategoryNodeCriteriaTransfer())
            ->setCategoryNodeIds($this->extractCategoryNodeIds($groupedProductCategoryTransfers));

        return $this->categoryFacade
            ->getAscendantCategoryKeysGroupedByIdCategoryNode($categoryNodeCriteriaTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    protected function getCategoryCollectionForCurrentLocale(): CategoryCollectionTransfer
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();

        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdLocale($localeTransfer->getIdLocaleOrFail());

        return $this->categoryFacade->getCategoryCollection($categoryCriteriaTransfer);
    }

    /**
     * @param array<int, array<\Generated\Shared\Transfer\ProductCategoryTransfer>> $groupedProductCategoryTransfers
     *
     * @return array<int>
     */
    protected function extractCategoryNodeIds(array $groupedProductCategoryTransfers): array
    {
        $categoryNodeIds = [];

        foreach ($groupedProductCategoryTransfers as $productCategoryTransfers) {
            foreach ($productCategoryTransfers as $productCategoryTransfer) {
                $categoryNodeIds[] = $productCategoryTransfer->getCategoryOrFail()->getCategoryNodeOrFail()->getIdCategoryNodeOrFail();
            }
        }

        return array_unique($categoryNodeIds);
    }
}
