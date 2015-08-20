<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Customer\Service;

use Generated\Shared\Customer\CustomerAddressInterface as CustomerAddressTransferInterface;
use Generated\Shared\Customer\CustomerInterface as CustomerTransferInterface;
use Generated\Shared\Transfer\FilterTransfer;
use SprykerEngine\Client\Kernel\Service\AbstractClient;

/**
 * @method CustomerDependencyContainer getDependencyContainer()
 */
class CustomerClient extends AbstractClient implements CustomerClientInterface
{

    /**
     * @inheritdoc
     */
    public function registerCustomer(CustomerTransferInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->confirmRegistration($customerTransfer)
        ;
    }

    /**
     * @inheritdoc
     */
    public function confirmRegistration(CustomerTransferInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->createAddress($addressTransfer)
        ;
    }

    /**
     * @inheritdoc
     */
    public function forgotPassword(CustomerTransferInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->delete($customerTransfer)
        ;
    }

    /**
     * @inheritdoc
     */
    public function restorePassword(CustomerTransferInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->forgotPassword($customerTransfer)
        ;
    }

    /**
     * @inheritdoc
     */
    public function deleteCustomer(CustomerTransferInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->getAddress($addressTransfer)
        ;
    }

    /**
     * @inheritdoc
     */
    public function getCustomer()
    {
        $customerTransfer = $this->getDependencyContainer()
            ->createSessionCustomerSession()
            ->getCustomer()
        ;

        return $customerTransfer;
    }

    /**
     * @inheritdoc
     */
    public function setCustomer(CustomerTransferInterface $customerTransfer)
    {
        $customerTransfer = $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->get($customerTransfer)
        ;

        return $customerTransfer;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerByEmail(CustomerTransferInterface $customerTransfer)
    {
        $result = $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->hasCustomerWithEmailAndPassword($customerTransfer)
        ;

        $hasCustomer = $result->getHasCustomer();
        if (true === $hasCustomer) {
            $this->setCustomer($result->getCustomerTransfer());
        }

        return $hasCustomer;
    }

    /**
     * @inheritdoc
     */
    public function updateCustomer(CustomerTransferInterface $customerTransfer)
    {
        $customerTransfer = $this->getDependencyContainer()
            ->createSessionCustomerSession()
            ->setCustomer($customerTransfer)
        ;

        return $customerTransfer;
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->getDependencyContainer()
            ->createSessionCustomerSession()
            ->hasCustomer()
        ;
    }

    /**
     * @inheritdoc
     */
    public function login(CustomerTransferInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->login($customerTransfer)
        ;
    }

    /**
     * @inheritdoc
     */
    public function logout()
    {
        return $this->getDependencyContainer()
            ->createSessionCustomerSession()
            ->logout()
        ;
    }

    /**
     * @inheritdoc
     */
    public function registerCustomer(CustomerInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->register($customerTransfer)
        ;
    }

    /**
     * @inheritdoc
     */
    public function getLatestOrders(CustomerTransferInterface $customerTransfer)
    {
        $filterTransfer = new FilterTransfer();
        $filterTransfer->setLimit(5);
        $filterTransfer->setOffset(0);
        $filterTransfer->setOrderBy('created_at, updated_at');
        $filterTransfer->setOrderDirection('DESC');
        
        $customerTransfer->setFilter($filterTransfer);
        
        return $this->getOrders($customerTransfer);
    }

    /**
     * @inheritdoc
     */
    public function getOrders(CustomerTransferInterface $customerTransfer)
    {
        return $this->getDependencyContainer()->createZedCustomerStub()->getOrders($customerTransfer);
    }

    /**
     * @inheritdoc
     */
    public function getAddress(CustomerAddressTransferInterface $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->restorePassword($customerTransfer)
        ;
    }

    /**
     * @inheritdoc
     */
    public function updateAddress(CustomerAddressTransferInterface $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->updateAddress($addressTransfer)
        ;
    }

    /**
     * @inheritdoc
     */
    public function createAddress(CustomerAddressTransferInterface $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->update($customerTransfer)
        ;
    }

    /**
     * @inheritdoc
     */
    public function deleteAddress(CustomerAddressTransferInterface $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->deleteAddress($addressTransfer)
        ;
    }

    /**
     * @inheritdoc
     */
    public function setDefaultShippingAddress(CustomerAddressTransferInterface $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->setDefaultShippingAddress($addressTransfer)
        ;
    }

    /**
     * @inheritdoc
     */
    public function setDefaultBillingAddress(CustomerAddressTransferInterface $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->setDefaultBillingAddress($addressTransfer)
        ;
    }
}
