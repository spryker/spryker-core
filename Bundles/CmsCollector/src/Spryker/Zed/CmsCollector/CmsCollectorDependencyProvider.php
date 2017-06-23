<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector;

use Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCmsBridge;
use Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCollectorBridge;
use Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToSearchBridge;
use Spryker\Zed\CmsCollector\Dependency\Service\CmsCollectorToUtilEncodingBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsCollectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_COLLECTOR = 'FACADE_COLLECTOR';
    const FACADE_SEARCH = 'FACADE_SEARCH';
    const FACADE_CMS = 'FACADE_CMS';

    const QUERY_CONTAINER_TOUCH = 'QUERY_CONTAINER_TOUCH';

    const SERVICE_DATA_READER = 'SERVICE_DATA_READER';
    const SERVICE_UTIL_ENCODING = 'UTIL_ENCODING_SERVICE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::SERVICE_DATA_READER] = function (Container $container) {
            return $container->getLocator()->utilDataReader()->service();
        };

        $container[self::FACADE_COLLECTOR] = function (Container $container) {
            return new CmsCollectorToCollectorBridge($container->getLocator()->collector()->facade());
        };

        $container[self::FACADE_SEARCH] = function (Container $container) {
            return new CmsCollectorToSearchBridge($container->getLocator()->search()->facade());
        };

        $container[self::QUERY_CONTAINER_TOUCH] = function (Container $container) {
            return $container->getLocator()->touch()->queryContainer();
        };

        $container[static::FACADE_CMS] = function (Container $container) {
            return new CmsCollectorToCmsBridge($container->getLocator()->cms()->facade());
        };

        $container[self::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new CmsCollectorToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }

}
