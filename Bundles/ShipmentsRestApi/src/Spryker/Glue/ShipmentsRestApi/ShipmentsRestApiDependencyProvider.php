<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig getConfig()
 */
class ShipmentsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_ADDRESS_SOURCE_PROVIDER = 'PLUGINS_ADDRESS_SOURCE_VALIDATOR';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addAddressSourceValidatorPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addAddressSourceValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ADDRESS_SOURCE_PROVIDER, function () {
            return $this->getAddressSourceProviderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\AddressSourceProvidePluginInterface[]
     */
    protected function getAddressSourceProviderPlugins(): array
    {
        return [];
    }
}
