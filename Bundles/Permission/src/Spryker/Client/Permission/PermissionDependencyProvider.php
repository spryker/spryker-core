<?php


namespace Spryker\Client\Permission;


use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Permission\Dependency\Client\PermissionToCustomerClientBridge;

class PermissionDependencyProvider extends AbstractDependencyProvider
{
    const PLUGINS_PERMISSION = 'PLUGINS_PERMISSION';
    const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addPermissionPlugins($container);
        $container = $this->addCustomerClient($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addPermissionPlugins(Container $container)
    {
        $container[static::PLUGINS_PERMISSION] = function (Container $container) {
            return $this->getPermissionPlugins();
        };

        return $container;
    }

    /**
     * @return array
     */
    protected function getPermissionPlugins()
    {
        return [
            new \Spryker\Client\CheckoutPermissionConnector\Plugin\CheckoutPlaceOrderPermissionPlugin()
        ];
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addCustomerClient(Container $container)
    {
        $container[static::CLIENT_CUSTOMER] = function (Container $container) {
            return new PermissionToCustomerClientBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }
}