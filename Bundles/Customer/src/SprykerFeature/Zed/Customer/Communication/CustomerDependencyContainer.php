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
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CustomerCommunication getFactory()
 */
class CustomerDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @param Request $request
     *
     * @return CustomerGrid
     */
    public function createCustomerGrid(Request $request)
    {
        return $this->getFactory()->createGridCustomerGrid(
            $this->createQueryContainer()->queryCustomers(),
            $request
        );
    }

    /**
     * @param Request $request
     *
     * @return CustomerForm
     */
    public function createCustomerForm(Request $request)
    {
        return $this->getFactory()->createFormCustomerForm(
            $request,
            $this->createQueryContainer()
        );
    }

    /**
     * @param Request $request
     *
     * @return AddressGrid
     */
    public function createAddressGrid(Request $request)
    {
        return $this->getFactory()->createGridAddressGrid(
            $this->createQueryContainer()->queryAddresses(),
            $request
        );
    }

    /**
     * @param Request $request
     *
     * @return AddressForm
     */
    public function createAddressForm(Request $request)
    {
        return $this->getFactory()->createFormAddressForm(
            $request,
            $this->createQueryContainer()
        );
    }

    /**
     * @return CustomerQueryContainerInterface
     */
    public function createQueryContainer()
    {
        return $this->getLocator()->customer()->queryContainer();
    }

}
