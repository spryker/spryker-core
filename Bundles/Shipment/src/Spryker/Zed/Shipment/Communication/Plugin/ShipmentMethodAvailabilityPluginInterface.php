<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Interface ShipmentMethodAvailabilityPluginInterface
 * @package Spryker\Zed\Shipment\Communication\Plugin
 *
 * @deprecated Use \Spryker\Zed\ShipmentExtension\Communication\Plugin\ShipmentMethodAvailabilityPluginInterface instead
 */
interface ShipmentMethodAvailabilityPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isAvailable(QuoteTransfer $quoteTransfer);
}
