<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Communication;

use Spryker\Zed\Customer\Communication\Form\AddressForm;
use Spryker\Zed\Customer\Communication\Form\CustomerForm;
use Spryker\Zed\Customer\Communication\Form\CustomerUpdateForm;
use Spryker\Zed\Customer\Communication\Form\DataProvider\AddressFormDataProvider;
use Spryker\Zed\Customer\Communication\Form\DataProvider\CustomerFormDataProvider;
use Spryker\Zed\Customer\Communication\Form\DataProvider\CustomerUpdateFormDataProvider;
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
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCustomerForm(array $data = [], array $options = [])
    {
        $customerFormType = new CustomerForm($this->getQueryContainer());

        return $this->getFormFactory()->create($customerFormType, $data, $options);
    }

    /**
     * @return \Spryker\Zed\Customer\Communication\Form\DataProvider\CustomerFormDataProvider
     */
    public function createCustomerFormDataProvider()
    {
        return new CustomerFormDataProvider($this->getQueryContainer());
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCustomerUpdateForm(array $data = [], array $options = [])
    {
        $customerFormType = new CustomerUpdateForm($this->getQueryContainer());

        return $this->getFormFactory()->create($customerFormType, $data, $options);
    }

    /**
     * @return \Spryker\Zed\Customer\Communication\Form\DataProvider\CustomerUpdateFormDataProvider
     */
    public function createCustomerUpdateFormDataProvider()
    {
        return new CustomerUpdateFormDataProvider($this->getQueryContainer());
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return FormInterface
     */
    public function createAddressForm(array $formData = [], array $formOptions = [])
    {
        $customerAddressFormType = new AddressForm();

        return $this->getFormFactory()->create($customerAddressFormType, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\Customer\Communication\Form\DataProvider\AddressFormDataProvider
     */
    public function createAddressFormDataProvider()
    {
        return new AddressFormDataProvider(
            $this->getProvidedDependency(CustomerDependencyProvider::FACADE_COUNTRY),
            $this->getQueryContainer()
        );
    }

}
