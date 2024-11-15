<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Processor;

use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Triggerer\OmsEventTriggererInterface;

class OrderAmendmentProcessor implements OrderAmendmentProcessorInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderAmendmentOms\Business\Triggerer\OmsEventTriggererInterface
     */
    protected OmsEventTriggererInterface $omsEventTriggerer;

    /**
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Business\Triggerer\OmsEventTriggererInterface $omsEventTriggerer
     */
    public function __construct(OmsEventTriggererInterface $omsEventTriggerer)
    {
        $this->omsEventTriggerer = $omsEventTriggerer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return void
     */
    public function startOrderAmendment(CartReorderTransfer $cartReorderTransfer): void
    {
        if ($cartReorderTransfer->getQuoteOrFail()->getAmendmentOrderReference() === null) {
            return;
        }

        $this->omsEventTriggerer->triggerStartOrderAmendmentEvent($cartReorderTransfer->getQuoteOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function cancelOrderAmendment(QuoteTransfer $quoteTransfer): void
    {
        if ($quoteTransfer->getAmendmentOrderReference() === null) {
            return;
        }

        $this->omsEventTriggerer->triggerCancelOrderAmendmentEvent($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function finishOrderAmendment(QuoteTransfer $quoteTransfer): void
    {
        if ($quoteTransfer->getAmendmentOrderReference() === null) {
            return;
        }

        $this->omsEventTriggerer->triggerFinishSalesOrderAmendmentEvent($quoteTransfer);
    }
}
