<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApprovalShipmentConnector;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @method \Spryker\Client\QuoteApprovalShipmentConnector\QuoteApprovalShipmentConnectorFactory getFactory()
 */
interface QuoteApprovalShipmentConnectorClientInterface
{
    /**
     * Specification:
     * - Returns true if shipment in quote was set.
     * - For BC-reason: Checks if QuoteTransfer::shipment and QuoteTransfer::shipmentAddress were provided for single shipment case.
     * - Checks that all items has shipments and corresponding expenses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteShipmentApplicableForApprovalProcess(QuoteTransfer $quoteTransfer): bool;
}
