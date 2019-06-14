<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyRolesRestApi;

use Spryker\Glue\CompanyRolesRestApi\Dependency\Client\CompanyRolesRestApiToCompanyRoleClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\CompanyRolesRestApi\CompanyRolesRestApiConfig getConfig()
 */
class CompanyRolesRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_COMPANY_ROLE = 'CLIENT_COMPANY_ROLE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCompanyRoleClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCompanyRoleClient(Container $container): Container
    {
        $container[static::CLIENT_COMPANY_ROLE] = function (Container $container) {
            return new CompanyRolesRestApiToCompanyRoleClientBridge(
                $container->getLocator()->companyRole()->client()
            );
        };

        return $container;
    }
}
