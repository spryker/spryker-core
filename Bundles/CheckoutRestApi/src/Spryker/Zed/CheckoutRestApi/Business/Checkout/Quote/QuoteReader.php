<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartsRestApiFacadeInterface;

class QuoteReader implements QuoteReaderInterface
{
    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartsRestApiFacadeInterface
     */
    protected $cartsRestApiFacade;

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade
     */
    public function __construct(CheckoutRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade)
    {
        $this->cartsRestApiFacade = $cartsRestApiFacade;
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
            ->setUuid($restCartTransfer->getId())
            ->setCustomerReference($restCartTransfer->getCustomer()->getCustomerReference());

        $quoteResponseTransfer = $this->cartsRestApiFacade->findCustomerQuoteByUuid($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return null;
        }

        return $quoteResponseTransfer->getQuoteTransfer();
    }
}
