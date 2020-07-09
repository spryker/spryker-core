<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBarcode;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductBarcode\Dependency\Service\ProductBarcodeToBarcodeServiceBridge;

class ProductBarcodeDependencyProvider extends AbstractDependencyProvider
{
    public const SERVICE_BARCODE = 'SERVICE_BARCODE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addBarcodeService($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addBarcodeService(Container $container): Container
    {
        $container->set(static::SERVICE_BARCODE, function (Container $container) {
            return new ProductBarcodeToBarcodeServiceBridge(
                $container->getLocator()->barcode()->service()
            );
        });

        return $container;
    }
}
