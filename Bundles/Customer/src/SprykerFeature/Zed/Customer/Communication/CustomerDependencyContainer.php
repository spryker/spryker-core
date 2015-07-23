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
     * @param string $type
     *
     * @return CustomerForm
     */
    public function createCustomerForm($type)
    {
        $customerQuery = $this->getQueryContainer()
            ->queryCustomers()
        ;

        $addressQuery = $this->getQueryContainer()
            ->queryAddresses()
        ;

        return $this->getFactory()
            ->createFormCustomerForm($customerQuery, $addressQuery, $type)
        ;
    }

    /**
     * @param string $type
     *
     * @return AddressForm
     */
    public function createAddressForm($type)
    {
        $customerQuery = $this->getQueryContainer()
            ->queryCustomers()
        ;

        $addressQuery = $this->getQueryContainer()
            ->queryAddresses()
        ;

        return $this->getFactory()
            ->createFormAddressForm($addressQuery, $customerQuery, $type)
        ;
    }

}
