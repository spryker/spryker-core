<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Dependency\Facade;

use Generated\Shared\Transfer\ShipmentMethodCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodCriteriaTransfer;

class ClickAndCollectExampleToShipmentFacadeBridge implements ClickAndCollectExampleToShipmentFacadeInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @param \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface $shipmentFacade
     */
    public function __construct($shipmentFacade)
    {
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodCriteriaTransfer $shipmentMethodCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer
     */
    public function getShipmentMethodCollection(ShipmentMethodCriteriaTransfer $shipmentMethodCriteriaTransfer): ShipmentMethodCollectionTransfer
    {
        return $this->shipmentFacade->getShipmentMethodCollection($shipmentMethodCriteriaTransfer);
    }
}
