<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToDiscountInterface;

class DiscountCalculator implements DiscountCalculatorInterface
{
    /**
     * @var \Spryker\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToDiscountInterface
     */
    protected $discountFacade;

    /**
     * @param \Spryker\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToDiscountInterface $discountFacade
     */
    public function __construct(DiscountCalculationToDiscountInterface $discountFacade)
    {
        $this->discountFacade = $discountFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $calculableObjectTransfer->requireStore();

        $this->removeCalculatedDiscountsForItems($calculableObjectTransfer);

        $quoteTransfer = $this->createQuoteTransfer($calculableObjectTransfer);

        $quoteTransfer = $this->discountFacade->calculateDiscounts($quoteTransfer);

        return $this->addCalculatedDiscounts($calculableObjectTransfer, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function removeCalculatedDiscountsForItems(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setCalculatedDiscounts(new ArrayObject());
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(CalculableObjectTransfer $calculableObjectTransfer): QuoteTransfer
    {
        $itemTransfers = $calculableObjectTransfer->getItems();
        $originalOrderTransfer = $calculableObjectTransfer->getOriginalOrder();

        // speedups next fromArray() execution
        $calculableObjectTransfer->setItems(new ArrayObject());
        $calculableObjectTransfer->setOriginalOrder(null);

        $quoteTransfer = (new QuoteTransfer())
            ->fromArray($calculableObjectTransfer->toArray(), true)
            ->setItems($itemTransfers)
            ->setOriginalOrder($originalOrderTransfer);

        if ($originalOrderTransfer) {
            $quoteTransfer->setOrderReference($originalOrderTransfer->getOrderReference());
        }

        $calculableObjectTransfer->setOriginalOrder($originalOrderTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function addCalculatedDiscounts(CalculableObjectTransfer $calculableObjectTransfer, QuoteTransfer $quoteTransfer): CalculableObjectTransfer
    {
        $itemTransfers = $quoteTransfer->getItems();
        $originalOrderTransfer = $calculableObjectTransfer->getOriginalOrder();
        // speedups next fromArray() execution
        $quoteTransfer->setItems(new ArrayObject());
        $quoteTransfer->setOriginalOrder(null);

        return $calculableObjectTransfer->fromArray($quoteTransfer->toArray(), true)
            ->setItems($itemTransfers)
            ->setOriginalOrder($originalOrderTransfer);
    }
}
