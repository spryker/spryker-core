<?php

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\CustomerAddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractSdkController;

class SdkController extends AbstractSdkController
{
    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function registerAction(CustomerTransfer $customerTransfer)
    {
        return $this->getLocator()->customer()->facade()->registerCustomer($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function confirmRegistrationAction(CustomerTransfer $customerTransfer)
    {
        return $this->getLocator()->customer()->facade()->confirmRegistration($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function forgotPasswordAction(CustomerTransfer $customerTransfer)
    {
        return $this->getLocator()->customer()->facade()->forgotPassword($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function restorePasswordAction(CustomerTransfer $customerTransfer)
    {
        return $this->getLocator()->customer()->facade()->restorePassword($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     */
    public function deleteAction(CustomerTransfer $customerTransfer)
    {
        $success = $this->getLocator()->customer()->facade()->deleteCustomer($customerTransfer);
        $this->setSuccess($success);

        return;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function customerAction(CustomerTransfer $customerTransfer)
    {
        return $this->getLocator()->customer()->facade()->getCustomer($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function updateAction(CustomerTransfer $customerTransfer)
    {
        $success = $this->getLocator()->customer()->facade()->updateCustomer($customerTransfer);
        $this->setSuccess($success);

        return $customerTransfer;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function addressAction(CustomerAddressTransfer $addressTransfer)
    {
        $addressTransfer = $this->getLocator()->customer()->facade()->getAddress($addressTransfer);
        if (!$addressTransfer) {
            $this->setSuccess(false);

            return null;
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
        $success = $this->getLocator()->customer()->facade()->updateAddress($addressTransfer);
        $this->setSuccess($success);

        return $addressTransfer;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function newAddressAction(CustomerAddressTransfer $addressTransfer)
    {
        $success = $this->getLocator()->customer()->facade()->createAddress($addressTransfer);
        $this->setSuccess($success);

        return $addressTransfer;
    }
}
