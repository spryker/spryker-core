<?php

namespace Spryker\Zed\CheckoutPermissionConnector;


use Spryker\Zed\CheckoutPermissionConnector\Dependency\CheckoutPermissionConnectorToFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CheckoutPermissionConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_PERMISSION = 'FACADE_PERMISSION';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addPermissionFacade($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addPermissionFacade(Container $container)
    {
        $container[static::FACADE_PERMISSION] = function (Container $container) {
            return new CheckoutPermissionConnectorToFacadeBridge($container->getLocator()->permission()->facade());
        };

        return $container;
    }
}