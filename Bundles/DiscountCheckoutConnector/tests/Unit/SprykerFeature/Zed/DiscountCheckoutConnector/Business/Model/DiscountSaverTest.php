<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\DiscountCheckoutConnector\Business\Model;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEngine\Zed\Kernel\AbstractUnitTest;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucher;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool;
use SprykerFeature\Zed\DiscountCheckoutConnector\Business\Model\DiscountSaver;
use SprykerFeature\Zed\Sales\Business\Model\Split\OrderItem;

/**
 * @group SprykerFeature
 * @group Zed
 * @group DiscountCheckoutConnector
 * @group Business
 * @group DiscountSaver
 */
class DiscountSaverTest extends AbstractUnitTest
{

    const DISCOUNT_DISPLAY_NAME = 'discount';
    const DISCOUNT_AMOUNT = 100;
    const DISCOUNT_ACTION = 'action';

    const ID_SALES_ORDER = 1;
    const USED_CODE_1 = 'used code 1';
    const USED_CODE_2 = 'used code 2';

    public function testSaveDiscountMustSaveSalesDiscount()
    {
        $discountQueryContainerMock = $this->getDiscountQueryContainerMock();
        $discountSaver = $this->getDiscountSaverMock(['persistSalesDiscount']);
        $discountSaver->expects($this->once())
            ->method('persistSalesDiscount')
        ;

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setDisplayName(self::DISCOUNT_DISPLAY_NAME);
        $discountTransfer->setAmount(self::DISCOUNT_AMOUNT);
        $discountTransfer->setAction(self::DISCOUNT_ACTION);

        $orderTransfer = new OrderTransfer();
        $orderTransfer->addDiscount($discountTransfer);
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        $discountSaver->saveDiscounts($orderTransfer, $checkoutResponseTransfer);
    }

    public function testSaveDiscountMustSaveSalesItemsDiscount()
    {
        $discountQueryContainerMock = $this->getDiscountQueryContainerMock();
        $discountSaver = $this->getDiscountSaverMock(['persistSalesDiscount']);
        $discountSaver->expects($this->once())
            ->method('persistSalesDiscount')
        ;

        $orderTransfer = new OrderTransfer();

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setDisplayName(self::DISCOUNT_DISPLAY_NAME);
        $discountTransfer->setAmount(self::DISCOUNT_AMOUNT);
        $discountTransfer->setAction(self::DISCOUNT_ACTION);

        $orderItemTransfer = new ItemTransfer();
        $orderItemTransfer->addDiscount($discountTransfer);

        $orderTransfer->addItem($orderItemTransfer);

        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        $discountSaver->saveDiscounts($orderTransfer, $checkoutResponseTransfer);
    }

    public function testSaveDiscountMustNotSaveSalesDiscountCodeIfUsedCodesCanNotBeFound()
    {
        $discountQueryContainerMock = $this->getDiscountQueryContainerMock();
        $discountSaver = $this->getDiscountSaverMock(['persistSalesDiscount', 'saveUsedCodes']);
        $discountSaver->expects($this->once())
            ->method('persistSalesDiscount')
        ;
        $discountSaver->expects($this->never())
            ->method('saveUsedCodes')
        ;

        $orderTransfer = new OrderTransfer();
        $orderTransfer->setIdSalesOrder(self::ID_SALES_ORDER);
        $orderTransfer->addDiscount(new DiscountTransfer());
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        $discountSaver->saveDiscounts($orderTransfer, $checkoutResponseTransfer);
    }

    public function testSaveDiscountMustSaveSalesDiscountCodesIfUsedCodesPresent()
    {
        $discountQueryContainerMock = $this->getDiscountQueryContainerMock();
        $discountSaver = $this->getDiscountSaverMock(['persistSalesDiscount', 'persistSalesDiscountCode', 'getDiscountVoucherEntityByCode']);
        $discountSaver->expects($this->once())
            ->method('persistSalesDiscount')
        ;
        $discountSaver->expects($this->exactly(2))
            ->method('persistSalesDiscountCode')
        ;
        $discountSaver->expects($this->exactly(2))
            ->method('getDiscountVoucherEntityByCode')
            ->will($this->returnCallback([$this, 'getDiscountVoucherEntityByCode']))
        ;

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setUsedCodes([self::USED_CODE_1, self::USED_CODE_2]);

        $orderTransfer = new OrderTransfer();
        $orderTransfer->setIdSalesOrder(self::ID_SALES_ORDER);
        $orderTransfer->addDiscount($discountTransfer);
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        $discountSaver->saveDiscounts($orderTransfer, $checkoutResponseTransfer);
    }

    public function testSaveDiscountMustNotSaveSalesDiscountCodesIfUsedCodeCanNotBeFound()
    {
        $discountQueryContainerMock = $this->getDiscountQueryContainerMock();
        $discountSaver = $this->getDiscountSaverMock(['persistSalesDiscount', 'persistSalesDiscountCode', 'getDiscountVoucherEntityByCode']);
        $discountSaver->expects($this->once())
            ->method('persistSalesDiscount')
        ;
        $discountSaver->expects($this->never())
            ->method('persistSalesDiscountCode')
        ;
        $discountSaver->expects($this->once())
            ->method('getDiscountVoucherEntityByCode')
            ->will($this->returnValue(false))
        ;

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setUsedCodes([self::USED_CODE_1]);

        $orderTransfer = new OrderTransfer();
        $orderTransfer->setIdSalesOrder(self::ID_SALES_ORDER);
        $orderTransfer->addDiscount($discountTransfer);
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        $discountSaver->saveDiscounts($orderTransfer, $checkoutResponseTransfer);
    }

    /**
     * @return SpyDiscountVoucher
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
     * @return \PHPUnit_Framework_MockObject_MockObject|DiscountQueryContainerInterface
     */
    private function getDiscountQueryContainerMock(array $methods = [])
    {
        $discountQueryContainerMock = $this->getMock('SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainerInterface', $methods);

        return $discountQueryContainerMock;
    }

    /**
     * @param array $discountSaverMethods
     * @param array $queryContainerMethods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|DiscountSaver
     */
    private function getDiscountSaverMock(array $discountSaverMethods = [], array $queryContainerMethods = [])
    {
        $discountSaverMock = $this->getMock(
            'SprykerFeature\Zed\DiscountCheckoutConnector\Business\Model\DiscountSaver',
            $discountSaverMethods,
            [$this->getDiscountQueryContainerMock($queryContainerMethods), $this->getFacade(null, 'Discount')]
        );

        return $discountSaverMock;
    }

}
