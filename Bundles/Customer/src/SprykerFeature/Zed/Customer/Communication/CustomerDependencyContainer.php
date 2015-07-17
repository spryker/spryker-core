<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\CustomerCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Customer\Communication\Form\AddressForm;
use SprykerFeature\Zed\Customer\Communication\Form\CustomerForm;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerQuery;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerAddressQuery;
use SprykerFeature\Zed\Customer\Communication\Table\AddressTable;
use SprykerFeature\Zed\Customer\Communication\Table\CustomerTable;

/**
 * @method CustomerCommunication getFactory()
 */
class CustomerDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return CustomerQueryContainerInterface
     */
    public function createQueryContainer()
    {
        return $this->getLocator()->customer()->queryContainer();
    }

    /**
     * @return CustomerTable
     */
    public function createCustomerTable()
    {
        /** @var SpyCustomerQuery $customerQuery */
        $customerQuery = $this->getQueryContainer()->queryCustomers();

        return $this->getFactory()->createTableCustomerTable($customerQuery);
    }

    /**
     * @return AddressTable
     */
    public function createCustomerAddressTable($idCustomer)
    {
        /** @var SpyCustomerAddressQuery $addressQuery */
        $addressQuery = $this->getQueryContainer()->queryAddresses();

        /** @var SpyCustomerQuery $customerQuery */
        $customerQuery = $this->getQueryContainer()->queryCustomers();

        return $this->getFactory()->createTableAddressTable($addressQuery, $customerQuery, $idCustomer);
    }

    /**
     * @param $type
     *
     * @return CustomerForm
     */
    public function createCustomerForm($type)
    {
        /** @var SpyCustomerQuery $customerQuery */
        $customerQuery = $this->getQueryContainer()->queryCustomers();

        /** @var SpyCustomerAddressQuery $addressQuery */
        $addressQuery = $this->getQueryContainer()->queryAddresses();

        return $this->getFactory()->createFormCustomerForm($customerQuery, $addressQuery, $type);
    }

    /**
     * @param $type
     *
     * @return AddressForm
     */
    public function createAddressForm($type)
    {
        /** @var SpyCustomerQuery $customerQuery */
        $customerQuery = $this->getQueryContainer()->queryCustomers();

        /** @var SpyCustomerAddressQuery $addressQuery */
        $addressQuery = $this->getQueryContainer()->queryAddresses();

        return $this->getFactory()->createFormAddressForm($addressQuery, $customerQuery, $type);
    }

}
