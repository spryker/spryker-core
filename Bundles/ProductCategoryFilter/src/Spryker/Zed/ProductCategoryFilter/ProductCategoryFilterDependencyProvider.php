<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilter;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductCategoryFilter\Dependency\Facade\ProductCategoryFilterToTouchFacadeBridge;
use Spryker\Zed\ProductCategoryFilter\Dependency\Service\ProductCategoryFilterToUtilEncodingServiceBridge;

class ProductCategoryFilterDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_TOUCH = 'FACADE_TOUCH';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->addTouchFacade($container);
        $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addTouchFacade(Container $container)
    {
        $container[static::FACADE_TOUCH] = function (Container $container) {
            return new ProductCategoryFilterToTouchFacadeBridge($container->getLocator()->touch()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addUtilEncodingService(Container $container)
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new ProductCategoryFilterToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        };
    }
}
