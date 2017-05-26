<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToLocaleBridge;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToProductSetBridge;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToUrlBridge;
use Spryker\Zed\ProductSetGui\Dependency\QueryContainer\ProductSetGuiToProductSetBridge as ProductSetGuiToProductSetQueryContainerBridge;
use Spryker\Zed\ProductSetGui\Dependency\Service\ProductSetGuiToUtilEncodingBridge;

class ProductSetGuiDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_PRODUCT_SET = 'FACADE_ProductSet';
    const FACADE_LOCALE = 'FACADE_LOCALE';
    const FACADE_URL = 'FACADE_URL';

    const QUERY_CONTAINER_PRODUCT_SET = 'QUERY_CONTAINER_PRODUCT_SET';

    const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $this->provideProductSetFacade($container);
        $this->provideLocaleFacade($container);
        $this->provideUrlFacade($container);

        $this->provideUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $this->provideProductSetQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideProductSetFacade(Container $container)
    {
        $container[self::FACADE_PRODUCT_SET] = function (Container $container) {
            return new ProductSetGuiToProductSetBridge($container->getLocator()->productSet()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideLocaleFacade(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new ProductSetGuiToLocaleBridge($container->getLocator()->locale()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideUrlFacade(Container $container)
    {
        $container[self::FACADE_URL] = function (Container $container) {
            return new ProductSetGuiToUrlBridge($container->getLocator()->url()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideProductSetQueryContainer(Container $container)
    {
        $container[self::QUERY_CONTAINER_PRODUCT_SET] = function (Container $container) {
            return new ProductSetGuiToProductSetQueryContainerBridge($container->getLocator()->productSet()->queryContainer());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideUtilEncodingService(Container $container)
    {
        $container[self::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new ProductSetGuiToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };
    }

}
