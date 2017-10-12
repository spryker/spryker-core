<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToCollectorBridge;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToGlossaryBridge;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleBridge;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToProductBridge;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToTouchBridge;

class ProductSearchDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_PRODUCT = 'product facade';
    const FACADE_LOCALE = 'locale facade';
    const FACADE_GLOSSARY = 'glossary facade';
    const FACADE_TOUCH = 'touch facade';
    const FACADE_COLLECTOR = 'collector facade';
    const QUERY_CONTAINER_TOUCH = 'touch query container';
    const SERVICE_DATA = 'util data service';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->provideProductFacade($container);
        $this->provideLocaleFacade($container);
        $this->provideGlossaryFacade($container);
        $this->provideTouchFacade($container);
        $this->provideCollectorFacade($container);
        $this->provideTouchQueryContainer($container);
        $this->provideUtilDataReaderService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $this->provideLocaleFacade($container);
        $this->provideGlossaryFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideProductFacade(Container $container)
    {
        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return new ProductSearchToProductBridge($container->getLocator()->product()->facade());
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
            return new ProductSearchToLocaleBridge($container->getLocator()->locale()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideGlossaryFacade(Container $container)
    {
        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return new ProductSearchToGlossaryBridge($container->getLocator()->glossary()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideTouchFacade(Container $container)
    {
        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new ProductSearchToTouchBridge($container->getLocator()->touch()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideCollectorFacade(Container $container)
    {
        $container[self::FACADE_COLLECTOR] = function (Container $container) {
            return new ProductSearchToCollectorBridge($container->getLocator()->collector()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideTouchQueryContainer(Container $container)
    {
        $container[self::QUERY_CONTAINER_TOUCH] = function (Container $container) {
            return $container->getLocator()->touch()->queryContainer();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideUtilDataReaderService(Container $container)
    {
        $container[static::SERVICE_DATA] = function (Container $container) {
            return $container->getLocator()->utilDataReader()->service();
        };
    }
}
