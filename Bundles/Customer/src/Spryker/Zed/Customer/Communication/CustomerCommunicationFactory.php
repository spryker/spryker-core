<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Communication;

use Spryker\Zed\Customer\Communication\Form\AddressForm;
use Spryker\Zed\Customer\Communication\Form\CustomerForm;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use Spryker\Zed\Customer\Communication\Table\AddressTable;
use Spryker\Zed\Customer\Communication\Table\CustomerTable;
use Symfony\Component\Form\FormInterface;

/**
 * @method CustomerQueryContainerInterface getQueryContainer()
 */
class CustomerCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return CustomerQueryContainerInterface
     */
    public function createQueryContainer()
    {
        return $this->getLocator()
            ->customer()
            ->queryContainer();
    }

    /**
     * @return CustomerTable
     */
    public function createCustomerTable()
    {
        return new CustomerTable($this->getQueryContainer());
    }

    /**
     * @param int $idCustomer
     *
     * @return AddressTable
     */
    public function createCustomerAddressTable($idCustomer)
    {
        return new AddressTable($this->getQueryContainer(), $idCustomer);
    }

    /**
     * @param string $formActionType
     *
     * @throws \ErrorException
     *
     * @return FormInterface
     */
    public function createCustomerForm($formActionType)
    {
        $customerForm = new CustomerForm($this->getQueryContainer(), $formActionType);

        return $this->createForm($customerForm);
    }

    /**
     * @return FormInterface
     */
    public function createAddressForm()
    {
        $customerAddressForm = new AddressForm(
                $this->getProvidedDependency(CustomerDependencyProvider::FACADE_COUNTRY),
                $this->getQueryContainer()
            );

        return $this->createForm($customerAddressForm);
    }

}
