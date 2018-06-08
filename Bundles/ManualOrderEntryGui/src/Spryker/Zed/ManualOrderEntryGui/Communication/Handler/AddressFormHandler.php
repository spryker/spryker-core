<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Handler;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCustomerFacadeInterface;
use Symfony\Component\HttpFoundation\Request;

class AddressFormHandler implements FormHandlerInterface
{
    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCustomerFacadeInterface $customerFacade
     */
    public function __construct(
        ManualOrderEntryGuiToCustomerFacadeInterface $customerFacade
    ) {
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handle(QuoteTransfer $quoteTransfer, &$form, Request $request): QuoteTransfer
    {
        if ($quoteTransfer->getShippingAddress()->getIdCustomerAddress()) {
            $addressTransfer = $quoteTransfer->getShippingAddress();
            $addressTransfer->setFkCustomer($quoteTransfer->getCustomer()->getIdCustomer());

            $addressTransfer = $this->customerFacade->getAddress($addressTransfer);
            $quoteTransfer->setShippingAddress($addressTransfer);
        }

        if ($quoteTransfer->getBillingSameAsShipping()) {
            $quoteTransfer->setBillingAddress($quoteTransfer->getShippingAddress());
        } elseif ($quoteTransfer->getBillingAddress()->getIdCustomerAddress()) {
            $addressTransfer = $quoteTransfer->getBillingAddress();
            $addressTransfer->setFkCustomer($quoteTransfer->getCustomer()->getIdCustomer());

            $addressTransfer = $this->customerFacade->getAddress($addressTransfer);
            $quoteTransfer->setBillingAddress($addressTransfer);
        }

        return $quoteTransfer;
    }
}
