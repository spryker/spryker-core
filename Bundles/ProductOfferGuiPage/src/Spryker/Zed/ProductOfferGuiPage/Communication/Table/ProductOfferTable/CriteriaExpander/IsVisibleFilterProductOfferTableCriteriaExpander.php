<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\CriteriaExpander;


use Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\Filter\TableFilterDataProviderInterface;

class IsVisibleFilterProductOfferTableCriteriaExpander implements FilterProductOfferTableCriteriaExpanderInterface
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

        if (isset($filters[$filterId])) {
            $isVisible = (bool)$filters[$filterId];
            $productOfferTableCriteriaTransfer->setIsVisible($isVisible);
        }

        return $productOfferTableCriteriaTransfer;
    }
}