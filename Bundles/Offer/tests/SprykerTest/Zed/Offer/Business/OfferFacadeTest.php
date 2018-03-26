<?php

namespace SprykerTest\Zed\Offer\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Offer
 * @group Business
 * @group Facade
 * @group OfferFacadeTest
 * Add your own group annotations below this line
 */
class OfferFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Offer\OfferBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testConvertOfferToOrder()
    {
        $facade = $this->tester->getFacade();
        $orderEntity = $this->tester->haveOrder();

        $this->assertTrue($orderEntity->getIsOffer());

        $response = $facade->convertOfferToOrder($orderEntity->getIdSalesOrder());
        $this->assertTrue($response->getIsSuccessful());
        $this->assertFalse($response->getOrder()->getIsOffer());
    }

    /**
     * @return void
     */
    public function testGetOffers()
    {
        $facade = $this->tester->getFacade();
        $orderListTransfer = $this->haveOrderListTransfer();
        $orderListTransfer = $facade->getOffers($orderListTransfer);

        $this->assertNotEmpty($orderListTransfer->getOrders());
        foreach ($orderListTransfer->getOrders() as $order) {
            $this->assertInstanceOf(OrderTransfer::class, $order);
            $this->assertTrue($order->getIsOffer());
        }
    }

    /**
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function haveOrderListTransfer()
    {
        $saveOrderTransfer = $this->tester->haveOrder();
        $orderTransfer = $this->createSalesFacade()
            ->getOrderByIdSalesOrder(
                $saveOrderTransfer->getIdSalesOrder()
            );

        return (new OrderListTransfer())
                ->setIdCustomer($orderTransfer->getFkCustomer())
                ->setOrders(new ArrayObject([$orderTransfer]));
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function createSalesFacade()
    {
        return $this->tester->getLocator()->sales()->facade();
    }
}
