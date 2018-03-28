<?php

namespace SprykerTest\Zed\Offer\Business;

use Codeception\Test\Unit;
use Spryker\Shared\Offer\OfferConfig;

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
        $this->assertNotEquals(OfferConfig::ORDER_TYPE_OFFER, $response->getOrder()->getType());
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function createOffer()
    {
        /** @var \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity */
        $orderEntity = $this->tester->create();
        $orderEntity->setType(OfferConfig::ORDER_TYPE_OFFER);
        $orderEntity->save();

        return $orderEntity;
    }
}
