<?php

namespace Spryker\Zed\CustomerAccessGui\Communication;

use Generated\Shared\Transfer\CustomerAccessTransfer;
use Spryker\Zed\CustomerAccessGui\Communication\Form\CustomerAccessForm;
use Spryker\Zed\CustomerAccessGui\Communication\Form\DataProvider\CustomerAccessDataProvider;
use Spryker\Zed\CustomerAccessGui\CustomerAccessGuiDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CustomerAccessGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CustomerAccessGui\Dependency\Facade\CustomerAccessGuiToCustomerAccessFacadeInterface
     */
    public function getCustomerAccessFacade()
    {
        return $this->getProvidedDependency(CustomerAccessGuiDependencyProvider::FACADE_CUSTOMER_ACCESS);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer|null $customerAccessTransfer
     *
     * @return \Spryker\Zed\CustomerAccessGui\Communication\Form\DataProvider\CustomerAccessDataProvider
     */
    public function createCustomerAccessDataProvider(CustomerAccessTransfer $customerAccessTransfer = null)
    {
        return new CustomerAccessDataProvider($this->getCustomerAccessFacade(), $customerAccessTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer|null $data
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCustomerAccessForm(CustomerAccessTransfer $customerAccessTransfer = null)
    {
        return $this->getFormFactory()->create(
            CustomerAccessForm::class,
            $customerAccessTransfer,
            [
                'data_class' => CustomerAccessTransfer::class,
            ]
        );
    }
}