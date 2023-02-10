<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter;

use Generated\Shared\Transfer\PriceProductTableCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTableViewCollectionTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter\ComparisonStrategy\PriceProductSortingComparisonStrategyInterface;

class PriceProductTableViewSorter implements PriceProductTableViewSorterInterface
{
    /**
     * @var array<\Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter\ComparisonStrategy\PriceProductSortingComparisonStrategyInterface>
     */
    protected array $priceProductSortingComparisonStrategies;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter\ComparisonStrategy\PriceProductSortingComparisonStrategyInterface
     */
    protected PriceProductSortingComparisonStrategyInterface $defaultSortingComparisonStrategy;

    /**
     * @param array<\Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter\ComparisonStrategy\PriceProductSortingComparisonStrategyInterface> $priceProductSortingComparisonStrategies
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter\ComparisonStrategy\PriceProductSortingComparisonStrategyInterface $defaultSortingComparisonStrategy
     */
    public function __construct(
        array $priceProductSortingComparisonStrategies,
        PriceProductSortingComparisonStrategyInterface $defaultSortingComparisonStrategy
    ) {
        $this->priceProductSortingComparisonStrategies = $priceProductSortingComparisonStrategies;
        $this->defaultSortingComparisonStrategy = $defaultSortingComparisonStrategy;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTableViewCollectionTransfer $priceProductTableViewCollectionTransfer
     * @param \Generated\Shared\Transfer\PriceProductTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTableViewCollectionTransfer
     */
    public function sortPriceProductTableViews(
        PriceProductTableViewCollectionTransfer $priceProductTableViewCollectionTransfer,
        PriceProductTableCriteriaTransfer $criteriaTransfer
    ): PriceProductTableViewCollectionTransfer {
        if (!$criteriaTransfer->getOrderBy()) {
            return $priceProductTableViewCollectionTransfer;
        }

        $orderDirection = $criteriaTransfer->getOrderDirection() ?? 'asc';
        $sortAscending = strtolower($orderDirection) === 'asc';

        $sortField = $criteriaTransfer->getOrderByOrFail();

        $sortFunction = $this->getSortFunction($sortField, $sortAscending);

        $priceProductTableViewCollectionTransfer
            ->getPriceProductTableViews()
            ->uasort($sortFunction);

        return $priceProductTableViewCollectionTransfer;
    }

    /**
     * @param string $sortField
     *
     * @return callable
     */
    protected function getValueExtractorFunction(string $sortField): callable
    {
        foreach ($this->priceProductSortingComparisonStrategies as $priceProductSortingComparisonStrategy) {
            if ($priceProductSortingComparisonStrategy->isApplicable($sortField)) {
                return $priceProductSortingComparisonStrategy->getValueExtractorFunction($sortField);
            }
        }

        return $this->defaultSortingComparisonStrategy->getValueExtractorFunction($sortField);
    }

    /**
     * @param string $sortField
     * @param bool $isSortAscending
     *
     * @return callable
     */
    protected function getSortFunction(string $sortField, bool $isSortAscending): callable
    {
        $valueExtractorFunction = $this->getValueExtractorFunction($sortField);

        return function (
            PriceProductTableViewTransfer $priceProductTableViewTransferA,
            PriceProductTableViewTransfer $priceProductTableViewTransferB
        ) use (
            $valueExtractorFunction,
            $isSortAscending
        ) {
            $valueA = $valueExtractorFunction($priceProductTableViewTransferA);
            $valueB = $valueExtractorFunction($priceProductTableViewTransferB);

            if ($valueA === $valueB) {
                return 0;
            }

            if ($isSortAscending) {
                return ($valueA < $valueB) ? -1 : 1;
            }

            return ($valueA < $valueB) ? 1 : -1;
        };
    }
}
