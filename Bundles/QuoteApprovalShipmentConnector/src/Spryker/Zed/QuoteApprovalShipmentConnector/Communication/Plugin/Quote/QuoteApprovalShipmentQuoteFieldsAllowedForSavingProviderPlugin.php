<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApprovalShipmentConnector\Communication\Plugin\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteFieldsAllowedForSavingProviderPluginInterface;

/**
 * @method \Spryker\Zed\QuoteApprovalShipmentConnector\Business\QuoteApprovalShipmentConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\QuoteApprovalShipmentConnector\QuoteApprovalShipmentConnectorConfig getConfig()
 */
class QuoteApprovalShipmentQuoteFieldsAllowedForSavingProviderPlugin extends AbstractPlugin implements QuoteFieldsAllowedForSavingProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns required shipment quote fields from config if approval request is not canceled.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    public function execute(QuoteTransfer $quoteTransfer): array
    {
        return $this->getFacade()->getQuoteFieldsAllowedForSaving($quoteTransfer);
    }
}
