<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartsRestApiFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCustomerFacadeInterface;

class QuoteReader implements QuoteReaderInterface
{
    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartsRestApiFacadeInterface
     */
    protected $cartsRestApiFacade;

    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCustomerFacadeInterface $customerFacade
     */
    public function __construct(
        CheckoutRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade,
        CheckoutRestApiToCustomerFacadeInterface $customerFacade
    ) {
        $this->cartsRestApiFacade = $cartsRestApiFacade;
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findCustomerQuoteByUuid(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): ?QuoteTransfer
    {
        if (!$restCheckoutRequestAttributesTransfer->getCustomer()
            || !$restCheckoutRequestAttributesTransfer->getCustomer()->getCustomerReference()) {
            return null;
        }

        $quoteTransfer = (new QuoteTransfer())
            ->setUuid($restCheckoutRequestAttributesTransfer->getIdCart());

        $quoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid($quoteTransfer);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return null;
        }

        $retrievedQuoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $customerReference = $restCheckoutRequestAttributesTransfer->getCustomer()->getCustomerReference();
        if ($retrievedQuoteTransfer->getCustomerReference() !== $customerReference) {
            return null;
        }

        $customerResponseTransfer = $this->customerFacade->findCustomerByReference($customerReference);
        if (!$customerResponseTransfer->getIsSuccess()) {
            return null;
        }

        return $retrievedQuoteTransfer->setCustomer($customerResponseTransfer->getCustomerTransfer());
    }
}
