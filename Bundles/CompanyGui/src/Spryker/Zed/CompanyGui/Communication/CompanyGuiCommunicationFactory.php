<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Communication;

use Spryker\Zed\CompanyGui\Communication\Table\CompanyTable;
use Spryker\Zed\CompanyGui\CompanyGuiDependencyProvider;
use Spryker\Zed\CompanyGui\Dependency\Facade\CompanyGuiToCompanyFacadeInterface;
use Spryker\Zed\CompanyGui\Dependency\QueryContainer\CompanyGuiToCompanyQueryContainerInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CompanyGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CompanyGui\Dependency\QueryContainer\CompanyGuiToCompanyQueryContainerInterface
     */
    public function getCompanyQueryContainer(): CompanyGuiToCompanyQueryContainerInterface
    {
        return $this->getProvidedDependency(CompanyGuiDependencyProvider::QUERY_CONTAINER_COMPANY);
    }

    /**
     * @return \Spryker\Zed\CompanyGui\Communication\Table\CompanyTable
     */
    public function createCompanyTable(): CompanyTable
    {
        $companyQuery = $this->getCompanyQueryContainer()->queryCompany();

        return new CompanyTable($companyQuery);
    }

    /**
     * @return \Spryker\Zed\CompanyGui\Dependency\Facade\CompanyGuiToCompanyFacadeInterface
     */
    public function getCompanyFacade(): CompanyGuiToCompanyFacadeInterface
    {
        return $this->getProvidedDependency(CompanyGuiDependencyProvider::FACADE_COMPANY);
    }
}
