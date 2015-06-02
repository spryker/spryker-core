<?php

namespace SprykerFeature\Zed\Customer\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\CustomerCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Customer\Communication\Grid\CustomerGrid;
use SprykerFeature\Zed\Customer\Communication\Form\CustomerForm;
use SprykerFeature\Zed\Customer\Communication\Grid\AddressGrid;
use SprykerFeature\Zed\Customer\Communication\Form\AddressForm;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainerInterface;

/**
 * @method CustomerCommunication getFactory()
 */
class CustomerDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @param Request $request
     *
     * @return CustomerGrid
     */
    public function createCustomerGrid(Request $request)
    {
        return $this->getFactory()->createGridCustomerGrid(
            $this->getQueryContainer()->queryCustomers(),
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
            $this->getQueryContainer()
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
            $this->getQueryContainer()->queryAddresses(),
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
            $this->getQueryContainer()
        );
    }

    /**
     * @return CustomerQueryContainerInterface
     */
    public function getQueryContainer()
    {
        return $this->getLocator()->customer()->queryContainer();
    }
}
