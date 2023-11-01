<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipmentsRestApi\Communication\Plugin\ShipmentsRestApi;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShipmentsRestApiExtension\Dependency\Plugin\QuoteItemExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantShipmentsRestApi\Business\MerchantShipmentsRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantShipmentsRestApi\MerchantShipmentsRestApiConfig getConfig()
 */
class MerchantReferenceQuoteItemExpanderPlugin extends AbstractPlugin implements QuoteItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `QuoteTransfer.items.shipment` to be set.
     * - Expands `QuoteTransfer.items.shipment` with `QuoteTransfer.items.merchantReference`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->expandQuoteShipmentWithMerchantReference($quoteTransfer);
    }
}
