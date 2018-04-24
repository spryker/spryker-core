<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ProductBarcodeDependencyProvider extends AbstractBundleDependencyProvider
{
    public const BARCODE_GENERATOR_SERVICE = 'BARCODE_GENERATOR_SERVICE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addBarcodeGeneratorService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addBarcodeGeneratorService(Container $container): Container
    {
        $container[static::BARCODE_GENERATOR_SERVICE] = function (Container $container) {
            return $container->getLocator()->barcode()->service();
        };

        return $container;
    }
}
