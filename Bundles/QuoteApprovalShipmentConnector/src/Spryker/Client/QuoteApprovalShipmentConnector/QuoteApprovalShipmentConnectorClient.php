<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApprovalShipmentConnector;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\QuoteApprovalShipmentConnector\QuoteApprovalShipmentConnectorFactory getFactory()
 */
class QuoteApprovalShipmentConnectorClient extends AbstractClient implements QuoteApprovalShipmentConnectorClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteShipmentApplicableForApprovalProcess(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()
            ->createQuoteShipmentChecker()
            ->checkQuoteShipment($quoteTransfer);
    }
}
