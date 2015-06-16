<?php

namespace SprykerFeature\Zed\Sales;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class SalesDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_OMS = 'oms facade';

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

        return $container;
    }
}
