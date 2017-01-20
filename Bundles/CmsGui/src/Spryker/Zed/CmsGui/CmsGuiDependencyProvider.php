<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui;

use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsBridge;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToLocaleBridge;
use Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerBrige;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_LOCALE = 'locale facade';
    const FACADE_CMS = 'locale cms';

    const QUERY_CONTAINER_CMS = 'cms query container';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new CmsGuiToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_CMS] = function (Container $container) {
            return new CmsGuiToCmsBridge($container->getLocator()->cms()->facade());
        };

        $container[self::QUERY_CONTAINER_CMS] = function (Container $container) {
            return new CmsGuiToCmsQueryContainerBrige($container->getLocator()->cms()->queryContainer());
        };


        return $container;
    }
}
