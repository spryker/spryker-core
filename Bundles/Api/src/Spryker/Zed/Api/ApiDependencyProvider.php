<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api;

use Spryker\Zed\Api\Dependency\QueryContainer\ApiToPropelQueryBuilderBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ApiDependencyProvider extends AbstractBundleDependencyProvider
{

    const QUERY_CONTAINER_PROPEL_QUERY_BUILDER = 'QUERY_CONTAINER_PROPEL_QUERY_BUILDER';
    const SERVICE_ENCODING = 'SERVICE_ENCODING';
    const PLUGINS_API = 'PLUGINS_API';
    const PLUGINS_API_VALIDATOR = 'PLUGINS_API_VALIDATOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container[static::SERVICE_ENCODING] = function (Container $container) {
            return $container->getLocator()->utilEncoding()->service();
        };

        $container = $this->provideApiPlugins($container);
        $container = $this->provideApiValidatorPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container[static::QUERY_CONTAINER_PROPEL_QUERY_BUILDER] = function (Container $container) {
            return new ApiToPropelQueryBuilderBridge($container->getLocator()->propelQueryBuilder()->queryContainer());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideApiPlugins(Container $container)
    {
        $container[static::PLUGINS_API] = function (Container $container) {
            return $this->getApiPluginCollection();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideApiValidatorPlugins(Container $container)
    {
        $container[static::PLUGINS_API_VALIDATOR] = function (Container $container) {
            return $this->getApiValidatorPluginCollection();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface[]
     */
    protected function getApiPluginCollection()
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface[]
     */
    protected function getApiValidatorPluginCollection()
    {
        return [];
    }

}
