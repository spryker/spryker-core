<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business\Calculator;

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
        $quoteTransfer = (new QuoteTransfer())->fromArray($calculableObjectTransfer->toArray(), true);
        $quoteTransfer = $this->discountFacade->calculateDiscounts($quoteTransfer);
        $calculableObjectTransfer->fromArray($quoteTransfer->toArray(), true);

        return $calculableObjectTransfer;
    }
}
