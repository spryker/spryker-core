<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector\Business\Messenger;

use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToMessengerFacadeInterface;

class ProductOptionMessenger implements ProductOptionMessengerInterface
{
    /**
     * @var string
     */
    protected const INFO_MESSAGE_INACTIVE_PRODUCT_OPTION_ITEM_REMOVED = 'cart_reorder.pre_add_to_cart.inactive_product_option_item_removed';

    /**
     * @var string
     */
    protected const MESSAGE_PARAM_SKU = '%sku%';

    /**
     * @param \Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        protected ProductOptionCartConnectorToMessengerFacadeInterface $messengerFacade
    ) {
    }

    /**
     * @param string $sku
     * @param array<string, \Generated\Shared\Transfer\MessageTransfer> $messageTransfersIndexedBySku
     *
     * @return array<string, \Generated\Shared\Transfer\MessageTransfer>
     */
    public function addInfoMessageInactiveProductOptionItemRemoved(
        string $sku,
        array $messageTransfersIndexedBySku
    ): array {
        if (isset($messageTransfersIndexedBySku[$sku])) {
            return $messageTransfersIndexedBySku;
        }

        $messageTransfersIndexedBySku[$sku] = (new MessageTransfer())
            ->setValue(static::INFO_MESSAGE_INACTIVE_PRODUCT_OPTION_ITEM_REMOVED)
            ->setParameters([
                static::MESSAGE_PARAM_SKU => $sku,
            ]);

        $this->messengerFacade->addInfoMessage($messageTransfersIndexedBySku[$sku]);

        return $messageTransfersIndexedBySku;
    }
}
