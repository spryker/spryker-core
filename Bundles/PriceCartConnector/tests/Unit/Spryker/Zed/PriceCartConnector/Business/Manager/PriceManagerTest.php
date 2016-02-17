<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\PriceCartConnector\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\PriceCartConnector\Business\Manager\PriceManager;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceBridge;
use Unit\Spryker\Zed\PriceCartConnector\Business\Fixture\PriceFacadeStub;

/**
 * @group Spryker
 * @group Zed
 * @group PriceCartConnector
 * @group Business
 * @group PriceManager
 */
class PriceManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testAddPriceToItems()
    {
        $priceFacadeStub = $this->createPriceFacadeStub();
        $priceFacadeStub->addPriceStub('123', 1000);
        $priceFacadeStub->addValidityStub('123', true);

        $itemCollection = new CartChangeTransfer();
        $item = new ItemTransfer();
        $item->setSku(123);
        $item->setId(123);
        $itemCollection->addItem($item);

        $priceCartToPriceBridge = new PriceCartToPriceBridge($priceFacadeStub);
        $priceManager = new PriceManager($priceCartToPriceBridge, 'grossPrice');

        $modifiedItems = $priceManager->addGrossPriceToItems($itemCollection);

        foreach ($modifiedItems as $modifiedItem) {
            $this->assertEquals(1000, $modifiedItem->getGrossPrice());
        }
    }

    /**
     * @expectedException \Spryker\Zed\PriceCartConnector\Business\Exception\PriceMissingException
     * @expectedExceptionMessage Cart item 123 can not be priced
     *
     * @return void
     */
    public function testIsNotPriceAbleWithInvalidPrice()
    {
        $priceFacadeStub = $this->createPriceFacadeStub();
        $priceFacadeStub->addPriceStub('123', 1000);
        $priceFacadeStub->addValidityStub('123', false);

        $itemCollection = new CartChangeTransfer();
        $item = new ItemTransfer();
        $item->setId(123);
        $item->setSku(123);
        $itemCollection->addItem($item);

        $priceCartToPriceBridge = new PriceCartToPriceBridge($priceFacadeStub);
        $priceManager = new PriceManager($priceCartToPriceBridge, 'grossPrice');
        $priceManager->addGrossPriceToItems($itemCollection);
    }

    /**
     * @return \Unit\Spryker\Zed\PriceCartConnector\Business\Fixture\PriceFacadeStub|\Spryker\Zed\Price\Business\PriceFacade
     */
    private function createPriceFacadeStub()
    {
        return new PriceFacadeStub();
    }

}
