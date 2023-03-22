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
use Spryker\Zed\PriceCartConnector\Business\Exception\PriceMissingException;
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
use SprykerTest\Zed\PriceCartConnector\Business\Fixture\PriceProductFacadeStub;
use SprykerTest\Zed\PriceCartConnector\PriceCartConnectorBusinessTester;

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
     * @var string
     */
    protected const TEST_ITEM_SKU_1 = '123';

    /**
     * @var string
     */
    protected const TEST_ITEM_SKU_2 = '124';

    /**
     * @var string
     */
    protected const TEST_ITEM_SKU_3 = '125';

    /**
     * @var \SprykerTest\Zed\PriceCartConnector\PriceCartConnectorBusinessTester
     */
    protected PriceCartConnectorBusinessTester $tester;

    /**
     * @dataProvider getItemFieldsForIdentifierConfigDataProvider
     *
     * @param list<string> $itemFieldsForIdentifier
     *
     * @return void
     */
    public function testAddPriceToItems(array $itemFieldsForIdentifier): void
    {
        // Arrange
        $priceProductFacadeStub = $this->createPriceProductFacadeStub();
        $priceProductFacadeStub->addPriceStub(static::TEST_ITEM_SKU_1, 1000);
        $priceProductFacadeStub->addValidityStub(static::TEST_ITEM_SKU_1, true);

        $cartChangeTransfer = $this->createCartChangeTransferWithItem();

        // Act
        $modifiedItemCollection = $this->createPriceManager($priceProductFacadeStub, $itemFieldsForIdentifier)
            ->addPriceToItems($cartChangeTransfer);

        // Assert
        $this->assertSame(1, $modifiedItemCollection->getItems()->count());

        foreach ($modifiedItemCollection->getItems() as $modifiedItem) {
            $this->assertSame(1000, $modifiedItem->getUnitGrossPrice());
        }
    }

    /**
     * @dataProvider getItemFieldsForIdentifierConfigDataProvider
     *
     * @param list<string> $itemFieldsForIdentifier
     *
     * @return void
     */
    public function testSourceUnitPriceHasHighestPriority(array $itemFieldsForIdentifier): void
    {
        // Arrange
        $priceProductFacadeStub = $this->createPriceProductFacadeStub();
        $priceProductFacadeStub->addPriceStub(static::TEST_ITEM_SKU_1, 1000);
        $priceProductFacadeStub->addValidityStub(static::TEST_ITEM_SKU_1, true);
        $priceProductFacadeStub->addPriceStub(static::TEST_ITEM_SKU_2, 2000);
        $priceProductFacadeStub->addValidityStub(static::TEST_ITEM_SKU_2, true);
        $priceProductFacadeStub->addPriceStub(static::TEST_ITEM_SKU_3, 3000);
        $priceProductFacadeStub->addValidityStub(static::TEST_ITEM_SKU_3, true);

        $cartChangeTransfer = $this->createCartChangeTransfer();

        $itemTransferWithForcedPrice = (new ItemTransfer())
            ->setSku(static::TEST_ITEM_SKU_1)
            ->setId((int)static::TEST_ITEM_SKU_1)
            ->setSourceUnitGrossPrice(1001);

        $itemTransferWithEmptyForcedPrice = (new ItemTransfer())
            ->setSku(static::TEST_ITEM_SKU_2)
            ->setId((int)static::TEST_ITEM_SKU_2);

        $itemTransferWithZeroForcedPrice = (new ItemTransfer())
            ->setSku(static::TEST_ITEM_SKU_3)
            ->setId((int)static::TEST_ITEM_SKU_3)
            ->setSourceUnitGrossPrice(0);

        $cartChangeTransfer
            ->addItem($itemTransferWithForcedPrice)
            ->addItem($itemTransferWithEmptyForcedPrice)
            ->addItem($itemTransferWithZeroForcedPrice);

        // Act
        $modifiedItemCollection = $this->createPriceManager($priceProductFacadeStub, $itemFieldsForIdentifier)
            ->addPriceToItems($cartChangeTransfer);

        // Assert
        $this->assertSame(3, $modifiedItemCollection->getItems()->count());

        $modifiedItemIterator = $modifiedItemCollection->getItems()->getIterator();
        $modifiedItemIterator->rewind();
        $this->assertSame(1000, $modifiedItemIterator->current()->getOriginUnitGrossPrice());
        $this->assertSame(1001, $modifiedItemIterator->current()->getSourceUnitGrossPrice());
        $this->assertSame(1001, $modifiedItemIterator->current()->getUnitGrossPrice());

        $modifiedItemIterator->next();
        $this->assertSame(2000, $modifiedItemIterator->current()->getOriginUnitGrossPrice());
        $this->assertNull($modifiedItemIterator->current()->getSourceUnitGrossPrice());
        $this->assertSame(2000, $modifiedItemIterator->current()->getUnitGrossPrice());

        $modifiedItemIterator->next();
        $this->assertSame(3000, $modifiedItemIterator->current()->getOriginUnitGrossPrice());
        $this->assertSame(0, $modifiedItemIterator->current()->getSourceUnitGrossPrice());
        $this->assertSame(0, $modifiedItemIterator->current()->getUnitGrossPrice());
    }

    /**
     * @dataProvider getItemFieldsForIdentifierConfigDataProvider
     *
     * @param list<string> $itemFieldsForIdentifier
     *
     * @return void
     */
    public function testIsNotPriceAbleWithInvalidPrice(array $itemFieldsForIdentifier): void
    {
        // Asset
        $this->expectException(PriceMissingException::class);

        // Arrange
        $priceProductFacadeStub = $this->createPriceProductFacadeStub();
        $priceProductFacadeStub->addPriceStub(static::TEST_ITEM_SKU_1, 1000);
        $priceProductFacadeStub->addValidityStub(static::TEST_ITEM_SKU_1, false);

        $cartChangeTransfer = $this->createCartChangeTransferWithItem();

        $cartChangeTransfer->getItems()[0]->setSku('non existing');

        // Act
        $this->createPriceManager($priceProductFacadeStub, $itemFieldsForIdentifier)
            ->addPriceToItems($cartChangeTransfer);
    }

    /**
     * @dataProvider getItemFieldsForIdentifierConfigDataProvider
     *
     * @param list<string> $itemFieldsForIdentifier
     *
     * @return void
     */
    public function testAddPriceToItemsWithEqualItems(array $itemFieldsForIdentifier): void
    {
        // Arrange
        $priceProductFacadeStub = $this->createPriceProductFacadeStub();
        $priceProductFacadeStub->addPriceStub(static::TEST_ITEM_SKU_1, 1000);
        $priceProductFacadeStub->addValidityStub(static::TEST_ITEM_SKU_1, true);
        $priceProductFacadeStub->addPriceStub(static::TEST_ITEM_SKU_2, 2000);
        $priceProductFacadeStub->addValidityStub(static::TEST_ITEM_SKU_2, true);

        $cartChangeTransfer = $this->createCartChangeTransferWithItem();

        $existingItemTransfer = (new ItemTransfer())
            ->setSku(static::TEST_ITEM_SKU_2)
            ->setId((int)static::TEST_ITEM_SKU_2)
            ->setQuantity(1);

        $cartChangeTransfer->addItem($existingItemTransfer);
        $cartChangeTransfer->addItem((new ItemTransfer())->fromArray($existingItemTransfer->toArray(), true));

        // Act
        $modifiedItemCollection = $this->createPriceManager($priceProductFacadeStub, $itemFieldsForIdentifier)
            ->addPriceToItems($cartChangeTransfer);

        // Assert
        $itemTransfers = $modifiedItemCollection->getItems();

        $this->assertSame(3, $itemTransfers->count());
        $this->assertSame(1000, $itemTransfers->getIterator()->offsetGet(0)->getUnitGrossPrice());
        $this->assertSame(2000, $itemTransfers->getIterator()->offsetGet(1)->getUnitGrossPrice());
        $this->assertSame(2000, $itemTransfers->getIterator()->offsetGet(2)->getUnitGrossPrice());
    }

    /**
     * @return array<string, list<string>>
     */
    protected function getItemFieldsForIdentifierConfigDataProvider(): array
    {
        return [
            'Collection of item fields used for building identifier is empty.' => [[]],
            'Collection of item fields used for building identifier is not empty.' => [[
                ItemTransfer::SKU,
                ItemTransfer::QUANTITY,
            ]],
        ];
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
            $this->createItemComparator(),
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
        return new ItemComparator($this->tester->createPriceCartConnectorConfigMock());
    }

    /**
     * @param \SprykerTest\Zed\PriceCartConnector\Business\Fixture\PriceProductFacadeStub $priceProductFacadeStub
     * @param list<string> $itemFieldsForIdentifier
     *
     * @return \Spryker\Zed\PriceCartConnector\Business\Manager\PriceManager
     */
    protected function createPriceManager(PriceProductFacadeStub $priceProductFacadeStub, array $itemFieldsForIdentifier = []): PriceManager
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

        return new PriceManager(
            $priceProductCartToPriceAdapter,
            $priceFacadeMock,
            $priceProductFilterMock,
            $this->createPriceCartConnectorToPriceProductServiceBridge(),
            [],
            $this->tester->createItemIdentifierBuilder(
                $this->tester->createPriceCartConnectorConfigMock($itemFieldsForIdentifier),
            ),
        );
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
        return $this->createCartChangeTransfer()
            ->addItem(
                (new ItemTransfer())
                    ->setSku(static::TEST_ITEM_SKU_1)
                    ->setId((int)static::TEST_ITEM_SKU_1)
                    ->setQuantity(1),
            );
    }
}
