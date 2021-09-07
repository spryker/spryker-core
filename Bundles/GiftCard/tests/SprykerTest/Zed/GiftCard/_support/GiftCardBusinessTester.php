<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GiftCard;

use Codeception\Actor;
use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCardQuery;
use Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class GiftCardBusinessTester extends Actor
{
    use _generated\GiftCardBusinessTesterActions;

    /**
     * @var string
     */
    public const GIFT_CARD_CODE = 'testCode1';

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransferWithoutGiftCard(): QuoteTransfer
    {
        return $this->createQuoteTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransferWithGiftCard(): QuoteTransfer
    {
        return $this->createQuoteTransfer()
            ->addGiftCard((new GiftCardTransfer())->setCode(static::GIFT_CARD_CODE));
    }

    /**
     * @param int $idSalesPayment
     * @param int $expectedCount
     *
     * @return void
     */
    public function assertPaymentGiftCardExistBySalesPaymentId(int $idSalesPayment, int $expectedCount): void
    {
        $paymentGiftCards = (new GiftCardQueryContainer())->queryPaymentGiftCards()
            ->findByFkSalesPayment($idSalesPayment);

        $this->assertCount($expectedCount, $paymentGiftCards);
    }

    /**
     * @param int $idSalesOrder
     * @param int $expectedCount
     *
     * @return void
     */
    public function assertSalesOrderItemGiftCardExistBySalesPaymentId(int $idSalesOrder, int $expectedCount): void
    {
        $salesOrderItemGiftCardEntities = (new SpySalesOrderItemGiftCardQuery())
            ->useSpySalesOrderItemQuery()
                ->filterByFkSalesOrder($idSalesOrder)
            ->endUse()
            ->find();

        $this->assertCount($expectedCount, $salesOrderItemGiftCardEntities);
    }

    /**
     * @param int $idSalesPayment
     * @param string $code
     *
     * @return void
     */
    public function assertPaymentGiftCardExistBySalesPaymentIdAndCode(int $idSalesPayment, string $code): void
    {
        $paymentGiftCards = (new GiftCardQueryContainer())->queryPaymentGiftCards()
            ->filterByCode($code)
            ->findByFkSalesPayment($idSalesPayment);

        $this->assertCount(1, $paymentGiftCards);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(): QuoteTransfer
    {
        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(3);

        return $quoteTransfer->addItem($itemTransfer);
    }
}
