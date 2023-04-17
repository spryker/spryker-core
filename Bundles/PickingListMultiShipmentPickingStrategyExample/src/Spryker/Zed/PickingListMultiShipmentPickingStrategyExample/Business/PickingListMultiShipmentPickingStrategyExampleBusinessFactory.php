<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Business\Generator\MultiShipmentPickingListGenerator;
use Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Business\Generator\MultiShipmentPickingListGeneratorInterface;
use Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Dependency\Facade\PickingListMultiShipmentPickingStrategyExampleToShipmentFacadeInterface;
use Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Dependency\Service\PickingListMultiShipmentPickingStrategyExampleToShipmentServiceInterface;
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
        return new MultiShipmentPickingListGenerator(
            $this->getShipmentFacade(),
            $this->getShipmentService(),
        );
    }

    /**
     * @return \Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Dependency\Facade\PickingListMultiShipmentPickingStrategyExampleToShipmentFacadeInterface
     */
    public function getShipmentFacade(): PickingListMultiShipmentPickingStrategyExampleToShipmentFacadeInterface
    {
        return $this->getProvidedDependency(PickingListMultiShipmentPickingStrategyExampleDependencyProvider::FACADE_SHIPMENT);
    }

    /**
     * @return \Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Dependency\Service\PickingListMultiShipmentPickingStrategyExampleToShipmentServiceInterface
     */
    public function getShipmentService(): PickingListMultiShipmentPickingStrategyExampleToShipmentServiceInterface
    {
        return $this->getProvidedDependency(PickingListMultiShipmentPickingStrategyExampleDependencyProvider::SERVICE_SHIPMENT);
    }
}
