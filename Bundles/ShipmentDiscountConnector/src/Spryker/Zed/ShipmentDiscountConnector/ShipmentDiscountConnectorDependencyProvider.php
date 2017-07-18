<?php


namespace Spryker\Zed\ShipmentDiscountConnector;


use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToDiscountBridge;
use Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToShipmentBridge;

class ShipmentDiscountConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_SHIPMENT = 'FACADE_SHIPMENT';
    const FACADE_DISCOUNT = 'FACADE_DISCOUNT';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addShipmentFacade($container);
        $container = $this->addDiscountFacade($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addShipmentFacade(Container $container)
    {
        $container[static::FACADE_SHIPMENT] = function (Container $container) {
            return new ShipmentDiscountConnectorToShipmentBridge($container->getLocator()->shipment()->facade());
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addDiscountFacade(Container $container)
    {
        $container[static::FACADE_DISCOUNT] = function (Container $container) {
            return new ShipmentDiscountConnectorToDiscountBridge($container->getLocator()->discount()->facade());
        };

        return $container;
    }
}