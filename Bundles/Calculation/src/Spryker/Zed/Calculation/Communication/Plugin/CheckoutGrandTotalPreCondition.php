<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @deprecated Will be removed in the next major version.
 *
 * @method \Spryker\Zed\Calculation\Business\CalculationFacadeInterface getFacade()
 * @method \Spryker\Zed\Calculation\Communication\CalculationCommunicationFactory getFactory()
 * @method \Spryker\Zed\Checkout\CheckoutConfig getConfig()
 */
class CheckoutGrandTotalPreCondition extends AbstractPlugin implements CheckoutPreConditionInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        return $this->getFacade()->validateCheckoutGrandTotal($quoteTransfer, $checkoutResponseTransfer);
    }
}
