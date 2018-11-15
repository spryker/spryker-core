<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\BusinessOnBehalfGui\BusinessOnBehalfGuiDependencyProvider;
use Spryker\Zed\BusinessOnBehalfGui\Communication\Form\CustomerBusinessUnitAttachForm;
use Spryker\Zed\BusinessOnBehalfGui\Communication\Form\DataProvider\CustomerBusinessUnitAttachFormDataProvider;
use Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyFacadeInterface;
use Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCustomerFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\BusinessOnBehalfGui\BusinessOnBehalfGuiConfig getConfig()
 */
class BusinessOnBehalfGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserAttachCustomerFormExpanderPluginInterface[]
     */
    public function getCompanyUserAttachCustomerFormExpanderPlugins(): array
    {
        return $this->getProvidedDependency(BusinessOnBehalfGuiDependencyProvider::PLUGINS_CUSTOMER_BUSINESS_UNIT_ATTACH_FORM_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\BusinessOnBehalfGui\Communication\Form\DataProvider\CustomerBusinessUnitAttachFormDataProvider
     */
    public function createCustomerCompanyAttachFormDataProvider(): CustomerBusinessUnitAttachFormDataProvider
    {
        return new CustomerBusinessUnitAttachFormDataProvider(
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
     * @param \Generated\Shared\Transfer\CompanyUserTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCustomerBusinessUnitAttachForm(?CompanyUserTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(CustomerBusinessUnitAttachForm::class, $data, $options);
    }
}
