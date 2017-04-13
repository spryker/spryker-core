<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class ItemGrossSumPriceCalculator implements CalculatorInterface
{

    /**
     * @var \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface
     */
    protected $productOptionGrossSumCalculator;

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface $productOptionGrossSumCalculator
     */
    public function __construct(CalculatorInterface $productOptionGrossSumCalculator)
    {
        $this->productOptionGrossSumCalculator = $productOptionGrossSumCalculator;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $this->productOptionGrossSumCalculator->recalculate($quoteTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->addCalculatedItemGrossAmounts($itemTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addCalculatedItemGrossAmounts(ItemTransfer $itemTransfer)
    {
        $this->assertItemRequirements($itemTransfer);

        if (!$itemTransfer->getUnitGrossPrice()) {
            $itemTransfer->setSumGrossPrice(0);
            return;
        }

        $itemTransfer->setSumGrossPrice($itemTransfer->getUnitGrossPrice() * $itemTransfer->getQuantity());

    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function assertItemRequirements(ItemTransfer $itemTransfer)
    {
        $itemTransfer->requireQuantity();
    }

}
