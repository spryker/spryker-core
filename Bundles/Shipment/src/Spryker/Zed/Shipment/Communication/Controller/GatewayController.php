<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Controller;

use \ArrayObject;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupCollectionTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    public function getAvailableMethodsAction(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->getAvailableMethods($quoteTransfer);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemCollectionTransfer $itemCollectionTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupCollectionTransfer
     */
    public function getShipmentGroupsAction(ItemCollectionTransfer $itemCollectionTransfer): ShipmentGroupCollectionTransfer
    {
        $shipmentGroups = $this->getFacade()->getShipmentGroups($itemCollectionTransfer->getItems());

        $shipmentGroupCollectionTransfer = new ShipmentGroupCollectionTransfer();
        $shipmentGroupCollectionTransfer->setGroups($shipmentGroups);

        return $shipmentGroupCollectionTransfer;
    }
}
