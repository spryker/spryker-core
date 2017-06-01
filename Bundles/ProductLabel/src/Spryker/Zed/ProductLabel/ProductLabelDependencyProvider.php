<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToTouchBridge;
use Spryker\Zed\ProductLabel\Dependency\Service\ProductLabelToUtilDateTimeBridge;

class ProductLabelDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_TOUCH = 'facade_touch';
    const SERVICE_DATE_TIME = 'service_date_time';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->provideTouchFacade($container);
        $container = $this->provideDateTimeService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideTouchFacade(Container $container)
    {
        $container[static::FACADE_TOUCH] = function (Container $container) {
            return new ProductLabelToTouchBridge($container->getLocator()->touch()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideDateTimeService(Container $container)
    {
        $container[static::SERVICE_DATE_TIME] = function (Container $container) {
            return new ProductLabelToUtilDateTimeBridge($container->getLocator()->utilDateTime()->service());
        };

        return $container;
    }

}
