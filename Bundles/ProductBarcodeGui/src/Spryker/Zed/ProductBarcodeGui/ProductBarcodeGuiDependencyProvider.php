<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcodeGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductBarcodeGui\Dependency\Facade\ProductBarcodeGuiToLocaleBridge;
use Spryker\Zed\ProductBarcodeGui\Dependency\Facade\ProductBarcodeGuiToProductBarcodeFacadeBridge;

class ProductBarcodeGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_PRODUCT_BARCODE = 'FACADE_PRODUCT_BARCODE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addProductBarcodeFacade($container);
        $container = $this->addLocaleFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductBarcodeFacade(Container $container): Container
    {
        $container[static::FACADE_PRODUCT_BARCODE] = function (Container $container) {
            return new ProductBarcodeGuiToProductBarcodeFacadeBridge(
                $container->getLocator()->productBarcode()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new ProductBarcodeGuiToLocaleBridge(
                $container->getLocator()->locale()->facade()
            );
        };

        return $container;
    }
}
