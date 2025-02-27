<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Communication\Plugin\Sales;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemsPostSavePluginInterface;

/**
 * @method \Spryker\Zed\GiftCard\GiftCardConfig getConfig()
 * @method \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface getFacade()
 * @method \Spryker\Zed\GiftCard\Communication\GiftCardCommunicationFactory getFactory()
 * @method \Spryker\Zed\GiftCard\Business\GiftCardBusinessFactory getBusinessFactory()
 */
class GiftCardOrderItemsPostSavePlugin extends AbstractPlugin implements OrderItemsPostSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `QuoteTransfer.items.giftCardMetadata` to be provided.
     * - Expects `QuoteTransfer.items.giftCardMetadata.isGiftCard` to be true.
     * - Requires `QuoteTransfer.items.idSalesOrderItem` to be set.
     * - Creates gift card information from `ItemTransfer` to `spy_sales_order_item_gift_card` table.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function execute(SaveOrderTransfer $saveOrderTransfer, QuoteTransfer $quoteTransfer): SaveOrderTransfer
    {
        $this->getBusinessFactory()->createSalesOrderItemSaver()->saveSalesOrderItemGiftCards($quoteTransfer);

        return $saveOrderTransfer;
    }
}
