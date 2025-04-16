<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSalesOrderAmendment\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentConfig getConfig()
 * @method \Spryker\Zed\PriceProductSalesOrderAmendment\Business\PriceProductSalesOrderAmendmentBusinessFactory getBusinessFactory()()
 */
class OriginalSalesOrderItemPriceItemExpanderPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Should be executed after {@link \Spryker\Zed\PriceCartConnector\Communication\Plugin\Cart\PriceItemExpanderPlugin} plugin.
     * - Requires `CartChangeTransfer.quote` to be set.
     * - Requires `CartChangeTransfer.quote.priceMode` to be set.
     * - Requires `CartChangeTransfer.items.sku` to be set.
     * - Requires `CartChangeTransfer.items.priceProduct` to be set when original price going to be applied.
     * - Requires `CartChangeTransfer.items.priceProduct.moneyValue` to be set when original price going to be applied.
     * - Builds a group key for each item in `CartChangeTransfer.items`.
     * - Tries to find original price in `CartChangeTransfer.quote.originalSalesOrderItemUnitPrices` by the built group key.
     * - Does nothing if original sales order item unit price is not found.
     * - For found prices uses {@link \Spryker\Service\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentConfig::useBestPriceBetweenOriginalAndSalesOrderItemPrice()} to determine if best price should be used.
     * - If the config method returns `true` replaces price in case the original price is lower then the current price.
     * - Configuration applies for all items. It is not possible to set it for each item separately.
     * - Otherwise replaces the default price anyway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getBusinessFactory()
            ->createCartChangeReplacer()
            ->replaceOriginalSalesOrderItemPrices($cartChangeTransfer);
    }
}
