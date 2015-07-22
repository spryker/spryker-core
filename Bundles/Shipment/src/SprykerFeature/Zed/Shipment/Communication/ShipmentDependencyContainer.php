<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\ShipmentCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Customer\Communication\Form\AddressForm;
use SprykerFeature\Zed\Customer\Communication\Form\CustomerForm;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerQuery;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerAddressQuery;
use SprykerFeature\Zed\Customer\Communication\Table\AddressTable;
use SprykerFeature\Zed\Customer\Communication\Table\CustomerTable;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentMethodQuery;

/**
 * @method ShipmentCommunication getFactory()
 */
class ShipmentDependencyContainer extends AbstractCommunicationDependencyContainer
{


    /**
     * @return
     */
    public function createQueryContainer()
    {
        return $this->getLocator()
            ->shipment()
            ->queryContainer()
            ;
    }

    /**
     * @return CustomerTable
     */
    public function createCustomerTable()
    {
        /** @var SpyCustomerQuery $customerQuery */
        $customerQuery = $this->getQueryContainer()
            ->query
        ;

        return $this->getFactory()
            ->createTableCustomerTable($customerQuery)
            ;
    }

    /**
     * @return AddressTable
     */
    public function createCustomerAddressTable($idCustomer)
    {
        /** @var SpyCustomerAddressQuery $addressQuery */
        $addressQuery = $this->getQueryContainer()
            ->queryAddresses()
        ;

        /** @var SpyCustomerQuery $customerQuery */
        $customerQuery = $this->getQueryContainer()
            ->queryCustomers()
        ;

        return $this->getFactory()
            ->createTableAddressTable($addressQuery, $customerQuery, $idCustomer)
            ;
    }

    /**
     * @param $type
     *
     * @return CustomerForm
     */
    public function createCustomerForm($type)
    {
        /** @var SpyCustomerQuery $customerQuery */
        $customerQuery = $this->getQueryContainer()
            ->queryCustomers()
        ;

        /** @var SpyCustomerAddressQuery $addressQuery */
        $addressQuery = $this->getQueryContainer()
            ->queryAddresses()
        ;

        return $this->getFactory()
            ->createFormCustomerForm($customerQuery, $addressQuery, $type)
            ;
    }

    /**
     * @param $type
     *
     * @return AddressForm
     */
    public function createAddressForm($type)
    {
        /** @var SpyCustomerQuery $customerQuery */
        $customerQuery = $this->getQueryContainer()
            ->queryCustomers()
        ;

        /** @var SpyCustomerAddressQuery $addressQuery */
        $addressQuery = $this->getQueryContainer()
            ->queryAddresses()
        ;

        return $this->getFactory()
            ->createFormAddressForm($addressQuery, $customerQuery, $type)
            ;
    }

}
