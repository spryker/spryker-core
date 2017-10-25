<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;
use Spryker\Zed\Discount\Business\Persistence\DiscountOrderSaver;
use Spryker\Zed\Discount\Business\Voucher\VoucherCode;

/**
 * Auto-generated group annotations
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
    const DISCOUNT_DISPLAY_NAME = 'discount';
    const DISCOUNT_AMOUNT = 100;
    const DISCOUNT_ACTION = 'action';

    const ID_SALES_ORDER = 1;
    const USED_CODE_1 = 'used code 1';
    const USED_CODE_2 = 'used code 2';

    /**
     * @return void
     */
    public function testSaveDiscountMustSaveSalesItemsDiscount()
    {
        $discountSaver = $this->getDiscountOrderSaverMock(['persistSalesDiscount']);
        $discountSaver->expects($this->once())
            ->method('persistSalesDiscount');

        $quoteTransfer = new QuoteTransfer();

        $discountTransfer = new CalculatedDiscountTransfer();
        $discountTransfer->setDisplayName(self::DISCOUNT_DISPLAY_NAME);
        $discountTransfer->setUnitGrossAmount(self::DISCOUNT_AMOUNT);

        $orderItemTransfer = new ItemTransfer();
        $orderItemTransfer->addCalculatedDiscount($discountTransfer);

        $quoteTransfer->addItem($orderItemTransfer);

        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $saverOrderTransfer = new SaveOrderTransfer();
        $saverOrderTransfer->setIdSalesOrder(self::ID_SALES_ORDER);
        $saverOrderTransfer->setOrderItems($quoteTransfer->getItems());
        $checkoutResponseTransfer->setSaveOrder($saverOrderTransfer);

        $discountSaver->saveDiscounts($quoteTransfer, $checkoutResponseTransfer);
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

        $orderItemTransfer = new ItemTransfer();
        $orderItemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $quoteTransfer->addItem($orderItemTransfer);

        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $saverOrderTransfer = new SaveOrderTransfer();
        $saverOrderTransfer->setIdSalesOrder(self::ID_SALES_ORDER);
        $saverOrderTransfer->setOrderItems($quoteTransfer->getItems());
        $checkoutResponseTransfer->setSaveOrder($saverOrderTransfer);

        $discountSaver->saveDiscounts($quoteTransfer, $checkoutResponseTransfer);
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

        $quoteTransfer = new QuoteTransfer();

        $orderItemTransfer = new ItemTransfer();
        $orderItemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);
        $quoteTransfer->addItem($orderItemTransfer);

        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $saverOrderTransfer = new SaveOrderTransfer();
        $saverOrderTransfer->setIdSalesOrder(self::ID_SALES_ORDER);
        $saverOrderTransfer->setOrderItems($quoteTransfer->getItems());
        $checkoutResponseTransfer->setSaveOrder($saverOrderTransfer);

        $discountSaver->saveDiscounts($quoteTransfer, $checkoutResponseTransfer);
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

        $quoteTransfer = new QuoteTransfer();

        $orderItemTransfer = new ItemTransfer();
        $orderItemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);
        $quoteTransfer->addItem($orderItemTransfer);

        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $saverOrderTransfer = new SaveOrderTransfer();
        $saverOrderTransfer->setIdSalesOrder(self::ID_SALES_ORDER);
        $saverOrderTransfer->setOrderItems($quoteTransfer->getItems());
        $checkoutResponseTransfer->setSaveOrder($saverOrderTransfer);

        $discountSaver->saveDiscounts($quoteTransfer, $checkoutResponseTransfer);
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    private function getDiscountQueryContainerMock(array $methods = [])
    {
        $discountQueryContainerMock = $this->getMockBuilder('Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface')->setMethods($methods)->getMock();

        return $discountQueryContainerMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\Voucher\VoucherCode
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\Persistence\DiscountOrderSaver
     */
    private function getDiscountOrderSaverMock(array $discountSaverMethods = [], array $queryContainerMethods = [])
    {
        $discountSaverMock = $this->getMockBuilder(DiscountOrderSaver::class)->setMethods($discountSaverMethods)
            ->setConstructorArgs([$this->getDiscountQueryContainerMock($queryContainerMethods), $this->getVoucherCodeMock()])
            ->getMock();

        return $discountSaverMock;
    }
}
