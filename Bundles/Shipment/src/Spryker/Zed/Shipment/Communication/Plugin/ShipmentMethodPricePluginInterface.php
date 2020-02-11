<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @deprecated Use \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodPricePluginInterface instead.
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
