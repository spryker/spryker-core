<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\CustomerCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Customer\CustomerDependencyProvider;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use SprykerFeature\Zed\Customer\Communication\Table\AddressTable;
use SprykerFeature\Zed\Customer\Communication\Table\CustomerTable;
use Symfony\Component\Form\FormInterface;

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
            ->queryContainer();
    }

    /**
     * @return CustomerTable
     */
    public function createCustomerTable()
    {
        return $this->getFactory()
            ->createTableCustomerTable($this->getQueryContainer());
    }

    /**
     * @param int $idCustomer
     *
     * @return AddressTable
     */
    public function createCustomerAddressTable($idCustomer)
    {
        return $this->getFactory()
            ->createTableAddressTable($this->getQueryContainer(), $idCustomer);
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
        $customerFormType = $this->getFactory()
            ->createFormCustomerForm($this->getQueryContainer(), $formActionType)
        ;

        return $this->createForm($customerFormType);
    }

    /**
     * @return FormInterface
     */
    public function getDummyForm()
    {
        $dummyFormType = $this->getFactory()
            ->createFormDummyForm(
                $this->getProvidedDependency(CustomerDependencyProvider::COUNTRY_FACADE)
            )
        ;

        return $this->createForm($dummyFormType);
    }

    /**
     * @return FormInterface
     */
    public function createAddressForm()
    {
        $customerAddressFormType = $this->getFactory()
            ->createFormAddressFormType($this->getProvidedDependency(CustomerDependencyProvider::COUNTRY_FACADE));

        $customerAddressForm = $this->getFactory()
            ->createFormAddressForm($customerAddressFormType, $this->getQueryContainer());

        return $customerAddressForm->create();
    }

}
