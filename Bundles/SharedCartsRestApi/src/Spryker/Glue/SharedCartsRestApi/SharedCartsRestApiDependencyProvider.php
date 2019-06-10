<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\SharedCartsRestApi\SharedCartsRestApiConfig getConfig()
 */
class SharedCartsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_COMPANY_USER_PROVIDER = 'PLUGINS_COMPANY_USER_PROVIDER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCompanyUserProviderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCompanyUserProviderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_COMPANY_USER_PROVIDER] = function (Container $container) {
            return $this->getCompanyUserProviderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Glue\SharedCartsRestApiExtension\Dependency\Plugin\CompanyUserProviderPluginInterface[]
     */
    protected function getCompanyUserProviderPlugins(): array
    {
        return [];
    }
}
