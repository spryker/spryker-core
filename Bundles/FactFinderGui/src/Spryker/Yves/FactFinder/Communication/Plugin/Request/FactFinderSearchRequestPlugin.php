<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinderGui\Communication\Plugin\Request;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\FactFinder\Business\FactFinderFacade getFacade()
 * @method \Spryker\Zed\FactFinder\Communication\FactFinderCommunicationFactory getFactory()
 */
class FactFinderSearchRequestPlugin extends AbstractPlugin implements CheckoutPreConditionInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function checkCondition(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
//        $this->getFacade()->initPayment($quoteTransfer);
//        $ratepayResponseTransfer = $this->getFacade()->requestPayment($quoteTransfer);
//        $this->checkForErrors($ratepayResponseTransfer, $checkoutResponseTransfer);

        return $checkoutResponseTransfer;
    }

}
