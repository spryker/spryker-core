<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication;

use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Zed\CompanyUserGui\Communication\Table\CompanyUserTable;
use Spryker\Zed\CompanyUserGui\Communication\Table\PluginExecutor\CompanyUserTableExpanderPluginExecutor;
use Spryker\Zed\CompanyUserGui\Communication\Table\PluginExecutor\CompanyUserTableExpanderPluginExecutorInterface;
use Spryker\Zed\CompanyUserGui\CompanyUserGuiDependencyProvider;
use Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyUserFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

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
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    public function getCompanyUserPropelQuery(): SpyCompanyUserQuery
    {
        return $this->getProvidedDependency(CompanyUserGuiDependencyProvider::PROPEL_QUERY_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\CompanyUserGui\Communication\Table\PluginExecutor\CompanyUserTableExpanderPluginExecutorInterface
     */
    public function createCompanyUserTableExpanderPluginExecutor(): CompanyUserTableExpanderPluginExecutorInterface
    {
        return new CompanyUserTableExpanderPluginExecutor(
            $this->getCompanyUserTableConfigExpanderPlugins(),
            $this->getCompanyUserTablePrepareDataExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUserGui\Communication\Table\CompanyUserTable
     */
    public function createCompanyUserTable(): CompanyUserTable
    {
        return new CompanyUserTable(
            $this->getCompanyUserPropelQuery(),
            $this->createCompanyUserTableExpanderPluginExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserGui\CompanyUserTableConfigExpanderPluginInterface[]
     */
    public function getCompanyUserTableConfigExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CompanyUserGuiDependencyProvider::PLUGINS_COMPANY_USER_TABLE_CONFIG_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserGui\CompanyUserTablePrepareDataExpanderPluginInterface[]
     */
    public function getCompanyUserTablePrepareDataExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CompanyUserGuiDependencyProvider::PLUGINS_COMPANY_USER_TABLE_PREPARE_DATA_EXPANDER);
    }
}
