<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\Customer\CustomerConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 */
class EditController extends AbstractController
{
    public const MESSAGE_CUSTOMER_UPDATE_ERROR = 'Customer was not updated.';
    public const MESSAGE_CUSTOMER_UPDATE_SUCCESS = 'Customer was updated successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCustomer = $this->castId($request->query->get(CustomerConstants::PARAM_ID_CUSTOMER));

        $dataProvider = $this->getFactory()->createCustomerUpdateFormDataProvider();
        $form = $this->getFactory()
            ->createCustomerUpdateForm(
                $dataProvider->getData($idCustomer),
                $dataProvider->getOptions($idCustomer)
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customerTransfer = new CustomerTransfer();
            $customerTransfer->fromArray($form->getData(), true);

            $customerResponseTransfer = $this->getFacade()->updateCustomer($customerTransfer);
            if (!$customerResponseTransfer->getIsSuccess()) {
                $this->addErrorMessage(static::MESSAGE_CUSTOMER_UPDATE_ERROR);

                return $this->viewResponse([
                    'form' => $form->createView(),
                    'idCustomer' => $idCustomer,
                ]);
            }

            $this->updateCustomerAddresses($customerTransfer);

            $this->addSuccessMessage(static::MESSAGE_CUSTOMER_UPDATE_SUCCESS);

            return $this->redirectResponse(
                sprintf('/customer/view?%s=%d', CustomerConstants::PARAM_ID_CUSTOMER, $idCustomer)
            );
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'idCustomer' => $idCustomer,
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransfer()
    {
        return new CustomerTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function createAddressTransfer()
    {
        return new AddressTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function updateCustomerAddresses(CustomerTransfer $customerTransfer)
    {
        $defaultBilling = $customerTransfer->getBillingAddress() ?: null;
        if (!$defaultBilling) {
            $this->updateBillingAddress($customerTransfer->getIdCustomer(), $defaultBilling);
        }

        $defaultShipping = $customerTransfer->getShippingAddress() ?: null;
        if (!$defaultShipping) {
            $this->updateShippingAddress($customerTransfer->getIdCustomer(), $defaultShipping);
        }
    }

    /**
     * @param int $idCustomer
     * @param int $defaultBillingAddress
     *
     * @return void
     */
    protected function updateBillingAddress($idCustomer, $defaultBillingAddress)
    {
        $addressTransfer = $this->createCustomAddressTransfer($idCustomer, $defaultBillingAddress);

        if ($this->isValidAddressTransfer($addressTransfer) === false) {
            return;
        }

        $this->getFacade()->setDefaultBillingAddress($addressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return bool
     */
    protected function isValidAddressTransfer(AddressTransfer $addressTransfer)
    {
        return (empty($addressTransfer->getIdCustomerAddress()) === false && $addressTransfer->getFkCustomer() !== null);
    }

    /**
     * @param int $idCustomer
     * @param int $defaultShippingAddress
     *
     * @return void
     */
    protected function updateShippingAddress($idCustomer, $defaultShippingAddress)
    {
        $addressTransfer = $this->createCustomAddressTransfer($idCustomer, $defaultShippingAddress);

        if ($this->isValidAddressTransfer($addressTransfer) === false) {
            return;
        }

        $this->getFacade()->setDefaultShippingAddress($addressTransfer);
    }

    /**
     * @param int $idCustomer
     * @param int $billingAddress
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function createCustomAddressTransfer($idCustomer, $billingAddress)
    {
        $addressTransfer = $this->createAddressTransfer();

        $addressTransfer->setIdCustomerAddress($billingAddress);
        $addressTransfer->setFkCustomer($idCustomer);

        return $addressTransfer;
    }
}
