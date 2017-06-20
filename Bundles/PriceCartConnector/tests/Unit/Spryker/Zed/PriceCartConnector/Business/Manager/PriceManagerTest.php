<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\PriceCartConnector\Business\Manager;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use PHPUnit_Framework_TestCase;
use Spryker\Shared\Price\PriceMode;
use Spryker\Zed\PriceCartConnector\Business\Manager\PriceManager;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceBridge;
use Unit\Spryker\Zed\PriceCartConnector\Business\Fixture\PriceFacadeStub;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group PriceCartConnector
 * @group Business
 * @group Manager
 * @group PriceManagerTest
 */
class PriceManagerTest extends PHPUnit_Framework_TestCase
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
        $itemCollection->setQuote(new QuoteTransfer());
        $item = new ItemTransfer();
        $item->setSku(123);
        $item->setId(123);
        $itemCollection->addItem($item);

        $priceCartToPriceBridge = new PriceCartToPriceBridge($priceFacadeStub);
        $priceManager = new PriceManager($priceCartToPriceBridge, 'grossPrice', PriceMode::PRICE_MODE_GROSS);

        $modifiedItemCollection = $priceManager->addGrossPriceToItems($itemCollection);

        $this->assertSame(1, $modifiedItemCollection->getItems()->count());

        foreach ($modifiedItemCollection->getItems() as $modifiedItem) {
            $this->assertSame(1000, $modifiedItem->getUnitGrossPrice());
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
        $itemCollection->setQuote(new QuoteTransfer());
        $item = new ItemTransfer();
        $item->setId(123);
        $item->setSku(123);
        $itemCollection->addItem($item);

        $priceCartToPriceBridge = new PriceCartToPriceBridge($priceFacadeStub);
        $priceManager = new PriceManager($priceCartToPriceBridge, 'grossPrice', PriceMode::PRICE_MODE_GROSS);
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
