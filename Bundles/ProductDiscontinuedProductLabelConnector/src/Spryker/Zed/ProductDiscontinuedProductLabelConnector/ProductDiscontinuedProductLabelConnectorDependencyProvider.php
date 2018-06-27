<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductLabelConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToGlossaryFacadeBridge;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToLocaleFacadeBridge;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductBridge;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductDiscontinuedFacadeBridge;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductLabelBridge;

class ProductDiscontinuedProductLabelConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT_LABEL = 'FACADE_PRODUCT_LABEL';
    public const FACADE_PRODUCT_DISCONTINUED = 'FACADE_PRODUCT_DISCONTINUED';
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->addProductLabelFacade($container);
        $this->addProductFacade($container);
        $this->addProductDiscontinuedFacade($container);
        $this->addGlossaryFacade($container);
        $this->addLocaleFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductLabelFacade(Container $container): void
    {
        $container[static::FACADE_PRODUCT_LABEL] = function (Container $container) {
            return new ProductDiscontinuedProductLabelConnectorToProductLabelBridge(
                $container->getLocator()->productLabel()->facade()
            );
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductFacade(Container $container): void
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new ProductDiscontinuedProductLabelConnectorToProductBridge(
                $container->getLocator()->product()->facade()
            );
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductDiscontinuedFacade(Container $container): void
    {
        $container[static::FACADE_PRODUCT_DISCONTINUED] = function (Container $container) {
            return new ProductDiscontinuedProductLabelConnectorToProductDiscontinuedFacadeBridge(
                $container->getLocator()->productDiscontinued()->facade()
            );
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addGlossaryFacade(Container $container): void
    {
        $container[static::FACADE_GLOSSARY] = function (Container $container) {
            return new ProductDiscontinuedProductLabelConnectorToGlossaryFacadeBridge(
                $container->getLocator()->glossary()->facade()
            );
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addLocaleFacade(Container $container): void
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new ProductDiscontinuedProductLabelConnectorToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade()
            );
        };
    }
}
