<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Dependency\Facade;

use Generated\Shared\Transfer\ShipmentMethodCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodCriteriaTransfer;

interface ClickAndCollectExampleToShipmentFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodCriteriaTransfer $shipmentMethodCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer
     */
    public function getShipmentMethodCollection(ShipmentMethodCriteriaTransfer $shipmentMethodCriteriaTransfer): ShipmentMethodCollectionTransfer;
}
