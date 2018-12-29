<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentExtension\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

interface ShipmentMethodAvailabilityPluginInterface
{
    /**
     * @param \Spryker\Zed\ShipmentExtension\Communication\Plugin\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isAvailable(ShipmentGroupTransfer $shipmentGroupTransfer, QuoteTransfer $quoteTransfer): bool;
}
