<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Business;

use Spryker\Zed\CompanyUser\Business\Model\CompanyUserPluginExecutor;
use Spryker\Zed\CompanyUser\Business\Model\CompanyUserPluginExecutorInterface;
use Spryker\Zed\CompanyUser\Business\Model\CompanyUserReader;
use Spryker\Zed\CompanyUser\Business\Model\CompanyUserReaderInterface;
use Spryker\Zed\CompanyUser\Business\Model\CompanyUserWriter;
use Spryker\Zed\CompanyUser\Business\Model\CompanyUserWriterInterface;
use Spryker\Zed\CompanyUser\CompanyUserDependencyProvider;
use Spryker\Zed\CompanyUser\Dependency\Facade\CompanyUserToCustomerFacadeInterface;
use Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface;
use Spryker\Zed\CompanyUser\Persistence\CompanyUserWriterRepositoryInterface;
use Spryker\Zed\CompanyUser\Persistence\Propel\CompanyUserPropelRepository;
use Spryker\Zed\CompanyUser\Persistence\Propel\CompanyUserWriterPropelRepository;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyUser\Persistence\CompanyUserQueryContainerInterface getQueryContainer()
 */
class CompanyUserBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyUser\Business\Model\CompanyUserWriterInterface
     */
    public function createCompanyUserWriter(): CompanyUserWriterInterface
    {
        return new CompanyUserWriter(
            $this->createCompanyUserWriterRepository(),
            $this->getCustomerFacade(),
            $this->createCompanyUserPluginExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Business\Model\CompanyUserReaderInterface
     */
    public function createCompanyUserReader(): CompanyUserReaderInterface
    {
        return new CompanyUserReader($this->createCompanyUserRepository());
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Business\Model\CompanyUserPluginExecutorInterface
     */
    public function createCompanyUserPluginExecutor(): CompanyUserPluginExecutorInterface
    {
        return new CompanyUserPluginExecutor(
            $this->getCompanyUserSavePlugins(),
            $this->getCompanyUserHydrationPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Persistence\CompanyUserWriterRepositoryInterface
     */
    protected function createCompanyUserWriterRepository(): CompanyUserWriterRepositoryInterface
    {
        return new CompanyUserWriterPropelRepository();
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface
     */
    public function createCompanyUserRepository(): CompanyUserRepositoryInterface
    {
        return new CompanyUserPropelRepository();
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Dependency\Facade\CompanyUserToCustomerFacadeInterface
     */
    protected function getCustomerFacade(): CompanyUserToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(CompanyUserDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Dependency\Plugin\CompanyUserSavePluginInterface[]
     */
    protected function getCompanyUserSavePlugins(): array
    {
        return $this->getProvidedDependency(CompanyUserDependencyProvider::PLUGINS_CUSTOMER_SAVE);
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Dependency\Plugin\CompanyUserHydrationPluginInterface[]
     */
    protected function getCompanyUserHydrationPlugins(): array
    {
        return $this->getProvidedDependency(CompanyUserDependencyProvider::PLUGINS_CUSTOMER_HYDRATE);
    }
}
