<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCalculationConnector\Dependency\Facade;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class DiscountCalculationToDiscountBridge implements DiscountCalculationToDiscountInterface
{
    /**
     * @var \Spryker\Zed\Discount\Business\DiscountFacadeInterface
     */
    protected $discountFacade;

    /**
     * @param \Spryker\Zed\Discount\Business\DiscountFacadeInterface $discountFacade
     */
    public function __construct($discountFacade)
    {
        $this->discountFacade = $discountFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function calculateDiscounts(QuoteTransfer $quoteTransfer)
    {
        return $this->discountFacade->calculateDiscounts($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateDiscounts(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        return $this->discountFacade->recalculateDiscounts($calculableObjectTransfer);
    }
}
