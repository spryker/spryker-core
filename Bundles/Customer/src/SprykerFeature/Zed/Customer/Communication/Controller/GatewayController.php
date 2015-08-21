<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\CustomerAddressTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerFeature\Zed\Customer\Business\CustomerFacade;
use SprykerFeature\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method CustomerFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function registerAction(CustomerTransfer $customerTransfer)
    {
        return $this->getFacade()
            ->registerCustomer($customerTransfer)
        ;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function confirmRegistrationAction(CustomerTransfer $customerTransfer)
    {
        return $this->getFacade()
            ->confirmRegistration($customerTransfer)
        ;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function forgotPasswordAction(CustomerTransfer $customerTransfer)
    {
        return $this->getFacade()
            ->forgotPassword($customerTransfer)
        ;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function restorePasswordAction(CustomerTransfer $customerTransfer)
    {
        return $this->getFacade()
            ->restorePassword($customerTransfer)
        ;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     */
    public function deleteAction(CustomerTransfer $customerTransfer)
    {
        $result = $this->getFacade()
            ->deleteCustomer($customerTransfer)
        ;
        $this->setSuccess($result);

        return;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     * 
     * @return CustomerResponseTransfer
     */
    public function hasCustomerWithEmailAndPasswordAction(CustomerTransfer $customerTransfer)
    {
        $isAuthorized = $this->getFacade()
            ->tryAuthorizeCustomerByEmailAndPassword($customerTransfer)
        ;

        $result = new CustomerResponseTransfer();
        if (true === $isAuthorized) {
            $result->setCustomerTransfer($this->getFacade()->getCustomer($customerTransfer));
        }

        $result->setHasCustomer($isAuthorized);

        $this->setSuccess($isAuthorized);

        return $result;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function customerAction(CustomerTransfer $customerTransfer)
    {
        $result = $this->getFacade()
            ->getCustomer($customerTransfer)
        ;

        return $result;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function updateAction(CustomerTransfer $customerTransfer)
    {
        $result = $this->getFacade()
            ->updateCustomer($customerTransfer)
        ;
        $this->setSuccess($result);

        return $customerTransfer;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function addressAction(CustomerAddressTransfer $addressTransfer)
    {
        $addressTransfer = $this->getFacade()
            ->getAddress($addressTransfer)
        ;
        if (null === $addressTransfer) {
            $this->setSuccess(false);

            return;
        }

        return $addressTransfer;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function updateAddressAction(CustomerAddressTransfer $addressTransfer)
    {
        $result = $this->getFacade()
            ->updateAddress($addressTransfer)
        ;
        $this->setSuccess($result);

        return $addressTransfer;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function newAddressAction(CustomerAddressTransfer $addressTransfer)
    {
        $addressTransfer = $this->getFacade()
            ->createAddress($addressTransfer)
        ;
        $this->setSuccess($addressTransfer->getIdCustomerAddress() > 0);

        return $addressTransfer;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function deleteAddressAction(CustomerAddressTransfer $addressTransfer)
    {
        $result = $this->getFacade()
            ->deleteAddress($addressTransfer)
        ;
        $this->setSuccess($result);

        return $addressTransfer;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function defaultBillingAddressAction(CustomerAddressTransfer $addressTransfer)
    {
        $result = $this->getFacade()
            ->setDefaultBillingAddress($addressTransfer)
        ;
        $this->setSuccess($result);

        return $addressTransfer;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function defaultShippingAddressAction(CustomerAddressTransfer $addressTransfer)
    {
        $result = $this->getFacade()
            ->setDefaultShippingAddress($addressTransfer)
        ;
        $this->setSuccess($result);

        return $addressTransfer;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     * 
     * @return CustomerTransfer
     */
    public function getOrdersAction(CustomerTransfer $customerTransfer)
    {
        return $this->getFacade()->getOrders($customerTransfer);
    }
}
