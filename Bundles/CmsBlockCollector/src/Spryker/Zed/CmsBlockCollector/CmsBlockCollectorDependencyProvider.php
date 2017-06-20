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

    const FACADE_COLLECTOR = 'CMS_BLOCL_COLLECTOR:FACADE_COLLECTOR';

    const QUERY_CONTAINER_TOUCH = 'CMS_BLOCL_COLLECTOR:QUERY_CONTAINER_TOUCH';

    const SERVICE_DATA_READER = 'CMS_BLOCL_COLLECTOR:SERVICE_DATA_READER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addUtilDataReaderService($container);
        $container = $this->addCollectorFacade($container);
        $container = $this->addTouchQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTouchQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_TOUCH] = function (Container $container) {
            return $container->getLocator()->touch()->queryContainer();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCollectorFacade(Container $container)
    {
        $container[static::FACADE_COLLECTOR] = function (Container $container) {
            return new CmsBlockCollectorToCollectorBridge($container->getLocator()->collector()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilDataReaderService(Container $container)
    {
        $container[static::SERVICE_DATA_READER] = function (Container $container) {
            return $container->getLocator()->utilDataReader()->service();
        };

        return $container;
    }

}
