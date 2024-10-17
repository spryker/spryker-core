<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToLocaleFacadeBridge;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductAlternativeFacadeBridge;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductBridge;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelBridge;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToStoreFacadeBridge;

/**
 * @method \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig getConfig()
 */
class ProductAlternativeProductLabelConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_PRODUCT_LABEL = 'FACADE_PRODUCT_LABEL';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_ALTERNATIVE = 'FACADE_PRODUCT_ALTERNATIVE';

    /**
     * @var string
     */
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';

    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addProductLabelFacade($container);
        $container = $this->addProductFacade($container);
        $container = $this->addProductAlternativeFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addStoreFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductLabelFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_LABEL, function (Container $container) {
            return new ProductAlternativeProductLabelConnectorToProductLabelBridge(
                $container->getLocator()->productLabel()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT, function (Container $container) {
            return new ProductAlternativeProductLabelConnectorToProductBridge(
                $container->getLocator()->product()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAlternativeFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_ALTERNATIVE, function (Container $container) {
            return new ProductAlternativeProductLabelConnectorToProductAlternativeFacadeBridge(
                $container->getLocator()->productAlternative()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new ProductAlternativeProductLabelConnectorToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new ProductAlternativeProductLabelConnectorToStoreFacadeBridge(
                $container->getLocator()->store()->facade(),
            );
        });

        return $container;
    }
}
