<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\DiscountCheckoutConnector\Dependency\Facade\DiscountCheckoutConnectorToDiscountBridge;
use Spryker\Zed\Discount\Business\DiscountFacade;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;
use Spryker\Zed\DiscountCheckoutConnector\Business\Model\DiscountSaver;

/**
 * @group Spryker
 * @group Zed
 * @group DiscountCheckoutConnector
 * @group Business
 * @group DiscountSaver
 */
class DiscountSaverTest extends \PHPUnit_Framework_TestCase
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
        $discountSaver = $this->getDiscountSaverMock(['persistSalesDiscount']);
        $discountSaver->expects($this->once())
            ->method('persistSalesDiscount');

        $orderTransfer = new OrderTransfer();

        $discountTransfer = new CalculatedDiscountTransfer();
        $discountTransfer->setDisplayName(self::DISCOUNT_DISPLAY_NAME);
        $discountTransfer->setAmount(self::DISCOUNT_AMOUNT);
        $discountTransfer->setAction(self::DISCOUNT_ACTION);

        $orderItemTransfer = new ItemTransfer();
        $orderItemTransfer->addCalculatedDiscount($discountTransfer);

        $orderTransfer->addItem($orderItemTransfer);

        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        $discountSaver->saveDiscounts($orderTransfer, $checkoutResponseTransfer);
    }

    /**
     * @return void
     */
    public function testSaveDiscountMustNotSaveSalesDiscountCodeIfUsedCodesCanNotBeFound()
    {
        $discountSaver = $this->getDiscountSaverMock(['persistSalesDiscount', 'saveUsedCodes']);
        $discountSaver->expects($this->once())
            ->method('persistSalesDiscount');
        $discountSaver->expects($this->never())
            ->method('saveUsedCodes');

        $orderTransfer = new OrderTransfer();

        $discountTransfer = new DiscountTransfer();

        $orderItemTransfer = new ItemTransfer();
        $orderItemTransfer->addDiscount($discountTransfer);

        $orderTransfer->addItem($orderItemTransfer);

        $orderTransfer->setIdSalesOrder(self::ID_SALES_ORDER);
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        $discountSaver->saveDiscounts($orderTransfer, $checkoutResponseTransfer);
    }

    /**
     * @return void
     */
    public function testSaveDiscountMustSaveSalesDiscountCodesIfUsedCodesPresent()
    {
        $discountSaver = $this->getDiscountSaverMock(['persistSalesDiscount', 'persistSalesDiscountCode', 'getDiscountVoucherEntityByCode']);
        $discountSaver->expects($this->once())
            ->method('persistSalesDiscount');
        $discountSaver->expects($this->exactly(2))
            ->method('persistSalesDiscountCode');
        $discountSaver->expects($this->exactly(2))
            ->method('getDiscountVoucherEntityByCode')
            ->will($this->returnCallback([$this, 'getDiscountVoucherEntityByCode']));

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setUsedCodes([self::USED_CODE_1, self::USED_CODE_2]);

        $orderTransfer = new OrderTransfer();

        $orderItemTransfer = new ItemTransfer();
        $orderItemTransfer->addDiscount($discountTransfer);
        $orderTransfer->addItem($orderItemTransfer);

        $orderTransfer->setIdSalesOrder(self::ID_SALES_ORDER);
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        $discountSaver->saveDiscounts($orderTransfer, $checkoutResponseTransfer);
    }

    /**
     * @return void
     */
    public function testSaveDiscountMustNotSaveSalesDiscountCodesIfUsedCodeCanNotBeFound()
    {
        $discountSaver = $this->getDiscountSaverMock(['persistSalesDiscount', 'persistSalesDiscountCode', 'getDiscountVoucherEntityByCode']);
        $discountSaver->expects($this->once())
            ->method('persistSalesDiscount');
        $discountSaver->expects($this->never())
            ->method('persistSalesDiscountCode');
        $discountSaver->expects($this->once())
            ->method('getDiscountVoucherEntityByCode')
            ->will($this->returnValue(false));

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setUsedCodes([self::USED_CODE_1]);

        $orderTransfer = new OrderTransfer();

        $orderItemTransfer = new ItemTransfer();
        $orderItemTransfer->addDiscount($discountTransfer);
        $orderTransfer->addItem($orderItemTransfer);

        $orderTransfer->setIdSalesOrder(self::ID_SALES_ORDER);
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        $discountSaver->saveDiscounts($orderTransfer, $checkoutResponseTransfer);
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
        $discountQueryContainerMock = $this->getMock('Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface', $methods);

        return $discountQueryContainerMock;
    }

    /**
     * @param array $discountSaverMethods
     * @param array $queryContainerMethods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\DiscountCheckoutConnector\Business\Model\DiscountSaver
     */
    private function getDiscountSaverMock(array $discountSaverMethods = [], array $queryContainerMethods = [])
    {
        $discountSaverMock = $this->getMock(
            'Spryker\Zed\DiscountCheckoutConnector\Business\Model\DiscountSaver',
            $discountSaverMethods,
            [$this->getDiscountQueryContainerMock($queryContainerMethods), new DiscountCheckoutConnectorToDiscountBridge(new DiscountFacade())]
        );

        return $discountSaverMock;
    }

}
