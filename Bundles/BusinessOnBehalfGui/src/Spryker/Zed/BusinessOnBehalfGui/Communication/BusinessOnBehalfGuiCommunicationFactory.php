<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\BusinessOnBehalfGui\BusinessOnBehalfGuiDependencyProvider;
use Spryker\Zed\BusinessOnBehalfGui\Communication\ButtonCreator\GuiButtonCreator;
use Spryker\Zed\BusinessOnBehalfGui\Communication\ButtonCreator\GuiButtonCreatorInterface;
use Spryker\Zed\BusinessOnBehalfGui\Communication\Form\CustomerBusinessUnitAttachForm;
use Spryker\Zed\BusinessOnBehalfGui\Communication\Form\DataProvider\CustomerBusinessUnitAttachFormDataProvider;
use Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyFacadeInterface;
use Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyUserFacadeInterface;
use Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCustomerFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\BusinessOnBehalfGui\BusinessOnBehalfGuiConfig getConfig()
 */
class BusinessOnBehalfGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\BusinessOnBehalfGui\Communication\Form\DataProvider\CustomerBusinessUnitAttachFormDataProvider
     */
    public function createCustomerCompanyAttachFormDataProvider(): CustomerBusinessUnitAttachFormDataProvider
    {
        return new CustomerBusinessUnitAttachFormDataProvider(
            $this->getCompanyBusinessUnitFacade(),
            $this->getCompanyFacade(),
            $this->getCustomerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyFacadeInterface
     */
    public function getCompanyFacade(): BusinessOnBehalfGuiToCompanyFacadeInterface
    {
        return $this->getProvidedDependency(BusinessOnBehalfGuiDependencyProvider::FACADE_COMPANY);
    }

    /**
     * @return \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCustomerFacadeInterface
     */
    public function getCustomerFacade(): BusinessOnBehalfGuiToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(BusinessOnBehalfGuiDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): BusinessOnBehalfGuiToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(BusinessOnBehalfGuiDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyBusinessUnitFacadeInterface
     */
    public function getCompanyBusinessUnitFacade(): BusinessOnBehalfGuiToCompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(BusinessOnBehalfGuiDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCustomerBusinessUnitAttachForm(?CompanyUserTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(CustomerBusinessUnitAttachForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\BusinessOnBehalfGuiExtension\Dependency\Plugin\CustomerBusinessUnitAttachFormExpanderPluginInterface[]
     */
    public function getCustomerBusinessUnitAttachFormExpanderPlugins(): array
    {
        return $this->getProvidedDependency(
            BusinessOnBehalfGuiDependencyProvider::PLUGINS_CUSTOMER_BUSINESS_UNIT_ATTACH_FORM_EXPANDER
        );
    }

    /**
     * @return \Spryker\Zed\BusinessOnBehalfGui\Communication\ButtonCreator\GuiButtonCreatorInterface
     */
    public function createGuiButtonCreator(): GuiButtonCreatorInterface
    {
        return new GuiButtonCreator();
    }
}
