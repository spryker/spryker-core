<?php

namespace SprykerFeature\Zed\ProductOptionExporter;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class ProductOptionExporterDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_PRODUCT = 'FACADE_PRODUCT';

    const FACADE_LOCALE = 'FACADE_LOCALE';

    const QUERY_CONTAINER_PRODUCT_OPTION = 'QUERY_CONTAINER_PRODUCT_OPTION';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[ProductOptionExporterDependencyProvider::FACADE_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->facade();
        };

        $container[ProductOptionExporterDependencyProvider::FACADE_LOCALE] = function (Container $container) {
            return $container->getLocator()->locale()->facade();
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container[ProductOptionExporterDependencyProvider::QUERY_CONTAINER_PRODUCT_OPTION] = function (Container $container) {
            return $container->getLocator()->productOption()->queryContainer();
        };

        return $container;
    }
}
