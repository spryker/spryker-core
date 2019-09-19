<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;
use Spryker\Zed\Discount\Business\Checkout\DiscountOrderSaver;
use Spryker\Zed\Discount\Business\Voucher\VoucherCode;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group Persistence
 * @group DiscountOrderSaverTest
 * Add your own group annotations below this line
 */
class DiscountOrderSaverTest extends Unit
{
    public const DISCOUNT_DISPLAY_NAME = 'discount';
    public const DISCOUNT_AMOUNT = 100;
    public const DISCOUNT_ACTION = 'action';

    public const ID_SALES_ORDER = 1;
    public const USED_CODE_1 = 'used code 1';
    public const USED_CODE_2 = 'used code 2';

    /**
     * @return void
     */
    public function testSaveDiscountMustSaveSalesItemsDiscount()
    {
        $discountSaver = $this->getDiscountOrderSaverMock(['persistSalesDiscount']);
        $discountSaver->expects($this->once())
            ->method('persistSalesDiscount');

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setStore($this->getCurrentStore());

        $discountTransfer = new CalculatedDiscountTransfer();
        $discountTransfer->setDisplayName(self::DISCOUNT_DISPLAY_NAME);
        $discountTransfer->setSumAmount(self::DISCOUNT_AMOUNT);

        $orderItemTransfer = new ItemTransfer();
        $orderItemTransfer->addCalculatedDiscount($discountTransfer);

        $quoteTransfer->addItem($orderItemTransfer);

        $saverOrderTransfer = new SaveOrderTransfer();
        $saverOrderTransfer->setIdSalesOrder(self::ID_SALES_ORDER);
        $saverOrderTransfer->setOrderItems($quoteTransfer->getItems());

        $discountSaver->saveOrderDiscounts($quoteTransfer, $saverOrderTransfer);
    }

    /**
     * @return void
     */
    public function testSaveDiscountMustNotSaveSalesDiscountCodeIfUsedCodesCanNotBeFound()
    {
        $discountSaver = $this->getDiscountOrderSaverMock(['persistSalesDiscount', 'saveUsedCodes']);
        $discountSaver->expects($this->once())
            ->method('persistSalesDiscount');
        $discountSaver->expects($this->never())
            ->method('saveUsedCodes');

        $quoteTransfer = new QuoteTransfer();

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setSumAmount(self::DISCOUNT_AMOUNT);

        $orderItemTransfer = new ItemTransfer();
        $orderItemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $quoteTransfer->addItem($orderItemTransfer);

        $saverOrderTransfer = new SaveOrderTransfer();
        $saverOrderTransfer->setIdSalesOrder(self::ID_SALES_ORDER);
        $saverOrderTransfer->setOrderItems($quoteTransfer->getItems());

        $discountSaver->saveOrderDiscounts($quoteTransfer, $saverOrderTransfer);
    }

    /**
     * @return void
     */
    public function testSaveDiscountMustSaveSalesDiscountCodesIfUsedCodesPresent()
    {
        $discountSaver = $this->getDiscountOrderSaverMock(['persistSalesDiscount', 'persistSalesDiscountCode', 'getDiscountVoucherEntityByCode']);
        $discountSaver->expects($this->once())
            ->method('persistSalesDiscount');
        $discountSaver->expects($this->exactly(1))
            ->method('persistSalesDiscountCode');
        $discountSaver->expects($this->exactly(1))
            ->method('getDiscountVoucherEntityByCode')
            ->will($this->returnCallback([$this, 'getDiscountVoucherEntityByCode']));

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setVoucherCode(self::USED_CODE_1);
        $calculatedDiscountTransfer->setSumAmount(self::DISCOUNT_AMOUNT);

        $quoteTransfer = new QuoteTransfer();

        $orderItemTransfer = new ItemTransfer();
        $orderItemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);
        $quoteTransfer->addItem($orderItemTransfer);

        $saverOrderTransfer = new SaveOrderTransfer();
        $saverOrderTransfer->setIdSalesOrder(self::ID_SALES_ORDER);
        $saverOrderTransfer->setOrderItems($quoteTransfer->getItems());

        $discountSaver->saveOrderDiscounts($quoteTransfer, $saverOrderTransfer);
    }

    /**
     * @return void
     */
    public function testSaveDiscountMustNotSaveSalesDiscountCodesIfUsedCodeCanNotBeFound()
    {
        $discountSaver = $this->getDiscountOrderSaverMock(['persistSalesDiscount', 'persistSalesDiscountCode', 'getDiscountVoucherEntityByCode']);
        $discountSaver->expects($this->once())
            ->method('persistSalesDiscount');
        $discountSaver->expects($this->never())
            ->method('persistSalesDiscountCode');
        $discountSaver->expects($this->once())
            ->method('getDiscountVoucherEntityByCode')
            ->will($this->returnValue(false));

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setVoucherCode(self::USED_CODE_1);
        $calculatedDiscountTransfer->setSumAmount(self::DISCOUNT_AMOUNT);

        $quoteTransfer = new QuoteTransfer();

        $orderItemTransfer = new ItemTransfer();
        $orderItemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);
        $quoteTransfer->addItem($orderItemTransfer);

        $saverOrderTransfer = new SaveOrderTransfer();
        $saverOrderTransfer->setIdSalesOrder(self::ID_SALES_ORDER);
        $saverOrderTransfer->setOrderItems($quoteTransfer->getItems());

        $discountSaver->saveOrderDiscounts($quoteTransfer, $saverOrderTransfer);
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function getDiscountVoucherEntityByCode()
    {
        $discountVoucherEntity = new SpyDiscountVoucher();
        $discountVoucherPoolEntity = new SpyDiscountVoucherPool();
        $discountVoucherEntity->setVoucherPool($discountVoucherPoolEntity);

        return $discountVoucherEntity;
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    private function getDiscountQueryContainerMock(array $methods = [])
    {
        $discountQueryContainerMock = $this->getMockBuilder('Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface')->setMethods($methods)->getMock();

        return $discountQueryContainerMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Discount\Business\Voucher\VoucherCode
     */
    private function getVoucherCodeMock()
    {
        $discountQueryContainerMock = $this->getMockBuilder(VoucherCode::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $discountQueryContainerMock;
    }

    /**
     * @param array $discountSaverMethods
     * @param array $queryContainerMethods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Discount\Business\Checkout\DiscountOrderSaver
     */
    private function getDiscountOrderSaverMock(array $discountSaverMethods = [], array $queryContainerMethods = [])
    {
        $discountSaverMock = $this->getMockBuilder(DiscountOrderSaver::class)->setMethods($discountSaverMethods)
            ->setConstructorArgs([$this->getDiscountQueryContainerMock($queryContainerMethods), $this->getVoucherCodeMock()])
            ->getMock();

        return $discountSaverMock;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getCurrentStore()
    {
        return (new StoreTransfer())
            ->setIdStore(1)
            ->setName('DE');
    }
}
