<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Plugin;

use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class AddressManualOrderEntryFormPlugin extends AbstractManualOrderEntryFormPlugin implements ManualOrderEntryFormPluginInterface
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $dataTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(Request $request, $dataTransfer = null)
    {
        return $this->getFactory()->createAddressCollectionForm($dataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function handleData($quoteTransfer, &$form, $request)
    {
        if ($quoteTransfer->getShippingAddress()->getIdCustomerAddress()) {
            $addressTransfer = $quoteTransfer->getShippingAddress();
            $addressTransfer->setFkCustomer($quoteTransfer->getCustomer()->getIdCustomer());

            $addressTransfer = $this->customerFacade->getAddress($addressTransfer);
            $quoteTransfer->setShippingAddress($addressTransfer);
        }

        if ($quoteTransfer->getBillingAddress()->getIdCustomerAddress()) {
            $addressTransfer = $quoteTransfer->getBillingAddress();
            $addressTransfer->setFkCustomer($quoteTransfer->getCustomer()->getIdCustomer());

            $addressTransfer = $this->customerFacade->getAddress($addressTransfer);
            $quoteTransfer->setBillingAddress($addressTransfer);
        }

        return $quoteTransfer;
    }
}
