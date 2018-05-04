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
    public const BARCODE_SERVICE = 'BARCODE_SERVICE';
    public const LOCALE_FACADE = 'LOCALE_FACADE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
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
        $container[static::BARCODE_SERVICE] = function (Container $container) {
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
        $container[static::LOCALE_FACADE] = function (Container $container) {
            return new ProductBarcodeGuiToLocaleBridge(
                $container->getLocator()->locale()->facade()
            );
        };

        return $container;
    }
}
