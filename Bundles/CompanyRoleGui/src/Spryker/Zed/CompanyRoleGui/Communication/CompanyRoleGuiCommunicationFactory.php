<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication;

use Spryker\Zed\CompanyRoleGui\Communication\Form\CompanyRoleChoiceFormType;
use Spryker\Zed\CompanyRoleGui\Communication\Form\DataProvider\CompanyRoleFormDataProvider;
use Spryker\Zed\CompanyRoleGui\CompanyRoleGuiDependencyProvider;
use Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyRoleFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CompanyRoleGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CompanyRoleGui\Communication\Form\CompanyRoleChoiceFormType
     */
    public function createCompanyRoleChoiceFormType(): CompanyRoleChoiceFormType
    {
        return new CompanyRoleChoiceFormType();
    }

    /**
     * @return \Spryker\Zed\CompanyRoleGui\Communication\Form\DataProvider\CompanyRoleFormDataProvider
     */
    public function createCompanyRoleChoiceFormDataProvider(): CompanyRoleFormDataProvider
    {
        return new CompanyRoleFormDataProvider($this->getCompanyRoleFacade());
    }

    /**
     * @return \Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyRoleFacadeInterface
     */
    public function getCompanyRoleFacade(): CompanyRoleGuiToCompanyRoleFacadeInterface
    {
        return $this->getProvidedDependency(CompanyRoleGuiDependencyProvider::FACADE_COMPANY_ROLE);
    }
}
