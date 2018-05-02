<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcodeGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ProductBarcodeGuiDependencyProvider extends AbstractBundleDependencyProvider
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
}
