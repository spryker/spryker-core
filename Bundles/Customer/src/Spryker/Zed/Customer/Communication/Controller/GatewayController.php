<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\Business\Exception\AddressNotFoundException;
use Spryker\Zed\Customer\Business\Exception\CustomerNotFoundException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function registerAction(CustomerTransfer $customerTransfer)
    {
        return $this->getFacade()
            ->registerCustomer($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function confirmRegistrationAction(CustomerTransfer $customerTransfer)
    {
        return $this->getFacade()
            ->confirmRegistration($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function sendPasswordRestoreMailAction(CustomerTransfer $customerTransfer)
    {
        return $this->getFacade()
            ->sendPasswordRestoreMail($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function restorePasswordAction(CustomerTransfer $customerTransfer)
    {
        return $this->getFacade()
            ->restorePassword($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function deleteAction(CustomerTransfer $customerTransfer)
    {
        $result = $this->getFacade()
            ->deleteCustomer($customerTransfer);
        $this->setSuccess($result);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function hasCustomerWithEmailAndPasswordAction(CustomerTransfer $customerTransfer)
    {
        $isAuthorized = $this->getFacade()
            ->tryAuthorizeCustomerByEmailAndPassword($customerTransfer);

        $result = new CustomerResponseTransfer();
        if ($isAuthorized === true) {
            $result->setCustomerTransfer($this->getFacade()->getCustomer($customerTransfer));
        }

        $result->setHasCustomer($isAuthorized);

        $this->setSuccess($isAuthorized);

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function customerAction(CustomerTransfer $customerTransfer)
    {
        try {
            return $this->getFacade()
                ->getCustomer($customerTransfer);
        } catch (CustomerNotFoundException $e) {
            return new CustomerTransfer();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function updateAction(CustomerTransfer $customerTransfer)
    {
        $response = $this->getFacade()
            ->updateCustomer($customerTransfer);
        $this->setSuccess($response->getIsSuccess());

        return $response;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function updatePasswordAction(CustomerTransfer $customerTransfer)
    {
        $response = $this->getFacade()
            ->updateCustomerPassword($customerTransfer);
        $this->setSuccess($response->getIsSuccess());

        return $response;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function addressAction(AddressTransfer $addressTransfer)
    {
        try {
            $addressTransfer = $this->getFacade()
                ->getAddress($addressTransfer);
        } catch (AddressNotFoundException $e) {
            $this->setSuccess(false);
            $addressTransfer = new AddressTransfer();
        }

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressesTransfer
     */
    public function addressesAction(CustomerTransfer $customerTransfer)
    {
        $addressesTransfer = $this->getFacade()
            ->getAddresses($customerTransfer);

        return $addressesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function updateAddressAction(AddressTransfer $addressTransfer)
    {
        $addressTransfer = $this->getFacade()
            ->updateAddress($addressTransfer);

        $this->setSuccess(true);

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function updateAddressAndCustomerDefaultAddressesAction(AddressTransfer $addressTransfer)
    {
        $customerTransfer = $this->getFacade()
            ->updateAddressAndCustomerDefaultAddresses($addressTransfer);

        $isSuccess = ($customerTransfer !== null);
        $this->setSuccess($isSuccess);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function createAddressAndUpdateCustomerDefaultAddressesAction(AddressTransfer $addressTransfer)
    {
        $customerTransfer = $this->getFacade()
            ->createAddressAndUpdateCustomerDefaultAddresses($addressTransfer);

        $isSuccess = ($customerTransfer !== null);
        $this->setSuccess($isSuccess);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function newAddressAction(AddressTransfer $addressTransfer)
    {
        $addressTransfer = $this->getFacade()
            ->createAddress($addressTransfer);
        $this->setSuccess($addressTransfer->getIdCustomerAddress() > 0);

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    public function deleteAddressAction(AddressTransfer $addressTransfer)
    {
        try {
            $this->getFacade()->deleteAddress($addressTransfer);

            return $addressTransfer;
        } catch (AddressNotFoundException $e) {
            $this->setSuccess(false);
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function defaultBillingAddressAction(AddressTransfer $addressTransfer)
    {
        $result = $this->getFacade()
            ->setDefaultBillingAddress($addressTransfer);
        $this->setSuccess($result);

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function defaultShippingAddressAction(AddressTransfer $addressTransfer)
    {
        $result = $this->getFacade()
            ->setDefaultShippingAddress($addressTransfer);
        $this->setSuccess($result);

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function anonymizeCustomerAction(CustomerTransfer $customerTransfer)
    {
        $this->getFacade()
            ->anonymizeCustomer($customerTransfer);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function findCustomerByReferenceAction(CustomerTransfer $customerTransfer): CustomerResponseTransfer
    {
        return $this->getFacade()->findCustomerByReference($customerTransfer->getCustomerReference());
    }
}
