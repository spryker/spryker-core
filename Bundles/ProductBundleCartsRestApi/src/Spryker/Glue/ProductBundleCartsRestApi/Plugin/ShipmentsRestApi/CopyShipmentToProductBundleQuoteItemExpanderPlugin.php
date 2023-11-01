<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundleCartsRestApi\Plugin\ShipmentsRestApi;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Zed\ShipmentsRestApiExtension\Dependency\Plugin\QuoteItemExpanderPluginInterface;

/**
 * @method \Spryker\Glue\ProductBundleCartsRestApi\ProductBundleCartsRestApiFactory getFactory()
 */
class CopyShipmentToProductBundleQuoteItemExpanderPlugin extends AbstractPlugin implements QuoteItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `QuoteTransfer.bundleItems.bundleItemIdentifier` to be set.
     * - Requires `QuoteTransfer.items.shipments` to be set for related product bundle items.
     * - Does nothing if quote does not contain product bundles.
     * - Expects `QuoteTransfer.items.relatedBundleItemIdentifier` to be set.
     * - Copies shipment from quote item to product bundle item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()->createQuoteBundleItemExpander()->expandBundleItemsWithShipment($quoteTransfer);
    }
}
