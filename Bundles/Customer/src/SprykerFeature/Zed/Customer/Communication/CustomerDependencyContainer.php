<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\CustomerCommunication;
use Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Customer\Communication\Form\AddressTypeForm;
use SprykerFeature\Zed\Customer\Communication\Form\CustomerTypeForm;
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
     * @param CustomerTransfer|null $customer
     * @param string $type
     * @param int $idCustomer
     *
     * @return FormInterface
     */
    public function createCustomerForm(CustomerTransfer $customer, $type, $idCustomer = 0)
    {
        $customerQuery = $this->getQueryContainer()
            ->queryCustomers()
        ;

        $customerAddressQuery = $this->getQueryContainer()
            ->queryAddresses()
        ;

        $customerFormType = $this->createCustomerFormType($customerQuery, $customerAddressQuery, $type, $idCustomer);

        $defaultData = $this->getCustomerDefaultData($customer);

        return $this->getFormFactory()
            ->create($customerFormType, $defaultData)
        ;
    }

    /**
     * @param SpyCustomerQuery $customerQuery
     * @param SpyCustomerAddressQuery $customerAddressQuery
     * @param string $type
     * @param int $idCustomer
     *
     * @return CustomerTypeForm
     */
    public function createCustomerFormType(SpyCustomerQuery $customerQuery, SpyCustomerAddressQuery $customerAddressQuery, $type, $idCustomer)
    {
        return new CustomerTypeForm($customerQuery, $customerAddressQuery, $type, $idCustomer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     * @param string $type
     *
     * @return FormInterface
     */
    public function createAddressForm(AddressTransfer $addressTransfer = null, $type)
    {
        $customerQuery = $this->getQueryContainer()
            ->queryCustomers()
        ;

        $addressQuery = $this->getQueryContainer()
            ->queryAddresses()
        ;

        $customerAddressForm = $this->createCustomerAddressFormType($addressQuery, $customerQuery, $type);

        $defaultData = $this->getAddressFormDefaultData($addressTransfer);

        return $this->getFormFactory()
            ->create($customerAddressForm, $defaultData)
        ;
    }

    /**
     * @param SpyCustomerAddressQuery $addressQuery
     * @param SpyCustomerQuery $customerQuery
     * @param string $type
     *
     * @return AddressTypeForm
     */
    protected function createCustomerAddressFormType(
        SpyCustomerAddressQuery $addressQuery,
        SpyCustomerQuery $customerQuery,
        $type
    ) {
        $customerAddressForm = new AddressTypeForm($addressQuery, $customerQuery, $type);

        return $customerAddressForm;
    }

    /**
     * @param AddressTransfer $addressTransfer
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
     * @param CustomerTransfer $customer
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
