<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUsersRestApi;

use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Zed\CompanyUsersRestApi\Dependency\Facade\CompanyUsersRestApiToCompanyUserFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CompanyUsersRestApi\CompanyUsersRestApiConfig getConfig()
 */
class CompanyUsersRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_QUERY_COMPANY_USER = 'PROPEL_QUERY_COMPANY_USER';
    public const PROPEL_QUERY_COMPANY_ROLE = 'PROPEL_QUERY_COMPANY_ROLE';
    public const FACADE_COMPANY_USER = 'FACADE_COMPANY_USER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCompanyUserFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addCompanyUserPropelQuery($container);
        $container = $this->addCompanyRolePropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_COMPANY_USER, $container->factory(function () {
            return SpyCompanyUserQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyRolePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_COMPANY_ROLE, $container->factory(function () {
            return SpyCompanyRoleQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_COMPANY_USER, function (Container $container) {
            return new CompanyUsersRestApiToCompanyUserFacadeBridge(
                $container->getLocator()->companyUser()->facade()
            );
        });

        return $container;
    }
}
