<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApprovalShipmentConnector\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\QuoteApprovalShipmentConnector\Business\QuoteApprovalShipmentConnectorBusinessFactory getFactory()
 */
class QuoteApprovalShipmentConnectorFacade extends AbstractFacade implements QuoteApprovalShipmentConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    public function getQuoteFieldsAllowedForSaving(QuoteTransfer $quoteTransfer): array
    {
        return $this->getFactory()
            ->createShipmentQuoteFieldProvider()
            ->getQuoteFieldsAllowedForSaving($quoteTransfer);
    }
}
