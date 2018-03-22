<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceCartConnector\Business\Manager;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PriceCartConnector\Business\Manager\PriceManager;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductBridge;
use SprykerTest\Zed\PriceCartConnector\Business\Fixture\PriceProductFacadeStub;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group PriceCartConnector
 * @group Business
 * @group Manager
 * @group PriceManagerTest
 * Add your own group annotations below this line
 */
class PriceManagerTest extends Unit
{
    /**
     * @return void
     */
    public function testAddPriceToItems()
    {
        $priceProductFacadeStub = $this->createPriceProductFacadeStub();
        $priceProductFacadeStub->addPriceStub('123', 1000);
        $priceProductFacadeStub->addValidityStub('123', true);

        $cartChangeTransfer = $this->createCartChangeTransfer();

        $priceManager = $this->createPriceManager($priceProductFacadeStub);

        $modifiedItemCollection = $priceManager->addPriceToItems($cartChangeTransfer);

        $this->assertSame(1, $modifiedItemCollection->getItems()->count());

        foreach ($modifiedItemCollection->getItems() as $modifiedItem) {
            $this->assertSame(1000, $modifiedItem->getUnitGrossPrice());
        }
    }

    /**
     * @expectedException \Spryker\Zed\PriceCartConnector\Business\Exception\PriceMissingException
     *
     * @return void
     */
    public function testIsNotPriceAbleWithInvalidPrice()
    {
        $priceProductFacadeStub = $this->createPriceProductFacadeStub();
        $priceProductFacadeStub->addPriceStub('123', 1000);
        $priceProductFacadeStub->addValidityStub('123', false);

        $cartChangeTransfer = $this->createCartChangeTransfer();

        $cartChangeTransfer->getItems()[0]->setSku('non existing');

        $priceManager = $this->createPriceManager($priceProductFacadeStub);
        $priceManager->addPriceToItems($cartChangeTransfer);
    }

    /**
     * @return \SprykerTest\Zed\PriceCartConnector\Business\Fixture\PriceProductFacadeStub|\Spryker\Zed\PriceProduct\Business\PriceProductFacade
     */
    protected function createPriceProductFacadeStub()
    {
        return new PriceProductFacadeStub();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface
     */
    protected function createPriceFacadeBridgeMock()
    {
        return $this->getMockBuilder(PriceCartToPriceInterface::class)->getMock();
    }

    /**
     * @param \SprykerTest\Zed\PriceCartConnector\Business\Fixture\PriceProductFacadeStub $priceProductFacadeStub
     *
     * @return \Spryker\Zed\PriceCartConnector\Business\Manager\PriceManager
     */
    protected function createPriceManager(PriceProductFacadeStub $priceProductFacadeStub)
    {
        $priceProductCartToPriceBridge = new PriceCartToPriceProductBridge($priceProductFacadeStub);

        $priceFacadeMock = $this->createPriceFacadeBridgeMock();

        $priceFacadeMock->method('getNetPriceModeIdentifier')
            ->willReturn('NET_MODE');

        $priceFacadeMock->method('getGrossPriceModeIdentifier')
            ->willReturn('GROSS_MODE');

        return new PriceManager($priceProductCartToPriceBridge, $priceFacadeMock);
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransfer()
    {
        $itemCollection = new CartChangeTransfer();

        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode('EUR');
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setCurrency($currencyTransfer);

        $itemCollection->setQuote($quoteTransfer);
        $item = new ItemTransfer();
        $item->setSku(123);
        $item->setId(123);
        $itemCollection->addItem($item);
        return $itemCollection;
    }
}
