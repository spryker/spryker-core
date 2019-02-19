<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserAuthRestApi;

use Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToOauthClientBridge;
use Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToOauthCompanyUserClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\CompanyUserAuthRestApi\CompanyUserAuthRestApiConfig getConfig()
 */
class CompanyUserAuthRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_OAUTH = 'CLIENT_OAUTH';
    public const CLIENT_OAUTH_COMPANY_USER = 'CLIENT_OAUTH_COMPANY_USER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addOauthClient($container);
        $container = $this->addOauthCompanyUserClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addOauthClient(Container $container): Container
    {
        $container[static::CLIENT_OAUTH] = function (Container $container) {
            return new CompanyUserAuthRestApiToOauthClientBridge($container->getLocator()->oauth()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addOauthCompanyUserClient(Container $container): Container
    {
        $container[static::CLIENT_OAUTH_COMPANY_USER] = function (Container $container) {
            return new CompanyUserAuthRestApiToOauthCompanyUserClientBridge($container->getLocator()->oauthCompanyUser()->client());
        };

        return $container;
    }
}
