<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCartConnector\Business;

use Generated\Shared\Transfer\CartChangeTransfer;

/**
 * @method \Spryker\Zed\ShipmentCartConnector\Business\ShipmentCartConnectorBusinessFactory getFactory()
 */
interface ShipmentCartConnectorFacadeInterface
{
    /**
     * Specification:
     *  - Changes shipment method and shipment expenses based on current currency for quote level (BC)
     * or item level shipments.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function updateShipmentPrice(CartChangeTransfer $cartChangeTransfer);

    /**
     * Specification:
     *  - Validates if current shipment method is still valid in cart for quote level (BC) or item level shipments.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateShipment(CartChangeTransfer $cartChangeTransfer);
}
