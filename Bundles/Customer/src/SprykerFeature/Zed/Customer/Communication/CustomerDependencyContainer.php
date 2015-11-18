<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\CustomerCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use SprykerFeature\Zed\Customer\Communication\Table\AddressTable;
use SprykerFeature\Zed\Customer\Communication\Table\CustomerTable;
use Symfony\Component\Form\FormInterface;

/**
 * @method CustomerCommunication getFactory()
 * @method CustomerQueryContainerInterface getQueryContainer()
 */
class CustomerDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return CustomerQueryContainerInterface
     */
    public function createQueryContainer()
    {
        return $this->getLocator()
            ->customer()
            ->queryContainer()
        ;
    }

    /**
     * @return CustomerTable
     */
    public function createCustomerTable()
    {
        $customerQuery = $this->getQueryContainer()
            ->queryCustomers()
        ;

        return $this->getFactory()
            ->createTableCustomerTable($customerQuery)
        ;
    }

    /**
     * @param int $idCustomer
     *
     * @return AddressTable
     */
    public function createCustomerAddressTable($idCustomer)
    {
        $addressQuery = $this->getQueryContainer()
            ->queryAddresses()
        ;

        $customerQuery = $this->getQueryContainer()
            ->queryCustomers()
        ;

        return $this->getFactory()
            ->createTableAddressTable($addressQuery, $customerQuery, $idCustomer)
        ;
    }

    /**
     * @param string $formTypeName
     *
     * @throws \ErrorException
     *
     * @return FormInterface
     */
    public function createCustomerForm($formTypeName)
    {
        $customerFormType = $this->getFactory()
            ->createFormCustomerFormType($this->getQueryContainer(), $formTypeName)
        ;

        $customerForm = $this->getFactory()
            ->createFormCustomerForm($this->getQueryContainer(), $customerFormType)
        ;

        return $customerForm->create();
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return FormInterface
     */
    public function createAddressForm(AddressTransfer $addressTransfer)
    {
        $customerQueryContainer = $this->getQueryContainer();

        $customerAddressFormType = $this->getFactory()
            ->createFormAddressTypeForm($customerQueryContainer)
        ;

        $defaultData = $this->getAddressFormDefaultData($addressTransfer);

        return $this->getFormFactory()
            ->create($customerAddressFormType, $defaultData);
    }

    /**
     * @param AddressTransfer|null $addressTransfer
     *
     * @return array
     */
    protected function getAddressFormDefaultData(AddressTransfer $addressTransfer = null)
    {
        if ($addressTransfer === null) {
            return [];
        }

        return $addressTransfer->toArray();
    }

    /**
     * @param CustomerTransfer|null $customer
     *
     * @return array
     */
    protected function getCustomerDefaultData(CustomerTransfer $customer = null)
    {
        if ($customer === null) {
            return [];
        }

        return $customer->toArray();
    }

}
