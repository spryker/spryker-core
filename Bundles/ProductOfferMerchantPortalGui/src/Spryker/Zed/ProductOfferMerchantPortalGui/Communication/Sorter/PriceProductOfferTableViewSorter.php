<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Sorter;

use Generated\Shared\Transfer\PriceProductOfferTableCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductOfferTableViewCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferTableViewTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Sorter\ComparisonStrategy\PriceProductOfferTableView\PriceProductOfferTableViewComparisonStrategyInterface;

class PriceProductOfferTableViewSorter implements PriceProductOfferTableViewSorterInterface
{
    /**
     * @var string
     */
    protected const DIRECTION_ASC = 'asc';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Sorter\ComparisonStrategy\PriceProductOfferTableView\PriceProductOfferTableViewComparisonStrategyInterface[]
     */
    protected $priceProductComparisonStrategies;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Sorter\ComparisonStrategy\PriceProductOfferTableView\PriceProductOfferTableViewComparisonStrategyInterface
     */
    protected $defaultPriceProductComparisonStrategy;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Sorter\ComparisonStrategy\PriceProductOfferTableView\PriceProductOfferTableViewComparisonStrategyInterface $defaultPriceProductComparisonStrategy
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Sorter\ComparisonStrategy\PriceProductOfferTableView\PriceProductOfferTableViewComparisonStrategyInterface[] $priceProductComparisonStrategies
     */
    public function __construct(
        PriceProductOfferTableViewComparisonStrategyInterface $defaultPriceProductComparisonStrategy,
        array $priceProductComparisonStrategies
    ) {
        $this->priceProductComparisonStrategies = $priceProductComparisonStrategies;
        $this->defaultPriceProductComparisonStrategy = $defaultPriceProductComparisonStrategy;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferTableViewCollectionTransfer $priceProductOfferTableViewCollectionTransfer
     * @param \Generated\Shared\Transfer\PriceProductOfferTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferTableViewCollectionTransfer
     */
    public function sortPriceProductOfferTableViews(
        PriceProductOfferTableViewCollectionTransfer $priceProductOfferTableViewCollectionTransfer,
        PriceProductOfferTableCriteriaTransfer $criteriaTransfer
    ): PriceProductOfferTableViewCollectionTransfer {
        if (!$criteriaTransfer->getOrderBy()) {
            return $priceProductOfferTableViewCollectionTransfer;
        }

        $orderDirection = $criteriaTransfer->getOrderDirection();
        if (!$orderDirection) {
            $orderDirection = static::DIRECTION_ASC;
        }

        $valueExtractorFunction = $this->getValueExtractorFunctionByStrategy(
            $criteriaTransfer->getOrderByOrFail()
        );
        $sortAscending = strtolower($orderDirection) === static::DIRECTION_ASC;

        $sortFunction = $this->createSortFunction($valueExtractorFunction, $sortAscending);

        $priceProductOfferTableViewCollectionTransfer
            ->getPriceProductOfferTableViews()
            ->uasort($sortFunction);

        return $priceProductOfferTableViewCollectionTransfer;
    }

    /**
     * @param string $sortField
     *
     * @return callable
     */
    protected function getValueExtractorFunctionByStrategy(string $sortField): callable
    {
        foreach ($this->priceProductComparisonStrategies as $priceProductComparisonStrategy) {
            if ($priceProductComparisonStrategy->isApplicable($sortField)) {
                return $priceProductComparisonStrategy->getValueExtractorFunction($sortField);
            }
        }

        return $this->defaultPriceProductComparisonStrategy->getValueExtractorFunction($sortField);
    }

    /**
     * @param callable $valueExtractorFunction
     * @param bool $sortAscending
     *
     * @return callable
     */
    protected function createSortFunction(
        callable $valueExtractorFunction,
        bool $sortAscending
    ): callable {
        return function (
            PriceProductOfferTableViewTransfer $priceProductOfferTableViewTransferA,
            PriceProductOfferTableViewTransfer $priceProductOfferTableViewTransferB
        ) use (
            $valueExtractorFunction,
            $sortAscending
        ) {
            $valueA = $valueExtractorFunction($priceProductOfferTableViewTransferA);
            $valueB = $valueExtractorFunction($priceProductOfferTableViewTransferB);

            if ($valueA == $valueB) {
                return 0;
            }

            if ($sortAscending) {
                return ($valueA < $valueB) ? -1 : 1;
            }

            return ($valueA < $valueB) ? 1 : -1;
        };
    }
}
