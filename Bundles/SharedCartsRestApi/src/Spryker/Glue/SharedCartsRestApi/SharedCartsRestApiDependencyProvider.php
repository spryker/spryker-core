<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\SharedCartsRestApi\Processor\Exception\MissingCompanyUserProviderPluginException;
use Spryker\Glue\SharedCartsRestApiExtension\Dependency\Plugin\CompanyUserProviderPluginInterface;

/**
 * @method \Spryker\Glue\SharedCartsRestApi\SharedCartsRestApiConfig getConfig()
 */
class SharedCartsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_COMPANY_USER_PROVIDER = 'PLUGIN_COMPANY_USER_PROVIDER';
    protected const EXCEPTION_MESSAGE_MISSING_COMPANY_USER_PROVIDER_PLUGIN = 'Missing instance of %s! You need to configure CompanyUserProviderPlugin in your own SharedCartsRestApiDependencyProvider::getCompanyUserProviderPlugin() to be able share carts.';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCompanyUserProviderPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCompanyUserProviderPlugin(Container $container): Container
    {
        $container[static::PLUGIN_COMPANY_USER_PROVIDER] = function (Container $container) {
            return $this->getCompanyUserProviderPlugin();
        };

        return $container;
    }

    /**
     * @throws \Spryker\Glue\SharedCartsRestApi\Processor\Exception\MissingCompanyUserProviderPluginException
     *
     * @return \Spryker\Glue\SharedCartsRestApiExtension\Dependency\Plugin\CompanyUserProviderPluginInterface
     */
    protected function getCompanyUserProviderPlugin(): CompanyUserProviderPluginInterface
    {
        throw new MissingCompanyUserProviderPluginException(
            sprintf(
                static::EXCEPTION_MESSAGE_MISSING_COMPANY_USER_PROVIDER_PLUGIN,
                CompanyUserProviderPluginInterface::class
            )
        );
    }
}
