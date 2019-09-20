<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;

interface ShipmentMethodPricePluginInterface
{
    /**
     * Specification:
     *  - Returns shipment method price for shipment group.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    public function getPrice(ShipmentGroupTransfer $shipmentGroupTransfer, QuoteTransfer $quoteTransfer): int;
}
