<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication;

use SprykerFeature\Zed\Customer\Communication\Form\AddressForm;
use SprykerFeature\Zed\Customer\Communication\Form\CustomerForm;
use Generated\Zed\Ide\FactoryAutoCompletion\CustomerCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Customer\CustomerDependencyProvider;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use SprykerFeature\Zed\Customer\Communication\Table\AddressTable;
use SprykerFeature\Zed\Customer\Communication\Table\CustomerTable;
use Symfony\Component\Form\FormInterface;

/**
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
                $this->getProvidedDependency(CustomerDependencyProvider::COUNTRY_FACADE),
                $this->getQueryContainer()
            );

        return $this->createForm($customerAddressForm);
    }

}
