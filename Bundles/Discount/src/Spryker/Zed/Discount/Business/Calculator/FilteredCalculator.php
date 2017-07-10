<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\Distributor\DistributorInterface;
use Spryker\Zed\Discount\Business\Filter\DiscountableItemFilterInterface;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface;

class FilteredCalculator extends Calculator implements CalculatorInterface
{

    /**
     * @var \Spryker\Zed\Discount\Business\Filter\DiscountableItemFilterInterface
     */
    protected $discountableItemFilter;

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface $collectorBuilder
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface $messengerFacade
     * @param \Spryker\Zed\Discount\Business\Distributor\DistributorInterface $distributor
     * @param array $calculatorPlugins
     * @param \Spryker\Zed\Discount\Business\Filter\DiscountableItemFilterInterface $discountableItemFilter
     */
    public function __construct(
        SpecificationBuilderInterface $collectorBuilder,
        DiscountToMessengerInterface $messengerFacade,
        DistributorInterface $distributor,
        array $calculatorPlugins,
        DiscountableItemFilterInterface $discountableItemFilter
    ) {
        parent::__construct($collectorBuilder, $messengerFacade, $distributor, $calculatorPlugins);
        $this->discountableItemFilter = $discountableItemFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer[] $discounts
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CollectedDiscountTransfer[]
     */
    public function calculate(array $discounts, QuoteTransfer $quoteTransfer)
    {
        $collectedDiscounts = $this->calculateDiscountAmount($discounts, $quoteTransfer);
        $collectedDiscounts = $this->filterExclusiveDiscounts($collectedDiscounts);
        $collectedDiscounts = $this->filterCollectedDiscounts($collectedDiscounts);

        $this->distributeDiscountAmount($collectedDiscounts);

        return $collectedDiscounts;
    }

    /**
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer[] $collectedDiscounts
     *
     * @return \Generated\Shared\Transfer\CollectedDiscountTransfer[]
     */
    protected function filterCollectedDiscounts(array $collectedDiscounts)
    {
        $filteredCollectedDiscounts = [];
        foreach ($collectedDiscounts as $collectedDiscountTransfer) {
            $filteredCollectedDiscountTransfer = $this->discountableItemFilter->filter($collectedDiscountTransfer);
            if (!$filteredCollectedDiscountTransfer || count($filteredCollectedDiscountTransfer->getDiscountableItems()) === 0) {
                continue;
            }
            $filteredCollectedDiscounts[] = $filteredCollectedDiscountTransfer;
        }
        return $filteredCollectedDiscounts;
    }

}
