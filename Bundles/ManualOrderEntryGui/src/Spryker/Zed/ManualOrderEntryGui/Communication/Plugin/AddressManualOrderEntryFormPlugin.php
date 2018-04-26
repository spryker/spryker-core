<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Address\AddressCollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class AddressManualOrderEntryFormPlugin extends AbstractPlugin implements ManualOrderEntryFormPluginInterface
{
    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCustomerFacadeInterface
     */
    protected $customerFacade;

    public function __construct()
    {
        $this->customerFacade = $this->getFactory()->getCustomerFacade();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return AddressCollectionType::TYPE_NAME;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(Request $request, QuoteTransfer $quoteTransfer): FormInterface
    {
        return $this->getFactory()->createAddressCollectionForm($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handleData(QuoteTransfer $quoteTransfer, &$form, Request $request): QuoteTransfer
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

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return bool
     */
    public function isFormPreFilled(?QuoteTransfer $quoteTransfer = null): bool
    {
        return false;
    }
}
