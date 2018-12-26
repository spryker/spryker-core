<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi;

use Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\CompanyUsersRestApi\CompanyUsersRestApiConfig getConfig()
 */
class CompanyUsersRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_COMPANY_USER = 'CLIENT_COMPANY_USER';
    public const PLUGINS_COMPANY_USERS_RESOURCE_MAPPER = 'PLUGINS_COMPANY_USERS_RESOURCE_MAPPER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCompanyUserClient($container);
        $container = $this->addCompanyUsersResourceMapperPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCompanyUserClient(Container $container): Container
    {
        $container[static::CLIENT_COMPANY_USER] = function (Container $container) {
            return new CompanyUsersRestApiToCompanyUserClientBridge($container->getLocator()->companyUser()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCompanyUsersResourceMapperPlugins(Container $container): Container
    {
        $container[static::PLUGINS_COMPANY_USERS_RESOURCE_MAPPER] = function (Container $container) {
            return $this->getCompanyUsersResourceMapperPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Glue\CompanyUsersRestApiExtension\Dependency\Plugin\CompanyUsersResourceMapperPluginInterface[]
     */
    protected function getCompanyUsersResourceMapperPlugins(): array
    {
        return [];
    }
}
