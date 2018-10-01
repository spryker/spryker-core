<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication;

use Spryker\Zed\CompanyRoleGui\Communication\Form\CompanyUserRoleChoiceFormType;
use Spryker\Zed\CompanyRoleGui\Communication\Form\DataProvider\CompanyUserRoleFormDataProvider;
use Spryker\Zed\CompanyRoleGui\CompanyRoleGuiDependencyProvider;
use Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyRoleFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormTypeInterface;

class CompanyRoleGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createCompanyUserRoleChoiceFormType(): FormTypeInterface
    {
        return new CompanyUserRoleChoiceFormType();
    }

    /**
     * @return \Spryker\Zed\CompanyRoleGui\Communication\Form\DataProvider\CompanyUserRoleFormDataProvider
     */
    public function createCompanyUserRoleChoiceFormDataProvider(): CompanyUserRoleFormDataProvider
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
}
