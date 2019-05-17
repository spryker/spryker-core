<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\BusinessOnBehalf;

use Spryker\Client\BusinessOnBehalf\Dependency\Client\BusinessOnBehalfToZedRequestClientBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class BusinessOnBehalfDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';
    public const PLUGINS_CUSTOMER_CHANGE_ALLOWED_CHECK = 'PLUGINS_CUSTOMER_CHANGE_ALLOWED_CHECK';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addZedRequestClient($container);
        $container = $this->addCustomerChangeAllowedCheckPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addZedRequestClient(Container $container): Container
    {
        $container[static::CLIENT_ZED_REQUEST] = function (Container $container) {
            return new BusinessOnBehalfToZedRequestClientBridge($container->getLocator()->zedRequest()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCustomerChangeAllowedCheckPlugins(Container $container): Container
    {
        $container[static::PLUGINS_CUSTOMER_CHANGE_ALLOWED_CHECK] = function () {
            return $this->getCustomerChangeAllowedCheckPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\BusinessOnBehalfExtension\Dependency\Plugin\CustomerChangeAllowedCheckPluginInterface[]
     */
    protected function getCustomerChangeAllowedCheckPlugins(): array
    {
        return [];
    }
}
