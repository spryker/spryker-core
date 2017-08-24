<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToGlossaryBridge;
use Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToLocaleBridge;
use Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToProductAttributeBridge;
use Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductBridge;
use Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerBridge as ProductAttributeGuiToProductAttributeQueryContainerBridge;

class ProductAttributeGuiDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_LOCALE = 'FACADE_LOCALE';
    const FACADE_PRODUCT_ATTRIBUTE = 'FACADE_PRODUCT_ATTRIBUTE';
    const FACADE_GLOSSARY = 'FACADE_GLOSSARY';

    const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';
    const QUERY_CONTAINER_PRODUCT_ATTRIBUTE = 'QUERY_CONTAINER_PRODUCT_ATTRIBUTE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addLocaleFacade($container);
        $container = $this->addProductAttributeFacade($container);
        $container = $this->addGlossaryFacade($container);

        $container = $this->addProductQueryContainer($container);
        $container = $this->addProductAttributeQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return new ProductAttributeGuiToProductBridge($container->getLocator()->product()->queryContainer());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container)
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new ProductAttributeGuiToLocaleBridge($container->getLocator()->locale()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAttributeFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT_ATTRIBUTE] = function (Container $container) {
            return new ProductAttributeGuiToProductAttributeBridge($container->getLocator()->productAttribute()->facade());
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addProductAttributeQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT_ATTRIBUTE] = function (Container $container) {
            return new ProductAttributeGuiToProductAttributeQueryContainerBridge(
                $container->getLocator()->productAttribute()->queryContainer()
            );
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addGlossaryFacade(Container $container)
    {
        $container[static::FACADE_GLOSSARY] = function (Container $container) {
            return new ProductAttributeGuiToGlossaryBridge(
                $container->getLocator()->glossary()->facade()
            );
        };

        return $container;
    }
}
