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

        $cartChangeTransfer = $this->createCartChangeTransferWithItem();

        $priceManager = $this->createPriceManager($priceProductFacadeStub);

        $modifiedItemCollection = $priceManager->addPriceToItems($cartChangeTransfer);

        $this->assertSame(1, $modifiedItemCollection->getItems()->count());

        foreach ($modifiedItemCollection->getItems() as $modifiedItem) {
            $this->assertSame(1000, $modifiedItem->getUnitGrossPrice());
        }
    }

    /**
     * @return void
     */
    public function testSourceUnitPriceHasHighestPriority()
    {
        $priceProductFacadeStub = $this->createPriceProductFacadeStub();
        $priceProductFacadeStub->addPriceStub('123', 1000);
        $priceProductFacadeStub->addValidityStub('123', true);
        $priceProductFacadeStub->addPriceStub('124', 2000);
        $priceProductFacadeStub->addValidityStub('124', true);
        $priceProductFacadeStub->addPriceStub('125', 3000);
        $priceProductFacadeStub->addValidityStub('125', true);

        $cartChangeTransfer = $this->createCartChangeTransfer();

        $itemTransferWithForcedPrice = (new ItemTransfer())
            ->setSku(123)
            ->setId(123)
            ->setSourceUnitGrossPrice(1001);

        $itemTransferWithEmptyForcedPrice = (new ItemTransfer())
            ->setSku(124)
            ->setId(124);

        $itemTransferWithZeroForcedPrice = (new ItemTransfer())
            ->setSku(125)
            ->setId(125)
            ->setSourceUnitGrossPrice(0);

        $cartChangeTransfer
            ->addItem($itemTransferWithForcedPrice)
            ->addItem($itemTransferWithEmptyForcedPrice)
            ->addItem($itemTransferWithZeroForcedPrice);

        $priceManager = $this->createPriceManager($priceProductFacadeStub);

        $modifiedItemCollection = $priceManager->addPriceToItems($cartChangeTransfer);

        $this->assertSame(3, $modifiedItemCollection->getItems()->count());

        $modifiedItemIterator = $modifiedItemCollection->getItems()->getIterator();
        $modifiedItemIterator->rewind();
        $this->assertSame(1000, $modifiedItemIterator->current()->getOriginUnitGrossPrice());
        $this->assertSame(1001, $modifiedItemIterator->current()->getSourceUnitGrossPrice());
        $this->assertSame(1001, $modifiedItemIterator->current()->getUnitGrossPrice());

        $modifiedItemIterator->next();
        $this->assertSame(2000, $modifiedItemIterator->current()->getOriginUnitGrossPrice());
        $this->assertSame(null, $modifiedItemIterator->current()->getSourceUnitGrossPrice());
        $this->assertSame(2000, $modifiedItemIterator->current()->getUnitGrossPrice());

        $modifiedItemIterator->next();
        $this->assertSame(3000, $modifiedItemIterator->current()->getOriginUnitGrossPrice());
        $this->assertSame(0, $modifiedItemIterator->current()->getSourceUnitGrossPrice());
        $this->assertSame(0, $modifiedItemIterator->current()->getUnitGrossPrice());
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

        $cartChangeTransfer = $this->createCartChangeTransferWithItem();

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
     * @return CartChangeTransfer
     */
    protected function createCartChangeTransfer()
    {
        $itemCollection = new CartChangeTransfer();

        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode('EUR');

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setCurrency($currencyTransfer);

        $itemCollection->setQuote($quoteTransfer);

        return $itemCollection;
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransferWithItem()
    {
        $itemCollection = $this->createCartChangeTransfer();

        $item = new ItemTransfer();
        $item->setSku(123);
        $item->setId(123);
        $itemCollection->addItem($item);

        return $itemCollection;
    }
}
