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
use Generated\Shared\Transfer\SalesPaymentTransfer;
use Orm\Zed\GiftCard\Persistence\SpyPaymentGiftCard;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCard;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCardQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainer;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

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
 * @SuppressWarnings(\SprykerTest\Zed\GiftCard\PHPMD)
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
     * @return int
     */
    public function createSalesPaymentEntity(): int
    {
        $this->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
        $salesOrderTransfer = $this->haveOrder([], BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $salesPaymentTransfer = (new SalesPaymentTransfer())
            ->setPaymentProvider('Test provider')
            ->setPaymentMethod('Test method')
            ->setAmount(100)
            ->setFkSalesOrder($salesOrderTransfer->getIdSalesOrder());
        $salesPaymentEntity = $this->haveSalesPaymentEntity($salesPaymentTransfer);

        return $salesPaymentEntity->getIdSalesPayment();
    }

    /**
     * @param int $idSalesPayment
     *
     * @return void
     */
    public function createPaymentGiftCardEntity(int $idSalesPayment): void
    {
        $paymentGiftCardEntity = new SpyPaymentGiftCard();
        $paymentGiftCardEntity->setFkSalesPayment($idSalesPayment);
        $paymentGiftCardEntity->setCode(static::GIFT_CARD_CODE);

        $paymentGiftCardEntity->save();
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCard
     */
    public function createSalesOrderItemGiftCard(int $idSalesOrderItem): SpySalesOrderItemGiftCard
    {
        $salesOrderItemGiftCardEntity = (new SpySalesOrderItemGiftCard())
            ->setFkSalesOrderItem($idSalesOrderItem)
            ->setCode(static::GIFT_CARD_CODE);
        $salesOrderItemGiftCardEntity->save();

        return $salesOrderItemGiftCardEntity;
    }

    /**
     * @return void
     */
    public function ensureSalesOrderItemGiftCardTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getSalesOrderItemGiftCardQuery());
    }

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCard>
     */
    public function getSalesOrderItemGiftCardEntities(): ObjectCollection
    {
        return $this->getSalesOrderItemGiftCardQuery()->find();
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

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCardQuery
     */
    protected function getSalesOrderItemGiftCardQuery(): SpySalesOrderItemGiftCardQuery
    {
        return SpySalesOrderItemGiftCardQuery::create();
    }
}
