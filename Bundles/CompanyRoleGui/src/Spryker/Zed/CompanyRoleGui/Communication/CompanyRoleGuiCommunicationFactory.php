<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery;
use Spryker\Zed\CompanyRoleGui\Communication\Form\CompanyRoleCreateForm;
use Spryker\Zed\CompanyRoleGui\Communication\Form\CompanyRoleEditForm;
use Spryker\Zed\CompanyRoleGui\Communication\Form\CompanyUserRoleForm;
use Spryker\Zed\CompanyRoleGui\Communication\Form\DataProvider\CompanyRoleCreateDataProvider;
use Spryker\Zed\CompanyRoleGui\Communication\Form\DataProvider\CompanyUserRoleFormDataProvider;
use Spryker\Zed\CompanyRoleGui\Communication\Formatter\CompanyRoleGuiFormatter;
use Spryker\Zed\CompanyRoleGui\Communication\Formatter\CompanyRoleGuiFormatterInterface;
use Spryker\Zed\CompanyRoleGui\Communication\Table\CompanyRoleTable;
use Spryker\Zed\CompanyRoleGui\CompanyRoleGuiDependencyProvider;
use Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyFacadeInterface;
use Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyRoleFacadeInterface;
use Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToGlossaryFacadeInterface;
use Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToPermissionFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @method \Spryker\Zed\CompanyRoleGui\CompanyRoleGuiConfig getConfig()
 */
class CompanyRoleGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createCompanyUserRoleForm(): FormTypeInterface
    {
        return new CompanyUserRoleForm();
    }

    /**
     * @return \Spryker\Zed\CompanyRoleGui\Communication\Form\DataProvider\CompanyUserRoleFormDataProvider
     */
    public function createCompanyUserRoleFormDataProvider(): CompanyUserRoleFormDataProvider
    {
        return new CompanyUserRoleFormDataProvider($this->getCompanyRoleFacade());
    }

    /**
     * @return \Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyRoleFacadeInterface
     */
    public function getCompanyRoleFacade(): CompanyRoleGuiToCompanyRoleFacadeInterface
    {
        return $this->getProvidedDependency(CompanyRoleGuiDependencyProvider::FACADE_COMPANY_ROLE);
    }

    /**
     * @return \Spryker\Zed\CompanyRoleGui\Communication\Table\CompanyRoleTable
     */
    public function createCompanyRoleTable(): CompanyRoleTable
    {
        return new CompanyRoleTable(
            $this->getCompanyRolePropelQuery()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyRoleGui\Communication\Formatter\CompanyRoleGuiFormatterInterface
     */
    public function createCompanyRoleGuiFormatter(): CompanyRoleGuiFormatterInterface
    {
        return new CompanyRoleGuiFormatter();
    }

    /**
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery
     */
    public function getCompanyRolePropelQuery(): SpyCompanyRoleQuery
    {
        return $this->getProvidedDependency(CompanyRoleGuiDependencyProvider::PROPEL_QUERY_COMPANY_ROLE);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCompanyRoleCreateForm(): FormInterface
    {
        $dataProvider = $this->createCompanyRoleCreateFormDataProvider();

        return $this->getFormFactory()->create(
            CompanyRoleCreateForm::class,
            $dataProvider->getData(),
            $dataProvider->getOptions()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCompanyRoleEditForm(CompanyRoleTransfer $companyRoleTransfer): FormInterface
    {
        $dataProvider = $this->createCompanyRoleCreateFormDataProvider();

        return $this->getFormFactory()->create(
            CompanyRoleEditForm::class,
            $dataProvider->getData($companyRoleTransfer),
            $dataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyRoleGui\Communication\Form\DataProvider\CompanyRoleCreateDataProvider
     */
    public function createCompanyRoleCreateFormDataProvider(): CompanyRoleCreateDataProvider
    {
        return new CompanyRoleCreateDataProvider(
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
