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
     * @var \Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToProductCategoryFacadeInterface
     */
    protected $productCategoryFacade;

    /**
     * @var \Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToLocaleFacadeInterface
     */
    protected $localeFacade;

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
     * @return array<int, array<\Generated\Shared\Transfer\ProductCategoryTransfer>>
     */
    public function getProductCategoriesGroupedByIdProductAbstract(QuoteTransfer $quoteTransfer): array
    {
        $groupedProductCategoryTransfers = [];
        $productCategoryCollectionTransfer = $this->getProductCategoryCollectionForCurrentLocale($quoteTransfer);

        foreach ($productCategoryCollectionTransfer->getProductCategories() as $productCategoryTransfer) {
            $groupedProductCategoryTransfers[$productCategoryTransfer->getFkProductAbstractOrFail()][] = $productCategoryTransfer;
        }

        return $groupedProductCategoryTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryCollectionTransfer
     */
    protected function getProductCategoryCollectionForCurrentLocale(QuoteTransfer $quoteTransfer): ProductCategoryCollectionTransfer
    {
        $productCategoryConditionsTransfer = (new ProductCategoryConditionsTransfer())
            ->setProductAbstractIds($this->extractProductAbstractIdsFromQuote($quoteTransfer))
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
