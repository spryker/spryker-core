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
        $offerEntity = $this->createOffer();
        $facade = $this->tester->getFacade();

        $response = $facade->convertOfferToOrder($offerEntity->getIdSalesOrder());
        $this->assertTrue($response->getIsSuccessful());
        $this->assertFalse($response->getOrder()->getIsOffer());
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function createOffer()
    {
        $orderEntity = $this->tester->create();
        $orderEntity->setIsOffer(true);
        $orderEntity->save();

        return $orderEntity;
    }
}
