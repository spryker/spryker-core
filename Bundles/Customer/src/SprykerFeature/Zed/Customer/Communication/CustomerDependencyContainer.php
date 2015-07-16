<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\CustomerCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;

use SprykerFeature\Zed\Customer\Communication\Form\AddressForm;
use SprykerFeature\Zed\Customer\Communication\Form\CustomerForm;

use SprykerFeature\Zed\Customer\Communication\Grid\AddressGrid;
use SprykerFeature\Zed\Customer\Communication\Grid\CustomerGrid;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerQuery;

use Symfony\Component\HttpFoundation\Request;
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
        /* @var SpyCustomerQuery $customerQuery */
        $customerQuery = $this->getQueryContainer()->queryCustomers();

        return $this->getFactory()->createTableCustomerTable($customerQuery);
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

        return $this->getFactory()->createFormCustomerForm($customerQuery, $type);
    }

}
