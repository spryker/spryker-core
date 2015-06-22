<?php

namespace SprykerFeature\Zed\ProductOptionExporter;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class ProductOptionExporterDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_PRODUCT_OPTION = 'product option facade';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[ProductOptionExporterDependencyProvider::FACADE_PRODUCT_OPTION] = function (Container $container) {
            return $container->getLocator()->productOption()->queryContainer();
        };

        return $container;
    }
}
