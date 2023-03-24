<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Business;

use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Business\Generator\MultiShipmentPickingListGenerator;
use Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Business\Generator\MultiShipmentPickingListGeneratorInterface;
use Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\PickingListMultiShipmentPickingStrategyExampleDependencyProvider;

/**
 * @method \Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\PickingListMultiShipmentPickingStrategyExampleConfig getConfig()
 */
class PickingListMultiShipmentPickingStrategyExampleBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Business\Generator\MultiShipmentPickingListGeneratorInterface
     */
    public function createMultiShipmentPickingListGenerator(): MultiShipmentPickingListGeneratorInterface
    {
        return new MultiShipmentPickingListGenerator($this->getShipmentService());
    }

    /**
     * @return \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    public function getShipmentService(): ShipmentServiceInterface
    {
        return $this->getProvidedDependency(PickingListMultiShipmentPickingStrategyExampleDependencyProvider::SERVICE_SHIPMENT);
    }
}
