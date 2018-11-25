<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomersRestApi\Business\Mapper;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\CustomersRestApi\Dependency\Facade\CustomersRestApiToCustomerFacadeInterface;

class CustomerQuoteMapper implements CustomerQuoteMapperInterface
{
    /**
     * @var \Spryker\Zed\CustomersRestApi\Dependency\Facade\CustomersRestApiToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param \Spryker\Zed\CustomersRestApi\Dependency\Facade\CustomersRestApiToCustomerFacadeInterface $customerFacade
     */
    public function __construct(CustomersRestApiToCustomerFacadeInterface $customerFacade)
    {
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapCustomerToQuote(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        $restCustomerTransfer = $restCheckoutRequestAttributesTransfer->getCart()->getCustomer();

        if (!$restCustomerTransfer || !$restCustomerTransfer->getCustomerReference()) {
            return $quoteTransfer;
        }

        $customerResponseTransfer = $this->customerFacade->findCustomerByReference($restCustomerTransfer->getCustomerReference());

        if ($customerResponseTransfer->getHasCustomer() === false) {
            $customerTransfer = (new CustomerTransfer())
                ->fromArray($restCustomerTransfer->toArray(), true)
                ->setIsGuest(true);

            return $quoteTransfer
                ->setCustomer($customerTransfer)
                ->setCustomerReference($customerTransfer->getCustomerReference());
        }

        $quoteTransfer
            ->setCustomerReference($customerResponseTransfer->getCustomerTransfer()->getCustomerReference())
            ->setCustomer($customerResponseTransfer->getCustomerTransfer());

        return $quoteTransfer;
    }
}
