<?php

namespace Unit\SprykerFeature\Zed\PriceCartConnector\Business;

use Generated\Shared\Transfer\CartItemsTransfer;
use Generated\Shared\Transfer\CartItemTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use SprykerFeature\Zed\Price\Business\PriceFacade;
use SprykerFeature\Zed\PriceCartConnector\Business\Manager\PriceManager;
use Unit\SprykerFeature\Zed\PriceCartConnector\Business\Fixture\CartItemFixture;
use Unit\SprykerFeature\Zed\PriceCartConnector\Business\Fixture\CollectionFixture;
use Unit\SprykerFeature\Zed\PriceCartConnector\Business\Fixture\PriceFacadeStub;
use Unit\SprykerFeature\Zed\PriceCartConnector\Business\Fixture\PriceItemFixture;

class PriceManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group PriceCartConnector
     * @group Business
     * @group Zed
     * @group Manager
     */
    public function testAddPriceToItems()
    {
        $priceFacadeStub = $this->createPriceFacadeStub();
        $priceFacadeStub->addPriceStub('123', 1000);
        $priceFacadeStub->addValidityStub('123', true);

        $itemCollection = new CartItemsTransfer();
        $item = new CartItemTransfer();
        $item->setId(123);
        $itemCollection->addCartItem($item);

        $priceManager = new PriceManager($priceFacadeStub, 'grossPrice');

        $modifiedItems = $priceManager->addGrossPriceToItems($itemCollection);

        foreach ($modifiedItems as $modifiedItem) {
            $this->assertEquals(1000, $modifiedItem->getGrossPrice());
        }
    }

    /**
     * @group PriceCartConnector
     * @group Business
     * @group Zed
     * @group Manager
     *
     * @expectedException \SprykerFeature\Zed\PriceCartConnector\Business\Exception\PriceMissingException
     * @expectedExceptionMessage Cart item 123 can not be priced
     */
    public function testIsNotPriceAbleWithInvalidPrice()
    {
        $priceFacadeStub = $this->createPriceFacadeStub();
        $priceFacadeStub->addPriceStub('123', 1000);
        $priceFacadeStub->addValidityStub('123', false);

        $itemCollection = new CartItemsTransfer();
        $item = new CartItemTransfer();
        $item->setId(123);
        $itemCollection->addCartItem($item);

        $priceManager = new PriceManager($priceFacadeStub, 'grossPrice');
        $priceManager->addGrossPriceToItems($itemCollection);
    }

    /**
     * @return PriceFacadeStub|PriceFacade
     */
    private function createPriceFacadeStub()
    {
        return new PriceFacadeStub(null, null);
    }
}
