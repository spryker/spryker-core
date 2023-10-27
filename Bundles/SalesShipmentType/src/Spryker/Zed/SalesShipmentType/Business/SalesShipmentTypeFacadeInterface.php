<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

interface SalesShipmentTypeFacadeInterface
{
    /**
     * Specification:
     * - Expects `SaveOrderTransfer.items.shipment.idSalesShipment` transfer property to be set.
     * - Expects `SaveOrderTransfer.items.shipmentType.key` transfer property to be set.
     * - Expects `SaveOrderTransfer.items.shipmentType.name` transfer property to be set.
     * - Creates sales shipment type entity if it does not exist.
     * - Updates sales shipment entity with `fkSalesShipmentType`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function saveSalesShipmentsWithSalesShipmentType(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): SaveOrderTransfer;
}
