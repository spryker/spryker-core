<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Communication;

use Spryker\Zed\Customer\Communication\Form\AddressForm;
use Spryker\Zed\Customer\Communication\Form\CustomerForm;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Communication\Table\AddressTable;
use Spryker\Zed\Customer\Communication\Table\CustomerTable;

/**
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainer getQueryContainer()
 * @method \Spryker\Zed\Customer\CustomerConfig getConfig()
 */
class CustomerCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @deprecated Use getQueryContainer() instead.
     *
     * @return \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface
     */
    public function createQueryContainer()
    {
        trigger_error('Deprecated, use getQueryContainer() instead.', E_USER_DEPRECATED);

        return $this->getQueryContainer();
    }

    /**
     * @return \Spryker\Zed\Customer\Communication\Table\CustomerTable
     */
    public function createCustomerTable()
    {
        return new CustomerTable($this->getQueryContainer());
    }

    /**
     * @param int $idCustomer
     *
     * @return \Spryker\Zed\Customer\Communication\Table\AddressTable
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
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCustomerForm($formActionType)
    {
        $customerForm = new CustomerForm($this->getQueryContainer(), $formActionType);

        return $this->createForm($customerForm);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
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
