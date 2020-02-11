<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Calculator;

use Generated\Shared\Transfer\DiscountTransfer;
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
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[]|\Spryker\Zed\Discount\Dependency\Plugin\DiscountAmountCalculatorPluginInterface[] $calculatorPlugins
     * @param \Spryker\Zed\DiscountExtension\Dependency\Plugin\CollectedDiscountGroupingStrategyPluginInterface[] $collectedDiscountGroupingPlugins
     * @param \Spryker\Zed\Discount\Business\Filter\DiscountableItemFilterInterface $discountableItemFilter
     */
    public function __construct(
        SpecificationBuilderInterface $collectorBuilder,
        DiscountToMessengerInterface $messengerFacade,
        DistributorInterface $distributor,
        array $calculatorPlugins,
        array $collectedDiscountGroupingPlugins,
        DiscountableItemFilterInterface $discountableItemFilter
    ) {
        parent::__construct($collectorBuilder, $messengerFacade, $distributor, $calculatorPlugins, $collectedDiscountGroupingPlugins);
        $this->discountableItemFilter = $discountableItemFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    protected function collectItems(QuoteTransfer $quoteTransfer, DiscountTransfer $discountTransfer)
    {
        $collectedItems = parent::collectItems($quoteTransfer, $discountTransfer);

        $collectedDiscountTransfer = $this->createCollectedDiscountTransfer($discountTransfer, $collectedItems);

        $filteredDiscountTransfer = $this->discountableItemFilter->filter($collectedDiscountTransfer);

        return (array)$filteredDiscountTransfer->getDiscountableItems();
    }
}
