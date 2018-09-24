<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication;

use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery;
use Spryker\Zed\CompanyRoleGui\Communication\Table\CompanyRoleTable;
use Spryker\Zed\CompanyRoleGui\CompanyRoleGuiDependencyProvider;
use Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyRoleFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CompanyRoleGuiCommunicationFactory extends AbstractCommunicationFactory
{
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
            $this->getPropelCompanyRoleQuery()
        );
    }

    /**
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery
     */
    public function getPropelCompanyRoleQuery(): SpyCompanyRoleQuery
    {
        return $this->getProvidedDependency(CompanyRoleGuiDependencyProvider::PROPEL_COMPANY_ROLE_QUERY);
    }
}
