<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Business;

use Spryker\Zed\CompanyUser\Business\CompanyUser\CompanyUserStatusHandler;
use Spryker\Zed\CompanyUser\Business\CompanyUser\CompanyUserStatusHandlerInterface;
use Spryker\Zed\CompanyUser\Business\Expander\CustomerExpander;
use Spryker\Zed\CompanyUser\Business\Expander\CustomerExpanderInterface;
use Spryker\Zed\CompanyUser\Business\Model\CompanyUser;
use Spryker\Zed\CompanyUser\Business\Model\CompanyUserInterface;
use Spryker\Zed\CompanyUser\Business\Model\CompanyUserPluginExecutor;
use Spryker\Zed\CompanyUser\Business\Model\CompanyUserPluginExecutorInterface;
use Spryker\Zed\CompanyUser\CompanyUserDependencyProvider;
use Spryker\Zed\CompanyUser\Dependency\Facade\CompanyUserToCustomerFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyUser\CompanyUserConfig getConfig()
 * @method \Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyUser\Persistence\CompanyUserEntityManagerInterface getEntityManager()
 */
class CompanyUserBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyUser\Business\Model\CompanyUserInterface
     */
    public function createCompanyUser(): CompanyUserInterface
    {
        return new CompanyUser(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getCustomerFacade(),
            $this->createCompanyUserPluginExecutor(),
            $this->getCompanyUserSavePreCheckPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Business\CompanyUser\CompanyUserStatusHandlerInterface
     */
    public function createCompanyUserStatusHandler(): CompanyUserStatusHandlerInterface
    {
        return new CompanyUserStatusHandler(
            $this->getRepository(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Business\Model\CompanyUserPluginExecutorInterface
     */
    protected function createCompanyUserPluginExecutor(): CompanyUserPluginExecutorInterface
    {
        return new CompanyUserPluginExecutor(
            $this->getCompanyUserPreSavePlugins(),
            $this->getCompanyUserPostSavePlugins(),
            $this->getCompanyUserPostCreatePlugins(),
            $this->getCompanyUserHydrationPlugins(),
            $this->getCompanyUserPreDeletePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Dependency\Facade\CompanyUserToCustomerFacadeInterface
     */
    protected function getCustomerFacade(): CompanyUserToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(CompanyUserDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return array<\Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPreSavePluginInterface>
     */
    protected function getCompanyUserPreSavePlugins(): array
    {
        return $this->getProvidedDependency(CompanyUserDependencyProvider::PLUGINS_COMPANY_USER_PRE_SAVE);
    }

    /**
     * @return array<\Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPostSavePluginInterface>
     */
    protected function getCompanyUserPostSavePlugins(): array
    {
        return $this->getProvidedDependency(CompanyUserDependencyProvider::PLUGINS_COMPANY_USER_POST_SAVE);
    }

    /**
     * @return array<\Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPostCreatePluginInterface>
     */
    protected function getCompanyUserPostCreatePlugins(): array
    {
        return $this->getProvidedDependency(CompanyUserDependencyProvider::PLUGINS_COMPANY_USER_POST_CREATE);
    }

    /**
     * @return array<\Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserHydrationPluginInterface>
     */
    protected function getCompanyUserHydrationPlugins(): array
    {
        return $this->getProvidedDependency(CompanyUserDependencyProvider::PLUGINS_COMPANY_USER_HYDRATE);
    }

    /**
     * @return array<\Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPreDeletePluginInterface>
     */
    protected function getCompanyUserPreDeletePlugins(): array
    {
        return $this->getProvidedDependency(CompanyUserDependencyProvider::PLUGINS_COMPANY_USER_PRE_DELETE);
    }

    /**
     * @return array<\Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserSavePreCheckPluginInterface>
     */
    public function getCompanyUserSavePreCheckPlugins(): array
    {
        return $this->getProvidedDependency(CompanyUserDependencyProvider::PLUGINS_COMPANY_USER_SAVE_PRE_CHECK);
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Business\Expander\CustomerExpanderInterface
     */
    public function createCustomerExpander(): CustomerExpanderInterface
    {
        return new CustomerExpander($this->getRepository());
    }
}
