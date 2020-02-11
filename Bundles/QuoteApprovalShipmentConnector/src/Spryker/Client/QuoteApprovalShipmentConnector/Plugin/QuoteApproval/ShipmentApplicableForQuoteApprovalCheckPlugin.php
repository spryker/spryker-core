<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApprovalShipmentConnector\Plugin\QuoteApproval;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\QuoteApprovalExtension\Dependency\Plugin\QuoteApplicableForApprovalCheckPluginInterface;

/**
 * @method \Spryker\Client\QuoteApprovalShipmentConnector\QuoteApprovalShipmentConnectorClientInterface getClient()
 */
class ShipmentApplicableForQuoteApprovalCheckPlugin extends AbstractPlugin implements QuoteApplicableForApprovalCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if quote has all parameters required for shipment calculation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function check(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getClient()
            ->isQuoteShipmentApplicableForApprovalProcess($quoteTransfer);
    }
}
