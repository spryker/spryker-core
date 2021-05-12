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
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\PriceCartConnector\Business\Filter\Comparator\ItemComparator;
use Spryker\Zed\PriceCartConnector\Business\Filter\Comparator\ItemComparatorInterface;
use Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilter;
use Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilterInterface;
use Spryker\Zed\PriceCartConnector\Business\Manager\PriceManager;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToPriceProductAdapter;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Service\PriceCartConnectorToPriceProductServiceBridge;
use Spryker\Zed\PriceCartConnector\Dependency\Service\PriceCartConnectorToPriceProductServiceInterface;
use Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig;
use SprykerTest\Zed\PriceCartConnector\Business\Fixture\PriceProductFacadeStub;

/**
 * Auto-generated group annotations
 *
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
     * @var \SprykerTest\Zed\PriceCartConnector\PriceCartConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddPriceToItems(): void
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
    public function testSourceUnitPriceHasHighestPriority(): void
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
     * @return void
     */
    public function testIsNotPriceAbleWithInvalidPrice(): void
    {
        $this->expectException('Spryker\Zed\PriceCartConnector\Business\Exception\PriceMissingException');
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface
     */
    protected function createPriceFacadeBridgeMock(): PriceCartToPriceInterface
    {
        return $this->getMockBuilder(PriceCartToPriceInterface::class)->getMock();
    }

    /**
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface $priceProductCartToPriceBridge
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface $priceFacadeMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilterInterface
     */
    protected function createPriceProductFilterMock(
        PriceCartToPriceProductInterface $priceProductCartToPriceBridge,
        PriceCartToPriceInterface $priceFacadeMock
    ): PriceProductFilterInterface {
        return new PriceProductFilter(
            $priceProductCartToPriceBridge,
            $priceFacadeMock,
            $this->createCurrencyFacadeBridgeMock(),
            [],
            $this->createItemComparator()
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface
     */
    protected function createCurrencyFacadeBridgeMock(): PriceCartConnectorToCurrencyFacadeInterface
    {
        return $this->getMockBuilder(PriceCartConnectorToCurrencyFacadeInterface::class)->getMock();
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Business\Filter\Comparator\ItemComparatorInterface
     */
    protected function createItemComparator(): ItemComparatorInterface
    {
        return new ItemComparator($this->createPriceCartConnectorConfigMock());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig
     */
    protected function createPriceCartConnectorConfigMock(): PriceCartConnectorConfig
    {
        return $this->getMockBuilder(PriceCartConnectorConfig::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();
    }

    /**
     * @param \SprykerTest\Zed\PriceCartConnector\Business\Fixture\PriceProductFacadeStub $priceProductFacadeStub
     *
     * @return \Spryker\Zed\PriceCartConnector\Business\Manager\PriceManager
     */
    protected function createPriceManager(PriceProductFacadeStub $priceProductFacadeStub): PriceManager
    {
        $priceProductCartToPriceAdapter = new PriceCartConnectorToPriceProductAdapter($priceProductFacadeStub);

        $priceFacadeMock = $this->createPriceFacadeBridgeMock();

        $priceFacadeMock->method('getNetPriceModeIdentifier')
            ->willReturn('NET_MODE');

        $priceFacadeMock->method('getGrossPriceModeIdentifier')
            ->willReturn('GROSS_MODE');

        $priceFacadeMock->method('getDefaultPriceMode')
            ->willReturn('GROSS_MODE');

        $priceProductFilterMock = $this->createPriceProductFilterMock($priceProductCartToPriceAdapter, $priceFacadeMock);

        return new PriceManager($priceProductCartToPriceAdapter, $priceFacadeMock, $priceProductFilterMock, $this->createPriceCartConnectorToPriceProductServiceBridge(), []);
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Dependency\Service\PriceCartConnectorToPriceProductServiceInterface
     */
    protected function createPriceCartConnectorToPriceProductServiceBridge(): PriceCartConnectorToPriceProductServiceInterface
    {
        return new PriceCartConnectorToPriceProductServiceBridge($this->tester->getLocator()->priceProduct()->service());
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransfer(): CartChangeTransfer
    {
        $itemCollection = new CartChangeTransfer();

        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode('EUR');

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setCurrency($currencyTransfer);

        $itemCollection->setQuote($quoteTransfer);

        $store = new StoreTransfer();
        $store->setName('DE');

        $quoteTransfer->setStore($store);

        return $itemCollection;
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransferWithItem(): CartChangeTransfer
    {
        $itemCollection = $this->createCartChangeTransfer();

        $item = new ItemTransfer();
        $item->setSku(123);
        $item->setId(123);
        $itemCollection->addItem($item);

        return $itemCollection;
    }
}
