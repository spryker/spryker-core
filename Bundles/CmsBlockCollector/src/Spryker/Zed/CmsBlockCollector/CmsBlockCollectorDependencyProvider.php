<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCollector;

use Spryker\Zed\CmsBlockCollector\Dependency\Facade\CmsBlockCollectorToCollectorBridge;
use Spryker\Zed\CmsBlockCollector\Dependency\Service\CmsBlockCollectorToUtilEncodingBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsBlockCollectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_COLLECTOR = 'FACADE_COLLECTOR';

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
            return new CmsBlockCollectorToCollectorBridge($container->getLocator()->collector()->facade());
        };

        $container[self::QUERY_CONTAINER_TOUCH] = function (Container $container) {
            return $container->getLocator()->touch()->queryContainer();
        };

        $container[self::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new CmsBlockCollectorToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }

}
