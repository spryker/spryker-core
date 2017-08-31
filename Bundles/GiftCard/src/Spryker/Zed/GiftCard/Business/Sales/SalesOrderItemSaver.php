<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Sales;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCard;
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class SalesOrderItemSaver
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingService
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardAttributePluginInterface[]
     */
    private $attributePlugins;

    /**
     * @param \Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardAttributePluginInterface[] $attributePlugins
     * @param \Spryker\Service\UtilEncoding\UtilEncodingService $utilEncodingService
     */
    public function __construct(
        array $attributePlugins,
        UtilEncodingService $utilEncodingService
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->attributePlugins = $attributePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveSalesOrderGiftCardItems(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->handleDatabaseTransaction(function () use ($quoteTransfer) {
            foreach ($quoteTransfer->getItems() as $itemTransfer) {
                if ($this->isGiftCard($itemTransfer)) {
                    $this->saveGiftCardOrderItem($itemTransfer);
                }
            }
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isGiftCard(ItemTransfer $itemTransfer)
    {
        if (!$itemTransfer->getGiftCardMetadata()) {
            return false;
        }

        return $itemTransfer->getGiftCardMetadata()->getIsGiftCard();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function saveGiftCardOrderItem(ItemTransfer $itemTransfer)
    {
        $salesOrderGiftCardItemEntity = new SpySalesOrderItemGiftCard();
        $salesOrderGiftCardItemEntity->setFkSalesOrderItem($itemTransfer->getIdSalesOrderItem());
        $attributes = $this->getAttributes($itemTransfer);

        $salesOrderGiftCardItemEntity->setAttributes($this->utilEncodingService->encodeJson($attributes));

        $salesOrderGiftCardItemEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array
     */
    protected function getAttributes(ItemTransfer $itemTransfer)
    {
        $attributes = [];

        foreach ($this->attributePlugins as $attributePlugin) {
            $attributes = array_merge($attributes, $attributePlugin->getAttributes($itemTransfer));
        }

        return $attributes;
    }

}
