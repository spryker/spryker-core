<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector;

use Spryker\Zed\CmsCollector\Communication\Plugin\CmsVersionPageDataPageMapPlugin;
use Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCollectorBridge;
use Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToSearchBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsCollectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_COLLECTOR = 'FACADE_COLLECTOR';
    const SERVICE_DATA_READER = 'SERVICE_DATA_READER';
    const QUERY_CONTAINER_TOUCH = 'QUERY_CONTAINER_TOUCH';
    const PLUGIN_PRODUCT_DATA_PAGE_MAP = 'PLUGIN_PRODUCT_DATA_PAGE_MAP';
    const FACADE_SEARCH = 'FACADE_SEARCH';
    const PLUGIN_CMS_PAGE_DATA_PAGE_MAP = 'PLUGIN_CMS_PAGE_DATA_PAGE_MAP';

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

        $container[self::PLUGIN_CMS_PAGE_DATA_PAGE_MAP] = function (Container $container) {
            return $this->createCmsVersionPageDataPageMapPlugin($container);
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return CmsVersionPageDataPageMapPlugin
     */
    function createCmsVersionPageDataPageMapPlugin(Container $container)
    {
        return new CmsVersionPageDataPageMapPlugin();
    }

}
