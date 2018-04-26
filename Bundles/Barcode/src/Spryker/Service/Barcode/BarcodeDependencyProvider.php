<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;

class BarcodeDependencyProvider extends AbstractBundleDependencyProvider
{
    public const BARCODE_PLUGINS = 'BARCODE_PLUGINS';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container): Container
    {
        $container = $this->addBarcodePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addBarcodePlugins(Container $container): Container
    {
        $container[static::BARCODE_PLUGINS] = function (Container $container) {
            return $this->getBarcodePlugins();
        };

        return $container;
    }

    /**
     * @return array
     */
    protected function getBarcodePlugins(): array
    {
        return [];
    }
}
