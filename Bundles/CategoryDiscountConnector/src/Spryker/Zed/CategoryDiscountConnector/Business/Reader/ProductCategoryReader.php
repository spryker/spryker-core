<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDiscountConnector\Business\Reader;

use Generated\Shared\Transfer\ProductCategoryCollectionTransfer;
use Generated\Shared\Transfer\ProductCategoryConditionsTransfer;
use Generated\Shared\Transfer\ProductCategoryCriteriaTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToLocaleFacadeInterface;
use Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToProductCategoryFacadeInterface;

class ProductCategoryReader implements ProductCategoryReaderInterface
{
    /**
     * @var array<int, list<\Generated\Shared\Transfer\ProductCategoryTransfer>>
     */
    protected static array $productCategoryTransfersGroupedByIdProductAbstract = [];

    /**
     * @var \Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToProductCategoryFacadeInterface
     */
    protected CategoryDiscountConnectorToProductCategoryFacadeInterface $productCategoryFacade;

    /**
     * @var \Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToLocaleFacadeInterface
     */
    protected CategoryDiscountConnectorToLocaleFacadeInterface $localeFacade;

    /**
     * @param \Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToProductCategoryFacadeInterface $productCategoryFacade
     * @param \Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        CategoryDiscountConnectorToProductCategoryFacadeInterface $productCategoryFacade,
        CategoryDiscountConnectorToLocaleFacadeInterface $localeFacade
    ) {
        $this->productCategoryFacade = $productCategoryFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<int, list<\Generated\Shared\Transfer\ProductCategoryTransfer>>
     */
    public function getProductCategoriesGroupedByIdProductAbstract(QuoteTransfer $quoteTransfer): array
    {
        $productAbstractIds = $this->extractProductAbstractIdsFromQuote($quoteTransfer);
        $productAbstractIds = array_diff($productAbstractIds, array_keys(static::$productCategoryTransfersGroupedByIdProductAbstract));
        if (!$productAbstractIds) {
            return static::$productCategoryTransfersGroupedByIdProductAbstract;
        }

        $productCategoryCollectionTransfer = $this->getProductCategoryCollectionForCurrentLocale($productAbstractIds);

        foreach ($productCategoryCollectionTransfer->getProductCategories() as $productCategoryTransfer) {
            static::$productCategoryTransfersGroupedByIdProductAbstract[$productCategoryTransfer->getFkProductAbstractOrFail()][] = $productCategoryTransfer;
        }

        return static::$productCategoryTransfersGroupedByIdProductAbstract;
    }

    /**
     * @param list<int> $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductCategoryCollectionTransfer
     */
    protected function getProductCategoryCollectionForCurrentLocale(array $productAbstractIds): ProductCategoryCollectionTransfer
    {
        $productCategoryConditionsTransfer = (new ProductCategoryConditionsTransfer())
            ->setProductAbstractIds($productAbstractIds)
            ->addIdLocale($this->localeFacade->getCurrentLocale()->getIdLocaleOrFail());

        $productCategoryCriteriaTransfer = (new ProductCategoryCriteriaTransfer())
            ->setProductCategoryConditions($productCategoryConditionsTransfer);

        return $this->productCategoryFacade->getProductCategoryCollection($productCategoryCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<int>
     */
    protected function extractProductAbstractIdsFromQuote(QuoteTransfer $quoteTransfer): array
    {
        $productAbstractIds = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $productAbstractIds[] = $itemTransfer->getIdProductAbstractOrFail();
        }

        return array_unique($productAbstractIds);
    }
}
