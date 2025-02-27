<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Writer;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\GiftCard\Business\Payment\SalesOrderPaymentSaverInterface;
use Spryker\Zed\GiftCard\Business\Sales\SalesOrderItemSaverInterface;

/**
 * @deprecated Will be removed without replacement.
 */
class OrderGiftCardsRelationshipWriter implements OrderGiftCardsRelationshipWriterInterface
{
    /**
     * @var \Spryker\Zed\GiftCard\Business\Sales\SalesOrderItemSaverInterface
     */
    protected $salesOrderItemSaver;

    /**
     * @var \Spryker\Zed\GiftCard\Business\Payment\SalesOrderPaymentSaverInterface
     */
    protected $salesOrderPaymentSaver;

    /**
     * @param \Spryker\Zed\GiftCard\Business\Sales\SalesOrderItemSaverInterface $salesOrderItemSaver
     * @param \Spryker\Zed\GiftCard\Business\Payment\SalesOrderPaymentSaverInterface $salesOrderPaymentSaver
     */
    public function __construct(
        SalesOrderItemSaverInterface $salesOrderItemSaver,
        SalesOrderPaymentSaverInterface $salesOrderPaymentSaver
    ) {
        $this->salesOrderItemSaver = $salesOrderItemSaver;
        $this->salesOrderPaymentSaver = $salesOrderPaymentSaver;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderGiftCards(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $this->salesOrderItemSaver->saveSalesOrderItemGiftCards($quoteTransfer);
        $this->salesOrderPaymentSaver->saveGiftCardOrderPayments($quoteTransfer, $saveOrderTransfer);
    }
}
