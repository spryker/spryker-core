<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCartConnector\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ShipmentCartConnector\Business\ShipmentCartConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ShipmentCartConnector\ShipmentCartConnectorConfig getConfig()
 */
class SanitizeCartShipmentItemExpanderPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Clears shipment method from quote and items in case of item add or remove action.
     * - Clears shipment expenses in case of item add or remove action.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFacade()->clearShipmentMethod($cartChangeTransfer);
    }
}
