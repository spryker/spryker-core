<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\PriceCartConnector\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use SprykerFeature\Zed\Price\Business\PriceFacade;
use SprykerFeature\Zed\PriceCartConnector\Business\Manager\PriceManager;
use Unit\SprykerFeature\Zed\PriceCartConnector\Business\Fixture\PriceFacadeStub;

/**
 * @group SprykerFeature
 * @group Zed
 * @group PriceCartConnector
 * @group Business
 * @group PriceManager
 */
class PriceManagerTest extends \PHPUnit_Framework_TestCase
{

    public function testAddPriceToItems()
    {
        $priceFacadeStub = $this->createPriceFacadeStub();
        $priceFacadeStub->addPriceStub('123', 1000);
        $priceFacadeStub->addValidityStub('123', true);

        $itemCollection = new ChangeTransfer();
        $item = new ItemTransfer();
        $item->setSku(123);
        $item->setId(123);
        $itemCollection->addItem($item);

        $priceManager = new PriceManager($priceFacadeStub, 'grossPrice');

        $modifiedItems = $priceManager->addGrossPriceToItems($itemCollection);

        foreach ($modifiedItems as $modifiedItem) {
            $this->assertEquals(1000, $modifiedItem->getGrossPrice());
        }
    }

    /**
     * @expectedException \SprykerFeature\Zed\PriceCartConnector\Business\Exception\PriceMissingException
     * @expectedExceptionMessage Cart item 123 can not be priced
     */
    public function testIsNotPriceAbleWithInvalidPrice()
    {
        $priceFacadeStub = $this->createPriceFacadeStub();
        $priceFacadeStub->addPriceStub('123', 1000);
        $priceFacadeStub->addValidityStub('123', false);

        $itemCollection = new ChangeTransfer();
        $item = new ItemTransfer();
        $item->setId(123);
        $item->setSku(123);
        $itemCollection->addItem($item);

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
