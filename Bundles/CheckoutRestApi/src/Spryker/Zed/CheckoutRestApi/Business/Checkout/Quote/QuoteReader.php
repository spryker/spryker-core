<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToQuoteFacadeInterface;

class QuoteReader implements QuoteReaderInterface
{
    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(CheckoutRestApiToQuoteFacadeInterface $quoteFacade)
    {
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findCustomerQuote(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): ?QuoteTransfer
    {
        $restCartTransfer = $restCheckoutRequestAttributesTransfer->getCart();
        if (!$restCartTransfer
            || !$restCartTransfer->getCustomer()
            || !$restCartTransfer->getCustomer()->getCustomerReference()) {
            return null;
        }

        $quoteTransfer = (new QuoteTransfer())
            ->setUuid($restCartTransfer->getId());

        $quoteResponseTransfer = $this->quoteFacade->findQuoteByUuid($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return null;
        }

        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();

        if ($quoteTransfer->getCustomerReference() !== $restCartTransfer->getCustomer()->getCustomerReference()) {
            return null;
        }

        return $quoteResponseTransfer->getQuoteTransfer();
    }
}
