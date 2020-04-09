<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\CriteriaExpander;


use Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\Filter\TableFilterDataProviderInterface;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\Filter\StockProductOfferTableFilterDataProvider;

class StockFilterProductOfferTableCriteriaExpander implements FilterProductOfferTableCriteriaExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Communication\Table\Filter\TableFilterDataProviderInterface
     */
    protected $tableFilterDataProvider;

    /**
     * @param \Spryker\Zed\ProductOfferGuiPage\Communication\Table\Filter\TableFilterDataProviderInterface $tableFilterDataProvider
     */
    public function __construct(TableFilterDataProviderInterface $tableFilterDataProvider)
    {
        $this->tableFilterDataProvider = $tableFilterDataProvider;
    }

    /**
     * @param array $filters
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer
     */
    public function expandProductOfferTableCriteria(
        array $filters,
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): ProductOfferTableCriteriaTransfer {
        $filterId = $this->tableFilterDataProvider->getFilterData()->getId();

        if (!isset($filters[$filterId])) {
            return $productOfferTableCriteriaTransfer;
        }

        if ($filters[$filterId] === StockProductOfferTableFilterDataProvider::OPTION_ALWAYS_IN_STOCK_VALUE) {
            $productOfferTableCriteriaTransfer->setIsNeverOutOfStock(true);
        }

        if ($filters[$filterId] === StockProductOfferTableFilterDataProvider::OPTION_HAS_STOCK_VALUE) {
            $productOfferTableCriteriaTransfer->setHasStock(true);
        }

        if ($filters[$filterId] === StockProductOfferTableFilterDataProvider::OPTION_OUT_OF_STOCK_VALUE) {
            $productOfferTableCriteriaTransfer->setHasStock(false);
        }

        return $productOfferTableCriteriaTransfer;
    }
}