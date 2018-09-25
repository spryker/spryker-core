<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication;

use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Zed\CompanyUserGui\Communication\Form\CompanyUserEditForm;
use Spryker\Zed\CompanyUserGui\Communication\Form\CompanyUserForm;
use Spryker\Zed\CompanyUserGui\Communication\Form\DataProvider\CompanyUserFormDataProvider;
use Spryker\Zed\CompanyUserGui\CompanyUserGuiDependencyProvider;
use Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyFacadeInterface;
use Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyUserFacadeInterface;
use Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCustomerFacadeInterface;
use Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToLocaleFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

class CompanyUserGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param array|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyUserForm($data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(CompanyUserForm::class, $data, $options);
    }

    /**
     * @param array|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyUserEditForm($data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(CompanyUserEditForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\CompanyUserGui\Communication\Form\DataProvider\CompanyUserFormDataProvider
     */
    public function createCompanyUserFormDataProvider(): CompanyUserFormDataProvider
    {
        return new CompanyUserFormDataProvider(
            $this->getCompanyUserFacade(),
            $this->getCompanyFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): CompanyUserGuiToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(CompanyUserGuiDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function getCompanyUserQuery(): SpyCompanyUserQuery
    {
        return $this->getProvidedDependency(CompanyUserGuiDependencyProvider::PROPEL_QUERY_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyFacadeInterface
     */
    public function getCompanyFacade(): CompanyUserGuiToCompanyFacadeInterface
    {
        return $this->getProvidedDependency(CompanyUserGuiDependencyProvider::FACADE_COMPANY);
    }

    /**
     * @return \Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCustomerFacadeInterface
     */
    public function getCustomerFacade(): CompanyUserGuiToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(CompanyUserGuiDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): CompanyUserGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(CompanyUserGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\CompanyUserGuiExtension\Communication\Plugin\CompanyUserFormExpanderPluginInterface[]
     */
    public function getCompanyUserFormPlugins(): array
    {
        return $this->getProvidedDependency(CompanyUserGuiDependencyProvider::COMPANY_USER_FORM_EXPANDER_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\CompanyUserGuiExtension\Communication\Plugin\CompanyUserFormExpanderPluginInterface[]
     */
    public function getCompanyUserEditFormPlugins(): array
    {
        return $this->getProvidedDependency(CompanyUserGuiDependencyProvider::COMPANY_USER_EDIT_FORM_EXPANDER_PLUGINS);
    }
}
