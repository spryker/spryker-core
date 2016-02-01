<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionExporter;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOptionExporter\Dependency\Facade\ProductOptionExporterToProductBridge;
use Spryker\Zed\ProductOptionExporter\Dependency\Facade\ProductOptionExporterToProductOptionBridge;

class ProductOptionExporterDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_PRODUCT_OPTION = 'FACADE_PRODUCT_OPTION';
    const FACADE_PRODUCT = 'FACADE_PRODUCT';

    /**
     * @param Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_PRODUCT_OPTION] = function (Container $container) {
            return new ProductOptionExporterToProductOptionBridge($container->getLocator()->productOption()->facade());
        };

        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return new ProductOptionExporterToProductBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }

}
