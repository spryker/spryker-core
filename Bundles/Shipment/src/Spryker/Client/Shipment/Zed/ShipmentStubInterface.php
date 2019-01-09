<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Shipment\Zed;

use \ArrayObject;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupCollectionTransfer;

interface ShipmentStubInterface
{
    /**
     * @api
     *
     * @deprecated Use getAvailableMethodsByShipment() instead
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer);

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemCollectionTransfer $itemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer
     */
    public function getShipmentGroupCollectionTransfer(ItemCollectionTransfer $itemCollectionTransfer): ShipmentGroupCollectionTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer
     */
    public function getAvailableMethodsByShipment(QuoteTransfer $quoteTransfer): ShipmentGroupCollectionTransfer;
}
