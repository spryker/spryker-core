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
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface getRepository()
 */
class EditController extends AbstractController
{
    /**
     * @var string
     */
    public const MESSAGE_CUSTOMER_UPDATE_ERROR = 'Customer was not updated.';

    /**
     * @var string
     */
    public const MESSAGE_CUSTOMER_UPDATE_SUCCESS = 'Customer was updated successfully.';

    /**
     * @var string
     */
    protected const MESSAGE_ERROR_CUSTOMER_NOT_EXIST = 'Customer with id `%s` does not exist';

    /**
     * @var string
     */
    protected const URL_CUSTOMER_LIST_PAGE = '/customer';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $idCustomer = $this->castId($request->query->get(CustomerConstants::PARAM_ID_CUSTOMER));

        $dataProvider = $this->getFactory()->createCustomerUpdateFormDataProvider();
        $formData = $dataProvider->getData($idCustomer);

        if ($formData === []) {
            $this->addErrorMessage(static::MESSAGE_ERROR_CUSTOMER_NOT_EXIST, ['%s' => $idCustomer]);

            return $this->redirectResponse(static::URL_CUSTOMER_LIST_PAGE);
        }

        $form = $this->getFactory()
            ->createCustomerUpdateForm(
                $formData,
                $dataProvider->getOptions($idCustomer),
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customerTransfer = new CustomerTransfer();
            $customerTransfer->fromArray($form->getData(), true);
            $customerTransfer->setIsEditedInBackoffice(true);

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
                sprintf('/customer/view?%s=%d', CustomerConstants::PARAM_ID_CUSTOMER, $idCustomer),
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
        $defaultBilling = $customerTransfer->getDefaultBillingAddress() ?: null;
        if (!$defaultBilling) {
            $this->updateBillingAddress($customerTransfer->getIdCustomer(), (int)$defaultBilling);
        }

        $defaultShipping = $customerTransfer->getDefaultShippingAddress() ?: null;
        if (!$defaultShipping) {
            $this->updateShippingAddress($customerTransfer->getIdCustomer(), (int)$defaultShipping);
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
        return (!$addressTransfer->getIdCustomerAddress() === false && $addressTransfer->getFkCustomer() !== null);
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
