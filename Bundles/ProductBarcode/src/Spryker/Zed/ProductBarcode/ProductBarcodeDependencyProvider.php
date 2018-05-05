<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductBarcode\Dependency\Facade\ProductBarcodeToProductFacadeBridge;
use Spryker\Zed\ProductBarcode\Dependency\Service\ProductBarcodeToBarcodeServiceBridge;

class ProductBarcodeDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_BARCODE = 'SERVICE_BARCODE';
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';

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
        $container[static::SERVICE_BARCODE] = function (Container $container) {
            return new ProductBarcodeToBarcodeServiceBridge(
                $container->getLocator()->barcode()->service()
            );
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
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new ProductBarcodeToProductFacadeBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }
}
