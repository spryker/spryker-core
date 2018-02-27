<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteMergeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Quote\Business\QuoteFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByCustomerAction(CustomerTransfer $customerTransfer)
    {
        return $this->getFacade()->findQuoteByCustomer($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function persistQuoteAction(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->persistQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteQuoteAction(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->deleteQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteMergeTransfer $quoteMergeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function mergeQuotesAction(QuoteMergeTransfer $quoteMergeTransfer)
    {
        return $this->getFacade()->mergeQuotes($quoteMergeTransfer);
    }
}
