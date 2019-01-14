<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\QuoteRequest\Business\QuoteRequestFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function createQuoteRequestFromQuoteAction(QuoteTransfer $quoteTransfer): QuoteRequestTransfer
    {
        return $this->getFacade()->createQuoteRequestFromQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function getCustomerQuoteRequestCollection(CustomerTransfer $customerTransfer): QuoteRequestCollectionTransfer
    {
        return $this->getFacade()->getCustomerQuoteRequestCollection($customerTransfer);
    }
}
