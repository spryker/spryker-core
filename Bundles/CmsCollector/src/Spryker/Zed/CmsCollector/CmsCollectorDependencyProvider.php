<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector;

use Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCmsBridge;
use Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCollectorBridge;
use Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToSearchBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsCollectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_COLLECTOR = 'FACADE_COLLECTOR';
    public const FACADE_SEARCH = 'FACADE_SEARCH';
    public const FACADE_CMS = 'FACADE_CMS';

    public const QUERY_CONTAINER_TOUCH = 'QUERY_CONTAINER_TOUCH';

    public const SERVICE_DATA_READER = 'SERVICE_DATA_READER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::SERVICE_DATA_READER] = function (Container $container) {
            return $container->getLocator()->utilDataReader()->service();
        };

        $container[static::FACADE_COLLECTOR] = function (Container $container) {
            return new CmsCollectorToCollectorBridge($container->getLocator()->collector()->facade());
        };

        $container[static::FACADE_SEARCH] = function (Container $container) {
            return new CmsCollectorToSearchBridge($container->getLocator()->search()->facade());
        };

        $container[static::QUERY_CONTAINER_TOUCH] = function (Container $container) {
            return $container->getLocator()->touch()->queryContainer();
        };

        $container = $this->addCmsFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCmsFacade(Container $container)
    {
        $container[static::FACADE_CMS] = function (Container $container) {
            return new CmsCollectorToCmsBridge($container->getLocator()->cms()->facade());
        };

        return $container;
    }
}
