<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCartConnector\Communication\Plugin\Cart;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\PreReloadItemsPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductCartConnector\ProductCartConnectorConfig getConfig()
 * @method \Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\ProductCartConnector\Communication\ProductCartConnectorCommunicationFactory getFactory()
 */
class OrderAmendmentRemoveInactiveItemsPreReloadPlugin extends AbstractPlugin implements PreReloadItemsPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `QuoteTransfer.originalSalesOrderItems.sku` to be set.
     * - Removes inactive items from quote.
     * - Adds note to messages about removed items.
     * - Skips items that are part of the original order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preReloadItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $itemSkusToSkipFiltering = $this->getBusinessFactory()
            ->createQuoteOriginalSalesOrderItemExtractor()
            ->extractOriginalSalesOrderItemSkus($quoteTransfer);

        return $this->getBusinessFactory()
            ->createInactiveItemsFilter()
            ->filterInactiveItems($quoteTransfer, $itemSkusToSkipFiltering);
    }
}
