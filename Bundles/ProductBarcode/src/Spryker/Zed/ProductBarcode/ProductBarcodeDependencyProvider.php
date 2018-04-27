<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductBarcode\Dependency\Facade\ProductBarcodeToProductBridge;

class ProductBarcodeDependencyProvider extends AbstractBundleDependencyProvider
{
    public const BARCODE_SERVICE = 'BARCODE_SERVICE';
    public const PRODUCT_FACADE = 'PRODUCT_FACADE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addBarcodeService($container);
        $container = $this->addProductFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addBarcodeService(Container $container): Container
    {
        $container[static::BARCODE_SERVICE] = function (Container $container) {
            return $container->getLocator()->barcode()->service();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container): Container
    {
        $container[static::PRODUCT_FACADE] = function (Container $container) {
            return new ProductBarcodeToProductBridge($container->getLocator()->product()->facade());
        };
    }
}
