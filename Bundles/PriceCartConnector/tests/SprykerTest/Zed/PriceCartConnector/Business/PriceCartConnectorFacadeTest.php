<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceCartConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use ReflectionClass;
use Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacadeInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface;
use Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig;
use SprykerTest\Zed\PriceCartConnector\PriceCartConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceCartConnector
 * @group Business
 * @group Facade
 * @group PriceCartConnectorFacadeTest
 * Add your own group annotations below this line
 */
class PriceCartConnectorFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_SKU_1 = 'TEST_SKU_1';

    /**
     * @var string
     */
    protected const TEST_SKU_2 = 'TEST_SKU_2';

    /**
     * @var string
     */
    protected const TEST_CURRENCY_1 = 'TCF';

    /**
     * @var string
     */
    protected const TEST_CURRENCY_2 = 'TCS';

    /**
     * @var string
     */
    protected const TEST_CURRENCY_3 = 'TCT';

    /**
     * @var string
     */
    protected const TEST_CURRENCY_4 = 'TCС';

    /**
     * @var \SprykerTest\Zed\PriceCartConnector\PriceCartConnectorBusinessTester
     */
    protected PriceCartConnectorBusinessTester $tester;

    /**
     * @uses \Spryker\Zed\PriceCartConnector\Business\Validator\PriceProductValidator::CART_PRE_CHECK_PRICE_FAILED_TRANSLATION_KEY
     *
     * @var string
     */
    protected const GLOSSARY_KEY_CART_PRE_CHECK_PRICE_FAILED = 'cart.pre.check.price.failed';

    /**
     * @dataProvider getFilterItemsWithoutPriceDataProvider
     *
     * @param array<string, int|null> $itemsData
     * @param string $currencyCode
     * @param list<string> $itemFieldsForIdentifier
     * @param list<string> $expectedSkus
     *
     * @return void
     */
    public function testFilterItemsWithoutPriceWillRemoveItemsWithoutPrices(
        array $itemsData,
        string $currencyCode,
        array $itemFieldsForIdentifier,
        array $expectedSkus
    ): void {
        // Arrange
        $currencyTransfer = $this->tester->haveCurrencyTransfer([CurrencyTransfer::CODE => $currencyCode]);
        $quoteTransfer = $this->tester->createQuoteWithItems($itemsData, $currencyTransfer);

        // Act
        $filteredQuoteTransfer = $this->getFacadeWithMockedConfig($itemFieldsForIdentifier)->filterItemsWithoutPrice($quoteTransfer);

        // Assert
        $itemsSkus = array_map(function (ItemTransfer $itemTransfer) {
            return $itemTransfer->getSku();
        }, $filteredQuoteTransfer->getItems()->getArrayCopy());

        $this->assertSame($expectedSkus, $itemsSkus);
    }

    /**
     * @dataProvider getItemFieldsForIdentifierConfigDataProvider
     *
     * @param list<string> $itemFieldsForIdentifier
     *
     * @return void
     */
    public function testFilterItemsWithoutPriceWithEqualItems(array $itemFieldsForIdentifier): void
    {
        // Arrange
        $currencyTransfer = $this->tester->haveCurrencyTransfer([CurrencyTransfer::CODE => static::TEST_CURRENCY_4]);
        $quoteTransfer = $this->tester->createQuoteWithItems([static::TEST_SKU_1 => 1000], $currencyTransfer);

        /** @var \Generated\Shared\Transfer\ItemTransfer $existingItemTransfer */
        $existingItemTransfer = $quoteTransfer->getItems()->offsetGet(0);
        $existingItemTransfer->setQuantity(1);
        $quoteTransfer->addItem((new ItemTransfer())->fromArray($existingItemTransfer->toArray(), true));

        $this->tester->mockConfigMethod('getItemFieldsForIdentifier', $itemFieldsForIdentifier);

        // Act
        $filteredQuoteTransfer = $this->tester->getFacade()->filterItemsWithoutPrice($quoteTransfer);

        // Assert
        $itemTransfers = $filteredQuoteTransfer->getItems()->getArrayCopy();
        $this->assertCount(2, $itemTransfers);

        $itemsSkus = array_map(function (ItemTransfer $itemTransfer) {
            return $itemTransfer->getSku();
        }, $itemTransfers);
        $this->assertSame([static::TEST_SKU_1, static::TEST_SKU_1], $itemsSkus);
    }

    /**
     * @dataProvider getValidatePricesDataProvider
     *
     * @param int $productPrice
     * @param list<string> $itemFieldsForIdentifier
     * @param bool $isZeroPriceEnabledForCartActions
     * @param bool $expectedIsSuccess
     * @param list<string> $expectedResponseMessages
     *
     * @return void
     */
    public function testValidatePrices(
        int $productPrice,
        array $itemFieldsForIdentifier,
        bool $isZeroPriceEnabledForCartActions,
        bool $expectedIsSuccess,
        array $expectedResponseMessages
    ): void {
        // Arrange
        $priceCartConnectorFacade = $this->getConfiguredPriceCartConnectorFacade(
            static::TEST_SKU_1,
            $productPrice,
            $itemFieldsForIdentifier,
            $isZeroPriceEnabledForCartActions,
        );
        $cartChangeTransfer = $this->tester->createCartChangeTransferWithItem();

        // Act
        $cartPreCheckResponseTransfer = $priceCartConnectorFacade->validatePrices($cartChangeTransfer);

        // Assert
        $this->assertSame($expectedIsSuccess, $cartPreCheckResponseTransfer->getIsSuccess());
        $messages = $this->tester->getCartPreCheckResponseTransferMessages($cartPreCheckResponseTransfer);
        $this->assertSame($expectedResponseMessages, $messages);
    }

    /**
     * @dataProvider getItemFieldsForIdentifierConfigDataProvider
     *
     * @param list<string> $itemFieldsForIdentifier
     *
     * @return void
     */
    public function testValidatePricesWithEqualItems(array $itemFieldsForIdentifier): void
    {
        // Arrange
        $priceCartConnectorFacade = $this->getConfiguredPriceCartConnectorFacade(
            static::TEST_SKU_1,
            1000,
            $itemFieldsForIdentifier,
            false,
        );
        $cartChangeTransfer = $this->tester->createCartChangeTransferWithItem();

        /** @var \Generated\Shared\Transfer\ItemTransfer $existingItemTransfer */
        $existingItemTransfer = $cartChangeTransfer->getItems()->offsetGet(0);
        $existingItemTransfer->setQuantity(1);
        $cartChangeTransfer->addItem((new ItemTransfer())->fromArray($existingItemTransfer->toArray(), true));

        // Act
        $cartPreCheckResponseTransfer = $priceCartConnectorFacade->validatePrices($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertSame([], $this->tester->getCartPreCheckResponseTransferMessages($cartPreCheckResponseTransfer));
    }

    /**
     * @return array<string, array<array<string, int|null>|string|list<string>>
     */
    protected function getFilterItemsWithoutPriceDataProvider(): array
    {
        return [
            'Should not filter items with 0 price when collection of item fields used for building identifier is not empty.' => [
                [
                    static::TEST_SKU_1 => 100,
                    static::TEST_SKU_2 => 0,
                ],
                static::TEST_CURRENCY_1,
                [ItemTransfer::SKU, ItemTransfer::QUANTITY],
                [
                    static::TEST_SKU_1,
                    static::TEST_SKU_2,
                ],
            ],
            'Should filter out items with null price when collection of item fields used for building identifier is not empty.' => [
                [
                    static::TEST_SKU_1 => 300,
                    static::TEST_SKU_2 => null,
                ],
                static::TEST_CURRENCY_2,
                [ItemTransfer::SKU, ItemTransfer::QUANTITY],
                [
                    static::TEST_SKU_1,
                ],
            ],
            'Should filter out all items with null price when collection of item fields used for building identifier is not empty.' => [
                [
                    static::TEST_SKU_1 => null,
                    static::TEST_SKU_2 => null,
                ],
                static::TEST_CURRENCY_3,
                [ItemTransfer::SKU, ItemTransfer::QUANTITY],
                [],
            ],
        ];
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
     * @return array
     */
    protected function getValidatePricesDataProvider(): array
    {
        return [
            'Should return success with non zero price, disabled zero price config and empty collection of item fields used for building identifier config.' => [
                1000, [], false, true, [],
            ],
            'Should return success with non zero price, disabled zero price config and not empty collection of item fields used for building identifier config.' => [
                1000, [ItemTransfer::SKU, ItemTransfer::QUANTITY], false, true, [],
            ],
            'Should not return success with zero price, disabled zero price config and empty collection of item fields used for building identifier config.' => [
                0, [], false, false, [static::GLOSSARY_KEY_CART_PRE_CHECK_PRICE_FAILED],
            ],
            'Should not return success with zero price, disabled zero price config and not empty collection of item fields used for building identifier config.' => [
                0, [ItemTransfer::SKU, ItemTransfer::QUANTITY], false, false, [static::GLOSSARY_KEY_CART_PRE_CHECK_PRICE_FAILED],
            ],
            'Should return success with non zero price, enabled zero price config and empty collection of item fields used for building identifier config.' => [
                1000, [], false, true, [],
            ],
            'Should return success with non zero price, enabled zero price config and not empty collection of item fields used for building identifier config.' => [
                1000, [ItemTransfer::SKU, ItemTransfer::QUANTITY], false, true, [],
            ],
            'Should return success with zero price, enabled zero price config and empty collection of item fields used for building identifier config.' => [
                1000, [], false, true, [],
            ],
            'Should return success with zero price, enabled zero price config and not empty collection of item fields used for building identifier config.' => [
                1000, [ItemTransfer::SKU, ItemTransfer::QUANTITY], false, true, [],
            ],
        ];
    }

    /**
     * @param string $sku
     * @param int $price
     * @param list<string> $itemFieldsForIdentifier
     * @param bool $isZeroPriceEnabledForCartActions
     *
     * @return \Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacadeInterface
     */
    protected function getConfiguredPriceCartConnectorFacade(
        string $sku,
        int $price,
        array $itemFieldsForIdentifier,
        bool $isZeroPriceEnabledForCartActions
    ): PriceCartConnectorFacadeInterface {
        $priceProductFacadeStub = $this->tester->createPriceProductFacadeStub();
        $priceProductFacadeStub->addPriceStub($sku, $price);

        return $this->tester->createAndConfigurePriceCartConnectorFacade(
            $this->tester->createPriceCartConnectorConfigMock($itemFieldsForIdentifier, $isZeroPriceEnabledForCartActions),
            $priceProductFacadeStub,
            $this->getPriceFacadeMock(),
            $this->getCurrencyFacadeBridgeMock(),
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface
     */
    protected function getPriceFacadeMock(): PriceCartToPriceInterface
    {
        return $this->getMockBuilder(PriceCartToPriceInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface
     */
    protected function getCurrencyFacadeBridgeMock(): PriceCartConnectorToCurrencyFacadeInterface
    {
        return $this->getMockBuilder(PriceCartConnectorToCurrencyFacadeInterface::class)->getMock();
    }

    /**
     * @param list<string> $itemFieldsForIdentifier
     *
     * @return \Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacadeInterface
     */
    protected function getFacadeWithMockedConfig(array $itemFieldsForIdentifier): PriceCartConnectorFacadeInterface
    {
        $this->resetCachedEntities();
        $priceCartConnectorFacade = $this->tester->getFacade();
        $configMock = $this->createMock(PriceCartConnectorConfig::class);
        $configMock->method('getItemFieldsForIdentifier')
            ->willReturn($itemFieldsForIdentifier);

        $priceCartConnectorBusinessFactory = $this->tester->createPriceCartConnectorBusinessFactory();
        $priceCartConnectorBusinessFactory->setConfig($configMock);

        $priceCartConnectorFacade->setFactory($priceCartConnectorBusinessFactory);

        return $priceCartConnectorFacade;
    }

    /**
     * @return void
     */
    protected function resetCachedEntities(): void
    {
        $priceProductConcreteReaderReflection = new ReflectionClass("\Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductConcreteReader");
        $property = $priceProductConcreteReaderReflection->getProperty('priceCache');
        $property->setAccessible(true);
        $property->setValue(null, []);

        $readerReflection = new ReflectionClass("\Spryker\Zed\PriceProduct\Business\Model\Reader");
        $property = $readerReflection->getProperty('validPricesCache');
        $property->setAccessible(true);
        $property->setValue(null, []);

        $property = $readerReflection->getProperty('resolvedPriceProductTransferCollection');
        $property->setAccessible(true);
        $property->setValue(null, []);

        $productBundleCartExpanderReflection = new ReflectionClass("Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartExpander");
        $property = $productBundleCartExpanderReflection->getProperty('productConcreteCache');
        $property->setAccessible(true);
        $property->setValue(null, []);

        $property = $productBundleCartExpanderReflection->getProperty('productPriceCache');
        $property->setAccessible(true);
        $property->setValue(null, []);
    }
}
