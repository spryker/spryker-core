<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class SalesDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_OMS = 'FACADE_OMS';

    const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[SalesDependencyProvider::FACADE_OMS] = function (Container $container){
            return $container->getLocator()->oms()->facade();
        };

        $container[SalesDependencyProvider::FACADE_LOCALE] = function (Container $container){
            return $container->getLocator()->locale()->facade();
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */

    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[SalesDependencyProvider::FACADE_OMS] = function (Container $container){
            return $container->getLocator()->oms()->facade();
        };

        return $container;
    }
}
