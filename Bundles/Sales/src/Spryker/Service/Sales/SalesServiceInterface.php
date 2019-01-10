<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Sales\Shipment;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface SalesServiceInterface
{
    /**
     * Specification:
     * - Checks quote item has own shipment transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkSplitDeliveryEnabledByQuote(QuoteTransfer $quoteTransfer): bool;

    /**
     * Specification:
     * - Checks sales order item has own shipment transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function checkSplitDeliveryEnabledByOrder(OrderTransfer $orderTransfer): bool;
}
