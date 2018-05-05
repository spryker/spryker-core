<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcodeGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductBarcodeGui\Dependency\Facade\ProductBarcodeGuiToLocaleBridge;
use Spryker\Zed\ProductBarcodeGui\Dependency\Service\ProductBarcodeGuiToBarcodeServiceBridge;

class ProductBarcodeGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_BARCODE = 'SERVICE_BARCODE';
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addBarcodeService($container);
        $container = $this->addLocaleFacade($container);

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
            return new ProductBarcodeGuiToBarcodeServiceBridge(
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
