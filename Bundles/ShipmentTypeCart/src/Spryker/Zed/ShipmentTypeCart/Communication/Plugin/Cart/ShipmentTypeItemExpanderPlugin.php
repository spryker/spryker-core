<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeCart\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ShipmentTypeCart\ShipmentTypeCartConfig getConfig()
 * @method \Spryker\Zed\ShipmentTypeCart\Business\ShipmentTypeCartFacadeInterface getFacade()
 */
class ShipmentTypeItemExpanderPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CartChange.items.shipment` transfer property to be set.
     * - Expects `CartChange.items.shipmentType.uuid` transfer property to be set.
     * - Does nothing if `CartChange.items.shipmentType` transfer property is not provided.
     * - Sets `CartChange.items.shipment.shipmentTypeUuid` taken from `CartChange.items.shipmentType.uuid`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFacade()->expandCartChangeItemsWithShipmentType($cartChangeTransfer);
    }
}
