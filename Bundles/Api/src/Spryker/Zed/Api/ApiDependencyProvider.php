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

    const PLUGIN_STACK_PRE_PROCESS = 'PLUGIN_STACK_PRE_PROCESS';
    const PLUGIN_STACK_POST_PROCESS = 'PLUGIN_STACK_POST_PROCESS';

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

}
