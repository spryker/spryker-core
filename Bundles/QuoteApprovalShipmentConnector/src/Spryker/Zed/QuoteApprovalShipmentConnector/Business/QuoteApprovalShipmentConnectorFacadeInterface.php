<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApprovalShipmentConnector\Business;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteApprovalShipmentConnectorFacadeInterface
{
    /**
     * Specification:
     * - Provides quote shipment field list that must be stored if quote has not-declined approval.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    public function getQuoteFieldsAllowedForSaving(QuoteTransfer $quoteTransfer): array;
}
