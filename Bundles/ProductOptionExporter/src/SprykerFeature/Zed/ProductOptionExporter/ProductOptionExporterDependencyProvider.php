<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionExporter;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class ProductOptionExporterDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_PRODUCT_OPTION = 'FACADE_PRODUCT_OPTION';

    const FACADE_PRODUCT = 'FACADE_PRODUCT';

    const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[ProductOptionExporterDependencyProvider::FACADE_PRODUCT_OPTION] = function (Container $container) {
            return $container->getLocator()->productOption()->facade();
        };

        $container[ProductOptionExporterDependencyProvider::FACADE_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->facade();
        };

        $container[ProductOptionExporterDependencyProvider::FACADE_LOCALE] = function (Container $container) {
            return $container->getLocator()->locale()->facade();
        };

        return $container;
    }
}
