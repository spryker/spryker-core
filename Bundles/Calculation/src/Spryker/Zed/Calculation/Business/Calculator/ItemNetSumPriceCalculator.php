<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class ItemNetSumPriceCalculator implements CalculatorInterface
{

    /**
     * @var \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface
     */
    protected $productOptionNetSumCalculator;

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface $productOptionNetSumCalculator
     */
    public function __construct(CalculatorInterface $productOptionNetSumCalculator)
    {
        $this->productOptionNetSumCalculator = $productOptionNetSumCalculator;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $this->productOptionNetSumCalculator->recalculate($quoteTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->addCalculatedItemNetAmounts($itemTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addCalculatedItemNetAmounts(ItemTransfer $itemTransfer)
    {
        $this->assertItemRequirements($itemTransfer);

        if (!$itemTransfer->getUnitNetPrice()) {
            $itemTransfer->setSumNetPrice(0);
            return;
        }

        $itemTransfer->setSumNetPrice($itemTransfer->getUnitNetPrice() * $itemTransfer->getQuantity());

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
