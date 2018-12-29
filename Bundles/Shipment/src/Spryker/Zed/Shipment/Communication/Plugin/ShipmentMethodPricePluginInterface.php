<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Interface ShipmentMethodPricePluginInterface
 * @package Spryker\Zed\Shipment\Communication\Plugin
 *
 * @deprecated Use \Spryker\Zed\ShipmentExtension\Communication\Plugin\ShipmentMethodPricePluginInterface instead
 */
interface ShipmentMethodPricePluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    public function getPrice(QuoteTransfer $quoteTransfer);
}
