<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\CustomerCommunication;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Customer\Communication\Form\AddressForm;
use SprykerFeature\Zed\Customer\Communication\Form\CustomerForm;
use SprykerFeature\Zed\Customer\Communication\Form\CustomerTypeForm;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainerInterface;
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
     * @param int $idCustomer
     *
     * @return CustomerForm
     */
    public function createCustomerForm(CustomerTransfer $customer = null, $type, $idCustomer = 0)
    {
        $customerQuery = $this->getQueryContainer()
            ->queryCustomers()
        ;

        $customerAddressQuery = $this->getQueryContainer()
            ->queryAddresses()
        ;

        $customerFormType = $this->createCustomerTypeForm($customerQuery, $customerAddressQuery, $type, $idCustomer);

        return $this->getFormFactory()
            ->create($customerFormType, $customer)
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
    public function createCustomerTypeForm(SpyCustomerQuery $customerQuery, SpyCustomerAddressQuery $customerAddressQuery, $type, $idCustomer)
    {
        return new CustomerTypeForm($customerQuery, $customerAddressQuery, $type, $idCustomer);
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
