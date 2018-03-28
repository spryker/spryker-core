<?php

namespace SprykerTest\Zed\Offer\Business;

use Codeception\Test\Unit;

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
        $this->markTestIncomplete('We moved to offer entities and storing a quote');

        $facade = $this->tester->getFacade();
        $orderTransfer = $this->tester->haveOrder([], 'Nopayment01');
        $orderTransfer = $this->createSalesFacade()
            ->getOrderByIdSalesOrder(
                $orderTransfer->getIdSalesOrder()
            );

        $this->assertTrue($orderTransfer->getIsOffer());

        $response = $facade->convertOfferToOrder($orderTransfer->getIdSalesOrder());
        $this->assertTrue($response->getIsSuccessful());
        $this->assertFalse($response->getOrder()->getIsOffer());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function createSalesFacade()
    {
        return $this->tester->getLocator()->sales()->facade();
    }
}
