<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsUserConnector;

use Spryker\Zed\CmsUserConnector\Dependency\Facade\CmsUserConnectorToUserBridge;
use Spryker\Zed\CmsUserConnector\Dependency\QueryContainer\CmsUserConnectorToCmsQueryContainer;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsUserConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_USER = 'user facade';
    const QUERY_CONTAINER_CMS = 'cms query container';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_USER] = function (Container $container) {
            return new CmsUserConnectorToUserBridge($container->getLocator()->user()->facade());
        };

        $container[self::QUERY_CONTAINER_CMS] = function (Container $container) {
            return new CmsUserConnectorToCmsQueryContainer($container->getLocator()->cms()->queryContainer());
        };

        return $container;
    }

}
