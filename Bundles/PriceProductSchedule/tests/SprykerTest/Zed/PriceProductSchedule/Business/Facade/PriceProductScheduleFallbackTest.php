<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductSchedule\Business\Facade;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use ReflectionClass;
use Spryker\Zed\PriceProduct\Business\PriceProductFacade;
use Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleBusinessFactory;
use Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductSchedule
 * @group Business
 * @group Facade
 * @group PriceProductScheduleFallbackTest
 * Add your own group annotations below this line
 */
class PriceProductScheduleFallbackTest extends Unit
{
    /**
     * @var int
     */
    public const DEFAULT_PRICE_TYPE_ID = 1;

    /**
     * @var int
     */
    public const PRICE_TYPE_ID = 2;

    public const PRICE_TYPE_NAME_ORIGINAL = PriceProductScheduleConfig::PRICE_TYPE_ORIGINAL;

    /**
     * @var string
     */
    protected const DEFAULT_STORE = 'DE';

    /**
     * @var string
     */
    protected const DEFAULT_CURRENCY = 'EUR';

    /**
     * @var \SprykerTest\Zed\PriceProductSchedule\PriceProductScheduleBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected StoreTransfer $storeTransfer;

    /**
     * @var \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureDatabaseTableIsEmpty();

        $this->priceProductFacade = $this->tester->getLocator()->priceProduct()->facade();
        $this->storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DEFAULT_STORE]);
        $this->currencyFacade = $this->tester->getLocator()->currency()->facade();
    }

    /**
     * @return void
     */
    public function testProductPriceShouldBeRevertedAfterPriceProductScheduleIsOver(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $defaultPriceTypeTransfer = $this->tester->havePriceType();

        $currencyId = $this->tester->haveCurrency([CurrencyTransfer::CODE => 'test']);
        $currencyTransfer = $this->currencyFacade->getByIdCurrency($currencyId);

        $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::PRICE_TYPE => $defaultPriceTypeTransfer,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 100,
                MoneyValueTransfer::GROSS_AMOUNT => 100,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);

        $fallbackPriceTypeTransfer = $this->tester->havePriceType();

        $fallbackPriceProductTransfer = $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::PRICE_TYPE => $fallbackPriceTypeTransfer,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 200,
                MoneyValueTransfer::GROSS_AMOUNT => 200,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);

        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-1 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => true,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $this->storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 100,
                    MoneyValueTransfer::GROSS_AMOUNT => 100,
                ],
            ],
        ]);

        $priceProductScheduleFacade = $this->tester->getFacade();
        $priceProductScheduleFacade->setFactory(
            (new PriceProductScheduleBusinessFactory())
                ->setConfig($this->getConfigMock($defaultPriceTypeTransfer->getName(), $fallbackPriceTypeTransfer->getName())),
        );

        // Act
        $priceProductScheduleFacade->applyScheduledPrices($this->storeTransfer->getName());

        // Assert
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($productConcreteTransfer->getSku())
            ->setPriceTypeName($defaultPriceTypeTransfer->getName())
            ->setCurrencyIsoCode($currencyTransfer->getCode())
            ->setStoreName($this->storeTransfer->getName());

        $actualPriceProductTransfer = $this->priceProductFacade->findPriceProductFor($priceProductFilterTransfer);

        $this->assertEquals(
            $fallbackPriceProductTransfer->getMoneyValue()->getNetAmount(),
            $actualPriceProductTransfer->getMoneyValue()->getNetAmount(),
            'Net product price should have been reverted after scheduled price is over.',
        );
        $this->assertEquals(
            $fallbackPriceProductTransfer->getMoneyValue()->getGrossAmount(),
            $actualPriceProductTransfer->getMoneyValue()->getGrossAmount(),
            'Gross product price should have been reverted after scheduled price is over.',
        );
    }

    /**
     * @return void
     */
    public function testProductPriceShouldBeSwitchedToTheSecondScheduledPrice(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $defaultPriceTypeTransfer = $this->tester->havePriceType();

        $currencyId = $this->tester->haveCurrency([CurrencyTransfer::CODE => 'tes']);
        $currencyTransfer = $this->currencyFacade->getByIdCurrency($currencyId);

        $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::PRICE_TYPE => $defaultPriceTypeTransfer,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 100,
                MoneyValueTransfer::GROSS_AMOUNT => 100,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);

        $priceProductScheduleTransferOne = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+1 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $this->storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 1000,
                    MoneyValueTransfer::GROSS_AMOUNT => 1000,
                ],
            ],
        ]);

        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+5 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $this->storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::NET_AMOUNT => 200,
                    MoneyValueTransfer::GROSS_AMOUNT => 200,
                ],
            ],
        ]);

        $priceProductScheduleFacade = $this->tester->getFacade();

        // Act
        $priceProductScheduleFacade->applyScheduledPrices($this->storeTransfer->getName());
        $priceProductScheduleTransferOne->setActiveTo(new DateTime('-1 hour'));
        $priceProductScheduleFacade->updateAndApplyPriceProductSchedule($priceProductScheduleTransferOne);

        // Assert
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($productConcreteTransfer->getSku())
            ->setPriceTypeName($defaultPriceTypeTransfer->getName())
            ->setCurrencyIsoCode($currencyTransfer->getCode())
            ->setStoreName($this->storeTransfer->getName());

        $actualPriceProductTransfer = $this->priceProductFacade->findPriceProductFor($priceProductFilterTransfer);

        $this->assertEquals(
            200,
            $actualPriceProductTransfer->getMoneyValue()->getNetAmount(),
            'Net product price should have been reverted after scheduled price is over.',
        );
        $this->assertEquals(
            200,
            $actualPriceProductTransfer->getMoneyValue()->getGrossAmount(),
            'Gross product price should have been reverted after scheduled price is over.',
        );
    }

    /**
     * @return void
     */
    public function testProductPriceShouldBeRemovedIfFallbackPriceTypeNotConfigured(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule($this->getPriceProductScheduleData($productConcreteTransfer));

        $priceProductScheduleFacade = $this->tester->getFacade();
        $priceProductScheduleFacade->setFactory((new PriceProductScheduleBusinessFactory())->setConfig($this->getNotConfiguredConfigMock()));

        // Act
        $priceProductScheduleFacade->applyScheduledPrices();

        // Assert
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($productConcreteTransfer->getSku())
            ->setStoreName($this->storeTransfer->getName())
            ->setCurrency($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getCurrency())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete());

        $priceProductTransfer = $this->createPriceProductFacadeMock()->findPriceProductFor($priceProductFilterTransfer);
        $this->assertNull(
            $priceProductTransfer,
            'Product price type should be removed after scheduled price is over if no fallback price os configured.',
        );
    }

    /**
     * @dataProvider createNullableGrossNetCombinationsDataProvider
     *
     * @param int|null $netPrice
     * @param int|null $grossPrice
     *
     * @return void
     */
    public function testApplyScheduledPricesChecksNullablePricesDuringApplyingScheduledPrices(?int $netPrice, ?int $grossPrice): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $defaultPriceTypeTransfer = $this->tester->havePriceType();

        $priceProductTransfer = $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::PRICE_TYPE => $defaultPriceTypeTransfer,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 100,
                MoneyValueTransfer::GROSS_AMOUNT => 100,
            ],
        ]);

        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+4 hour')),
            PriceProductScheduleTransfer::IS_CURRENT => false,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $defaultPriceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $defaultPriceTypeTransfer->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $priceProductTransfer->getMoneyValue()->getStore()->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $priceProductTransfer->getMoneyValue()->getCurrency()->getIdCurrency(),
                    MoneyValueTransfer::CURRENCY => $priceProductTransfer->getMoneyValue()->getCurrency(),
                    MoneyValueTransfer::NET_AMOUNT => $netPrice,
                    MoneyValueTransfer::GROSS_AMOUNT => $grossPrice,
                ],
            ],
        ]);

        // Act
        $this->tester->getFacade()->applyScheduledPrices();

        // Assert
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($productConcreteTransfer->getSku())
            ->setPriceTypeName($defaultPriceTypeTransfer->getName())
            ->setCurrencyIsoCode($priceProductTransfer->getMoneyValue()->getCurrency()->getCode())
            ->setStoreName($this->storeTransfer->getName());

        $this->resetCachedEntities();
        $priceProductTransfer = $this->priceProductFacade->findPriceProductFor($priceProductFilterTransfer);

        $this->assertSame($netPrice ?? 100, $priceProductTransfer->getMoneyValue()->getNetAmount());
        $this->assertSame($grossPrice ?? 100, $priceProductTransfer->getMoneyValue()->getGrossAmount());
    }

    /**
     * @return array<string, list<int|null>>
     */
    public function createNullableGrossNetCombinationsDataProvider(): array
    {
        return [
            'net price, gross price' => [200, 200],
            '!net price, gross price' => [null, 200],
            'net price, !gross price' => [200, null],
            '!net price, !gross price' => [null, null],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return array
     */
    protected function getPriceProductScheduleData(ProductConcreteTransfer $productConcreteTransfer): array
    {
        $currencyId = $this->tester->haveCurrency();
        $priceType = $this->tester->havePriceType();

        return [
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-1 hour')),
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $priceType->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $priceType->getIdPriceType(),
                ],
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::FK_STORE => $this->storeTransfer->getIdStore(),
                    MoneyValueTransfer::FK_CURRENCY => $currencyId,
                ],
            ],
        ];
    }

    /**
     * @param string $priceTypeName
     * @param string $fallbackPriceTypeName
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig
     */
    protected function getConfigMock(string $priceTypeName, string $fallbackPriceTypeName): PriceProductScheduleConfig
    {
        $configMock = $this->getMockBuilder(PriceProductScheduleConfig::class)
            ->onlyMethods(['getFallbackPriceTypeList'])
            ->getMock();

        $configMock->method('getFallbackPriceTypeList')
            ->willReturn([$priceTypeName => $fallbackPriceTypeName]);

        return $configMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig
     */
    protected function getNotConfiguredConfigMock(): PriceProductScheduleConfig
    {
        $configMock = $this->getMockBuilder(PriceProductScheduleConfig::class)
            ->onlyMethods(['getFallbackPriceTypeList'])
            ->getMock();

        $configMock->method('getFallbackPriceTypeList')
            ->willReturn([]);

        return $configMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected function createPriceProductFacadeMock(): PriceProductFacadeInterface
    {
        $storeFacadeMock = $this->getMockBuilder(PriceProductToStoreFacadeInterface::class)
            ->onlyMethods(['getCurrentStore', 'getStoreByName', 'getStoreById', 'getStoreTransfersByStoreNames'])
            ->getMock();
        $storeFacadeMock->method('getStoreByName')
            ->willReturn((new StoreTransfer())
                ->setName(static::DEFAULT_STORE)
                ->setDefaultCurrencyIsoCode(static::DEFAULT_CURRENCY));

        $priceProductFactoryMock = $this->tester->mockFactoryMethod('getStoreFacade', $storeFacadeMock);
        $priceProductFacadeMock = $this->createMock(PriceProductFacade::class);
        $priceProductFacadeMock->setFactory($priceProductFactoryMock);

        return $priceProductFacadeMock;
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
