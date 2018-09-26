<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication;

use Spryker\Zed\CompanyRoleGui\Communication\Form\CompanyRoleEditForm;
use Spryker\Zed\CompanyRoleGui\Communication\Form\DataProvider\CompanyRoleEditFormDataProvider;
use Spryker\Zed\CompanyRoleGui\Communication\Form\DataProvider\CompanyRoleEditFormDataProviderInterface;
use Spryker\Zed\CompanyRoleGui\CompanyRoleGuiDependencyProvider;
use Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyFacadeInterface;
use Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyRoleFacadeInterface;
use Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToGlossaryFacadeInterface;
use Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToPermissionFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

class CompanyRoleGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCompanyRoleEditForm(): FormInterface
    {
        $dataProvider = $this->createCompanyRoleEditFormDataProvider();

        return $this->getFormFactory()->create(
            CompanyRoleEditForm::class,
            $dataProvider->getData(),
            $dataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyRoleGui\Communication\Form\DataProvider\CompanyRoleEditFormDataProviderInterface
     */
    public function createCompanyRoleEditFormDataProvider(): CompanyRoleEditFormDataProviderInterface
    {
        return new CompanyRoleEditFormDataProvider(
            $this->getCompanyFacade(),
            $this->getCompanyRoleFacade(),
            $this->getGlossaryFacade(),
            $this->getPermissionFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyFacadeInterface
     */
    public function getCompanyFacade(): CompanyRoleGuiToCompanyFacadeInterface
    {
        return $this->getProvidedDependency(CompanyRoleGuiDependencyProvider::FACADE_COMPANY);
    }

    /**
     * @return \Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyRoleFacadeInterface
     */
    public function getCompanyRoleFacade(): CompanyRoleGuiToCompanyRoleFacadeInterface
    {
        return $this->getProvidedDependency(CompanyRoleGuiDependencyProvider::FACADE_COMPANY_ROLE);
    }

    /**
     * @return \Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): CompanyRoleGuiToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(CompanyRoleGuiDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToPermissionFacadeInterface
     */
    public function getPermissionFacade(): CompanyRoleGuiToPermissionFacadeInterface
    {
        return $this->getProvidedDependency(CompanyRoleGuiDependencyProvider::FACADE_PERMISSION);
    }
}
