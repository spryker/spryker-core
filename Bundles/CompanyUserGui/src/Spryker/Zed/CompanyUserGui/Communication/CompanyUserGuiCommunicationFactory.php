<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication;

use Spryker\Zed\CompanyUserGui\Communication\Form\CustomerCompanyAttachForm;
use Spryker\Zed\CompanyUserGui\Communication\Form\DataProvider\CustomerCompanyAttachFormDataProvider;
use Spryker\Zed\CompanyUserGui\CompanyUserGuiDependencyProvider;
use Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyFacadeInterface;
use Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyUserFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

class CompanyUserGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): CompanyUserGuiToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(CompanyUserGuiDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyFacadeInterface
     */
    public function getCompanyFacade(): CompanyUserGuiToCompanyFacadeInterface
    {
        return $this->getProvidedDependency(CompanyUserGuiDependencyProvider::FACADE_COMPANY);
    }

    /**
     * @return \Spryker\Zed\CompanyUserGui\Communication\Form\DataProvider\CustomerCompanyAttachFormDataProvider
     */
    public function createCustomerCompanyAttachFormDataProvider(): CustomerCompanyAttachFormDataProvider
    {
        return new CustomerCompanyAttachFormDataProvider(
            $this->getCompanyUserFacade(),
            $this->getCompanyFacade()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer|array|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCustomerCompanyAttachForm($data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(CustomerCompanyAttachForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CustomerCompanyAttachFormExpanderPluginInterface[]
     */
    public function getCustomerCompanyAttachFormPlugins(): array
    {
        return $this->getProvidedDependency(CompanyUserGuiDependencyProvider::PLUGINS_CUSTOMER_COMPANY_ATTACH_FORM_EXPANDER);
    }
}
