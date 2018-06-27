<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToGlossaryFacadeBridge;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToLocaleFacadeBridge;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductAlternativeFacadeBridge;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductBridge;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelBridge;

class ProductAlternativeProductLabelConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT_LABEL = 'FACADE_PRODUCT_LABEL';
    public const FACADE_PRODUCT_ALTERNATIVE = 'FACADE_PRODUCT_ALTERNATIVE';
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
        $this->addProductAlternativeFacade($container);
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
            return new ProductAlternativeProductLabelConnectorToProductLabelBridge(
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
            return new ProductAlternativeProductLabelConnectorToProductBridge(
                $container->getLocator()->product()->facade()
            );
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductAlternativeFacade(Container $container): void
    {
        $container[static::FACADE_PRODUCT_ALTERNATIVE] = function (Container $container) {
            return new ProductAlternativeProductLabelConnectorToProductAlternativeFacadeBridge(
                $container->getLocator()->productAlternative()->facade()
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
            return new ProductAlternativeProductLabelConnectorToGlossaryFacadeBridge(
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
            return new ProductAlternativeProductLabelConnectorToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade()
            );
        };
    }
}
