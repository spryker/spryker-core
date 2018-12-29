<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Interface ShipmentMethodDeliveryTimePluginInterface
 * @package Spryker\Zed\Shipment\Communication\Plugin
 *
 * @deprecated
 */
interface ShipmentMethodDeliveryTimePluginInterface
{
    /**
     * @param \Spryker\Zed\Shipment\Communication\Plugin\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    public function getTime(ShipmentGroupTransfer $shipmentGroupTransfer, QuoteTransfer $quoteTransfer): int;
}
