<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\PriceProductFilterBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use ReflectionClass;
use Spryker\Shared\Price\PriceConfig;
use Spryker\Shared\PriceProduct\PriceProductConfig;
use Spryker\Zed\Currency\Business\CurrencyFacade;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;
use Spryker\Zed\PriceProduct\Business\Model\Reader;
use Spryker\Zed\PriceProduct\Business\PriceProductBusinessFactory;
use Spryker\Zed\PriceProduct\Business\PriceProductFacade;
use Spryker\Zed\PriceProduct\Communication\Plugin\DefaultPriceQueryCriteriaPlugin;
use Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManager;
use Spryker\Zed\PriceProduct\PriceProductDependencyProvider;
use Spryker\Zed\Store\Business\StoreFacade;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Facade
 * @group PriceProductFacadeTest
 * Add your own group annotations below this line
 */
class PriceProductFacadeTest extends Unit
{
    /**
     * @var string
     */
    public const EUR_ISO_CODE = 'EUR';

    /**
     * @var string
     */
    public const USD_ISO_CODE = 'USD';

    /**
     * @var string
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @var int
     */
    protected const COUNT_PRODUCT_WITH_PRICES = 5;

    /**
     * @var string
     */
    protected const FAKE_CURRENCY = 'FAKE_CURRENCY';

    /**
     * @var string
     */
    protected const PRICE_TYPE_ORIGINAL = 'ORIGINAL';

    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_DATA
     *
     * @var string
     */
    protected const PRICE_DATA = 'priceData';

    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_DATA_BY_PRICE_TYPE
     *
     * @var string
     */
    protected const PRICE_DATA_BY_PRICE_TYPE = 'priceDataByPriceType';

    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $priceDimensionQueryCriteriaPlugins = [
            new DefaultPriceQueryCriteriaPlugin(),
        ];

        $this->tester->setDependency(PriceProductDependencyProvider::PLUGIN_PRICE_DIMENSION_QUERY_CRITERIA, $priceDimensionQueryCriteriaPlugins);
    }

    /**
     * @return void
     */
    public function testGetPriceTypeValuesShouldReturnListOfAllPersistedPriceTypes(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceTypesBefore = $priceProductFacade->getPriceTypeValues();

        $priceProductFacade->createPriceType('test');

        $priceTypesAfter = $priceProductFacade->getPriceTypeValues();

        $this->assertCount(count($priceTypesBefore) + 1, $priceTypesAfter);
    }

    /**
     * @return void
     */
    public function testGetPriceBySkuShouldReturnDefaultPriceForGivenProduct(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceProductTransfer = $this->createProductWithAmount(50, 40);

        $price = $priceProductFacade->findPriceBySku($priceProductTransfer->getSkuProduct());

        $this->assertSame(50, $price);
    }

    /**
     * @return void
     */
    public function testGetPriceForShouldReturnPriceBasedOnFilter(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceProductTransfer = $this->createProductWithAmount(
            100,
            90,
            '',
            '',
            static::USD_ISO_CODE,
        );

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setCurrencyIsoCode(static::USD_ISO_CODE)
            ->setSku($priceProductTransfer->getSkuProduct());

        $price = $priceProductFacade->findPriceFor($priceProductFilterTransfer);

        $this->assertSame(100, $price);
    }

    /**
     * @return void
     */
    public function testCreatePriceType(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $idPriceType = $priceProductFacade->createPriceType('test price type');

        $this->assertNotEmpty($idPriceType);
    }

    /**
     * @return void
     */
    public function testSetPriceForProductShouldUpdateExistingPrice(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceProductTransfer = $this->createProductWithAmount(50, 40);

        $priceProductTransfer->getMoneyValue()->setGrossAmount(100);

        $priceProductFacade->setPriceForProduct($priceProductTransfer);

        $price = $priceProductFacade->findPriceBySku($priceProductTransfer->getSkuProduct());

        $this->assertSame(100, $price);
    }

    /**
     * @return void
     */
    public function testHasValidPriceShouldReturnTrueWhenProductHavePrices(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceProductTransfer = $this->createProductWithAmount(50, 40);

        $this->assertTrue(
            $priceProductFacade->hasValidPrice($priceProductTransfer->getSkuProduct()),
        );
    }

    /**
     * @return void
     */
    public function testHasValidPriceForReturnTrueWhenProductHavePrices(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceProductTransfer = $this->createProductWithAmount(50, 40);

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($priceProductTransfer->getSkuProduct());

        $this->assertTrue(
            $priceProductFacade->hasValidPriceFor($priceProductFilterTransfer),
        );
    }

    /**
     * @return void
     */
    public function testGetDefaultPriceTypeNameShouldReturnDefaultTypeName(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();
        $this->assertNotEmpty($priceProductFacade->getDefaultPriceTypeName());
    }

    /**
     * @return void
     */
    public function testGetIdPriceProductShouldReturnIdOfPriceProductEntity(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceProductTransfer = $this->createProductWithAmount(50, 40);

        $idPriceProduct = $priceProductFacade->getIdPriceProduct(
            $priceProductTransfer->getSkuProduct(),
            $priceProductFacade->getDefaultPriceTypeName(),
            $this->createCurrencyFacade()->getCurrent()->getCode(),
        );

        $this->assertSame($idPriceProduct, $priceProductTransfer->getIdPriceProduct());
    }

    /**
     * @return void
     */
    public function testPersistProductAbstractPriceCollectionShouldSavePriceCollection(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($priceProductFacade->getDefaultPriceTypeName());

        $productConcreteTransfer = $this->tester->haveProduct();

        $prices = new ArrayObject();
        $prices[] = $this->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 10, 9, static::EUR_ISO_CODE);
        $prices[] = $this->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 11, 10, static::USD_ISO_CODE);

        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setSku($productConcreteTransfer->getAbstractSku())
            ->setPrices($prices);

        $productAbstractTransfer = $priceProductFacade->persistProductAbstractPriceCollection($productAbstractTransfer);

        foreach ($productAbstractTransfer->getPrices() as $priceProductTransfer) {
            $this->assertNotEmpty($priceProductTransfer->getIdPriceProduct());
            $this->assertNotEmpty($priceProductTransfer->getMoneyValue()->getIdEntity());
        }
    }

    /**
     * @return void
     */
    public function testPersistProductConcretePriceCollectionShouldSavePriceCollection(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($priceProductFacade->getDefaultPriceTypeName());

        $productConcreteTransfer = $this->tester->haveProduct();

        $prices = new ArrayObject();
        $prices[] = $this->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 10, 9, static::EUR_ISO_CODE);

        $productConcreteTransfer->setPrices($prices);

        $productConcreteTransfer = $priceProductFacade->persistProductConcretePriceCollection($productConcreteTransfer);

        foreach ($productConcreteTransfer->getPrices() as $priceProductTransfer) {
            $this->assertNotEmpty($priceProductTransfer->getIdPriceProduct());
            $this->assertNotEmpty($priceProductTransfer->getMoneyValue()->getIdEntity());
        }
    }

    /**
     * @return void
     */
    public function testPriceFindPricesBySkuShouldReturnPricesForCurrentStoreConfiguration(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($priceProductFacade->getDefaultPriceTypeName());

        $productConcreteTransfer = $this->tester->haveProduct();

        $prices = new ArrayObject();
        $prices[] = $this->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 10, 9, static::EUR_ISO_CODE);
        $prices[] = $this->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 10, 9, static::USD_ISO_CODE);

        $productConcreteTransfer->setPrices($prices);

        $productConcreteTransfer = $priceProductFacade->persistProductConcretePriceCollection($productConcreteTransfer);

        $storePrices = $priceProductFacade->findPricesBySkuForCurrentStore($productConcreteTransfer->getSku());

        $this->assertCount(2, $storePrices);
    }

    /**
     * @return void
     */
    public function testFindPricesBySkuGroupedShouldReturnGroupedPrices(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $defaultPriceMode = $priceProductFacade->getDefaultPriceTypeName();
        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($defaultPriceMode);

        $productConcreteTransfer = $this->tester->haveProduct();

        $prices = new ArrayObject();
        $prices[] = $this->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 10, 9, static::EUR_ISO_CODE);
        $prices[] = $this->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 10, 9, static::USD_ISO_CODE);

        $productConcreteTransfer->setPrices($prices);

        $productConcreteTransfer = $priceProductFacade->persistProductConcretePriceCollection($productConcreteTransfer);

        $storePrices = $priceProductFacade->findPricesBySkuGroupedForCurrentStore($productConcreteTransfer->getSku());

        $this->assertCount(2, $storePrices);

        $this->assertArrayHasKey(static::EUR_ISO_CODE, $storePrices);
        $this->assertArrayHasKey(static::USD_ISO_CODE, $storePrices);

        $this->assertArrayHasKey(PriceConfig::PRICE_MODE_GROSS, $storePrices[static::EUR_ISO_CODE]);
        $this->assertArrayHasKey(PriceConfig::PRICE_MODE_NET, $storePrices[static::EUR_ISO_CODE]);
        $this->assertArrayHasKey(PriceConfig::PRICE_MODE_GROSS, $storePrices[static::USD_ISO_CODE]);
        $this->assertArrayHasKey(PriceConfig::PRICE_MODE_NET, $storePrices[static::USD_ISO_CODE]);

        $this->assertArrayHasKey($defaultPriceMode, $storePrices[static::USD_ISO_CODE][PriceConfig::PRICE_MODE_GROSS]);
        $this->assertArrayHasKey($defaultPriceMode, $storePrices[static::USD_ISO_CODE][PriceConfig::PRICE_MODE_NET]);

        $priceGross = $storePrices[static::USD_ISO_CODE][PriceConfig::PRICE_MODE_GROSS][$defaultPriceMode];
        $priceNet = $storePrices[static::USD_ISO_CODE][PriceConfig::PRICE_MODE_NET][$defaultPriceMode];

        $this->assertSame(9, $priceGross);
        $this->assertSame(10, $priceNet);
    }

    /**
     * @return void
     */
    public function testFindProductAbstractPricesShouldReturnPriceAssignedToAbstractProduct(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($priceProductFacade->getDefaultPriceTypeName());

        $productConcreteTransfer = $this->tester->haveProduct();

        $prices = new ArrayObject();
        $prices[] = $this->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 10, 9, static::EUR_ISO_CODE);
        $prices[] = $this->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 11, 10, static::USD_ISO_CODE);

        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setSku($productConcreteTransfer->getAbstractSku())
            ->setPrices($prices);

        $productAbstractTransfer = $priceProductFacade->persistProductAbstractPriceCollection($productAbstractTransfer);

        $storedPrices = $priceProductFacade->findProductAbstractPrices(
            $productAbstractTransfer->getIdProductAbstract(),
            $this->createPriceProductCriteriaTransfer(),
        );

        $this->assertCount(2, $storedPrices);
    }

    /**
     * @return void
     */
    public function testFindProductConcretePricesShouldReturnPriceAssignedToConcreteProduct(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($priceProductFacade->getDefaultPriceTypeName());

        $productConcreteTransfer = $this->tester->haveProduct();

        $prices = new ArrayObject();
        $prices[] = $this->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 10, 9, static::EUR_ISO_CODE);

        $productConcreteTransfer->setPrices($prices);

        $productConcreteTransfer = $priceProductFacade->persistProductConcretePriceCollection($productConcreteTransfer);

        $storedPrices = $priceProductFacade->findProductConcretePrices(
            $productConcreteTransfer->getIdProductConcrete(),
            $productConcreteTransfer->getFkProductAbstract(),
            $this->createPriceProductCriteriaTransfer(),
        );

        $this->assertCount(1, $storedPrices);
    }

    /**
     * @return void
     */
    public function testFindProductAbstractPriceShouldReturnDefaultPriceForAbstractProduct(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($priceProductFacade->getDefaultPriceTypeName());

        $productConcreteTransfer = $this->tester->haveProduct();

        $prices = new ArrayObject();
        $prices[] = $this->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 10, 9, static::EUR_ISO_CODE);
        $prices[] = $this->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 11, 10, static::USD_ISO_CODE);

        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setSku($productConcreteTransfer->getAbstractSku())
            ->setPrices($prices);

        $productAbstractTransfer = $priceProductFacade->persistProductAbstractPriceCollection($productAbstractTransfer);

        $priceProductTransfer = $priceProductFacade->findProductAbstractPrice($productAbstractTransfer->getIdProductAbstract());

        $this->assertSame(9, $priceProductTransfer->getMoneyValue()->getGrossAmount());
        $this->assertSame(10, $priceProductTransfer->getMoneyValue()->getNetAmount());
    }

    /**
     * @return void
     */
    public function testGroupPriceProductCollectionGroupsProvidedCollection(): void
    {
        // Assign
        $priceProductFacade = $this->getPriceProductFacade();
        $defaultPriceTypeName = $priceProductFacade->getDefaultPriceTypeName();
        $expectedResult = [
            'dummy currency 1' => [
                'GROSS_MODE' => [
                    $defaultPriceTypeName => 100,
                    static::PRICE_TYPE_ORIGINAL => 1100,
                ],
                'NET_MODE' => [
                    $defaultPriceTypeName => 300,
                    static::PRICE_TYPE_ORIGINAL => 1300,
                ],
                'priceData' => null,
                'priceDataByPriceType' => [
                    $defaultPriceTypeName => null,
                    static::PRICE_TYPE_ORIGINAL => null,
                ],
            ],
            'dummy currency 2' => [
                'GROSS_MODE' => [
                    $defaultPriceTypeName => 200,
                    static::PRICE_TYPE_ORIGINAL => 1200,
                ],
                'NET_MODE' => [
                    $defaultPriceTypeName => 400,
                    static::PRICE_TYPE_ORIGINAL => 1400,
                ],
                'priceData' => null,
                'priceDataByPriceType' => [
                    $defaultPriceTypeName => null,
                    static::PRICE_TYPE_ORIGINAL => null,
                ],
            ],
        ];
        $priceProductCollection = [];
        $priceProductCollection[] = $this->createPriceProduct('dummy currency 1', $defaultPriceTypeName, 100, 300);
        $priceProductCollection[] = $this->createPriceProduct('dummy currency 1', static::PRICE_TYPE_ORIGINAL, 1100, 1300);
        $priceProductCollection[] = $this->createPriceProduct('dummy currency 2', $defaultPriceTypeName, 200, 400);
        $priceProductCollection[] = $this->createPriceProduct('dummy currency 2', static::PRICE_TYPE_ORIGINAL, 1200, 1400);

        // Act
        $actualResult = $priceProductFacade->groupPriceProductCollection($priceProductCollection);

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testGroupPriceProductCollectionDoesNotOverwritePriceDataByNull(): void
    {
        // Assign
        $priceProductFacade = $this->getPriceProductFacade();
        $defaultPriceTypeName = $priceProductFacade->getDefaultPriceTypeName();

        $expectedPriceData = 'dummy price data';

        $priceProductWithPriceData = $this->createPriceProduct('dummy currency 1', $defaultPriceTypeName, 100, 300);
        $priceProductWithPriceData->getMoneyValue()->setPriceData($expectedPriceData);

        $priceProductCollection = [];
        $priceProductCollection[] = $priceProductWithPriceData;
        $priceProductCollection[] = $this->createPriceProduct('dummy currency 1', static::PRICE_TYPE_ORIGINAL, 1100, 1300);

        // Act
        $actualResult = $priceProductFacade->groupPriceProductCollection($priceProductCollection);

        // Assert
        $this->assertSame($expectedPriceData, $actualResult['dummy currency 1']['priceData']);
    }

    /**
     * @return void
     */
    public function testGroupPriceProductCollectionVolumePriceDataOfDefaultPriceTypeShouldBeSameAsInPriceData(): void
    {
        // Assign
        $priceProductFacade = $this->getPriceProductFacade();
        $defaultPriceTypeName = $priceProductFacade->getDefaultPriceTypeName();

        $expectedPriceData = 'dummy price data';

        $priceProductWithPriceData = $this->createPriceProduct(static::FAKE_CURRENCY, $defaultPriceTypeName, 100, 300);
        $priceProductWithPriceData->getMoneyValue()->setPriceData($expectedPriceData);

        $priceProductCollection = [];
        $priceProductCollection[] = $priceProductWithPriceData;
        $priceProductCollection[] = $this->createPriceProduct(static::FAKE_CURRENCY, static::PRICE_TYPE_ORIGINAL, 1100, 1300);

        // Act
        $actualResult = $priceProductFacade->groupPriceProductCollection($priceProductCollection);

        // Assert
        $this->assertSame($expectedPriceData, $actualResult[static::FAKE_CURRENCY][static::PRICE_DATA_BY_PRICE_TYPE][$defaultPriceTypeName]);
        $this->assertSame($expectedPriceData, $actualResult[static::FAKE_CURRENCY][static::PRICE_DATA]);
    }

    /**
     * @return void
     */
    public function testGetPriceModeIdentifierForBothType(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $actualResult = $priceProductFacade->getPriceModeIdentifierForBothType();

        $this->assertSame('BOTH', $actualResult);
    }

    /**
     * @return void
     */
    public function testGeneratePriceDataChecksum(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $actualResult = $priceProductFacade->generatePriceDataChecksum(['11', '22']);

        $this->assertSame('3b513d6f', $actualResult);
    }

    /**
     * @return void
     */
    public function testPersistPriceProductStore(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceProductTransfer = $this->createProductWithAmount(50, 40);
        $actualResult = $priceProductFacade->persistPriceProductStore($priceProductTransfer);

        $this->assertEquals($priceProductTransfer, $actualResult);
    }

    /**
     * @return void
     */
    public function testDeleteOrphanPriceProductStoreEntitiesNotFails(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();
        $priceProductBusinessFactory = (new PriceProductBusinessFactory());

        /** @var \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManager $priceProductEntityManagerMock */
        $priceProductEntityManagerMock = $this->getMockBuilder(PriceProductEntityManager::class)
            ->setMethods([
                'deletePriceProductStore',
            ])
            ->getMock();

        $priceProductBusinessFactory->setEntityManager($priceProductEntityManagerMock);
        $priceProductFacade->setFactory($priceProductBusinessFactory);

        $priceProductFacade->deleteOrphanPriceProductStoreEntities();
    }

    /**
     * @return void
     */
    public function testInstallNotFails(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceProductFacade->install();
    }

    /**
     * @return void
     */
    public function testFindProductAbstractPricesWithoutPriceExtractionByIdProductAbstractIn(): void
    {
        $priceProductFacade = $this->getPriceProductFacade();

        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($priceProductFacade->getDefaultPriceTypeName());

        $productConcreteTransfer = $this->tester->haveProduct();

        $prices = new ArrayObject();
        $prices[] = $this->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 10, 9, static::EUR_ISO_CODE);
        $prices[] = $this->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 11, 10, static::USD_ISO_CODE);

        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setSku($productConcreteTransfer->getAbstractSku())
            ->setPrices($prices);

        $productAbstractTransfer = $priceProductFacade->persistProductAbstractPriceCollection($productAbstractTransfer);

        $foundPrices = $priceProductFacade->findProductAbstractPricesWithoutPriceExtractionByIdProductAbstractIn([$productAbstractTransfer->getIdProductAbstract()]);

        $this->assertSame(
            count($foundPrices),
            count($prices),
        );
    }

    /**
     * @return void
     */
    public function testBuildCriteriaFromFilter(): void
    {
        $priceProductFilterTransfer = (new PriceProductFilterBuilder([
            'quantity' => rand(1, 100),
        ]))->build();

        $priceProductCriteriaTransfer = $this->getPriceProductFacade()
            ->buildCriteriaFromFilter($priceProductFilterTransfer);

        $this->assertSame($priceProductFilterTransfer->getQuantity(), $priceProductCriteriaTransfer->getQuantity());
    }

    /**
     * @param string $currencyCode
     * @param string $priceTypeName
     * @param int $grossAmount
     * @param int $netAmount
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProduct(string $currencyCode, string $priceTypeName, int $grossAmount, int $netAmount): PriceProductTransfer
    {
        return (new PriceProductTransfer())
            ->setPriceType((new PriceTypeTransfer())->setName($priceTypeName))
            ->setMoneyValue(
                (new MoneyValueTransfer())
                    ->setCurrency((new CurrencyTransfer())->setCode($currencyCode))
                    ->setGrossAmount($grossAmount)
                    ->setNetAmount($netAmount),
            );
    }

    /**
     * @param int $grossAmount
     * @param int $netAmount
     * @param string $skuAbstract
     * @param string $skuConcrete
     * @param string $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createProductWithAmount(
        int $grossAmount,
        int $netAmount,
        string $skuAbstract = '',
        string $skuConcrete = '',
        string $currencyIsoCode = ''
    ): PriceProductTransfer {
        $priceProductTransfer = (new PriceProductTransfer())
            ->setSkuProductAbstract($skuAbstract)
            ->setSkuProduct($skuConcrete);

        $config = $this->createSharedPriceProductConfig();
        $priceProductDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType($config->getPriceDimensionDefault());

        $priceProductTransfer->setPriceDimension($priceProductDimensionTransfer);

        if (!$skuAbstract || !$skuConcrete) {
            $priceProductTransfer = $this->buildProduct($priceProductTransfer);
        }

        $storeTransfer = $this->createStoreFacade()->getCurrentStore();
        $currencyTransfer = $this->getCurrencyTransfer($currencyIsoCode);

        $moneyValueTransfer = $this->createMoneyValueTransfer(
            $grossAmount,
            $netAmount,
            $storeTransfer,
            $currencyTransfer,
        );

        $priceProductTransfer->setMoneyValue($moneyValueTransfer);

        return $this->getPriceProductFacade()->createPriceForProduct($priceProductTransfer);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected function getPriceProductFacade()
    {
        return new PriceProductFacade();
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function createStoreFacade(): StoreFacadeInterface
    {
        return new StoreFacade();
    }

    /**
     * @return \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected function createCurrencyFacade(): CurrencyFacadeInterface
    {
        return new CurrencyFacade();
    }

    /**
     * @return \Spryker\Shared\PriceProduct\PriceProductConfig
     */
    protected function createSharedPriceProductConfig(): PriceProductConfig
    {
        return new PriceProductConfig();
    }

    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    protected function getPriceProductQuery(): SpyPriceProductQuery
    {
        return new SpyPriceProductQuery();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer
     * @param int $netPrice
     * @param int $grossPrice
     * @param string $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProductTransfer(
        ProductConcreteTransfer $productConcreteTransfer,
        PriceTypeTransfer $priceTypeTransfer,
        int $netPrice,
        int $grossPrice,
        string $currencyIsoCode
    ): PriceProductTransfer {
        $config = $this->createSharedPriceProductConfig();
        $priceDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType($config->getPriceDimensionDefault());

        $priceProductTransfer = (new PriceProductTransfer())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setSkuProductAbstract($productConcreteTransfer->getAbstractSku())
            ->setSkuProduct($productConcreteTransfer->getSku())
            ->setPriceTypeName($this->getPriceProductFacade()->getDefaultPriceTypeName())
            ->setPriceType($priceTypeTransfer)
            ->setPriceDimension($priceDimensionTransfer);

        $currencyTransfer = $this->createCurrencyFacade()->fromIsoCode($currencyIsoCode);
        $storeTransfer = $this->createStoreFacade()->getCurrentStore();

        $moneyValueTransfer = $this->createMoneyValueTransfer(
            $grossPrice,
            $netPrice,
            $storeTransfer,
            $currencyTransfer,
        );

        $priceProductTransfer->setMoneyValue($moneyValueTransfer);

        return $priceProductTransfer;
    }

    /**
     * @param string $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyTransfer(string $currencyIsoCode): CurrencyTransfer
    {
        if (!$currencyIsoCode) {
            return $this->createCurrencyFacade()->getDefaultCurrencyForCurrentStore();
        }

        return $this->createCurrencyFacade()->fromIsoCode($currencyIsoCode);
    }

    /**
     * @param int $grossAmount
     * @param int $netAmount
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function createMoneyValueTransfer(
        int $grossAmount,
        int $netAmount,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): MoneyValueTransfer {
        return (new MoneyValueTransfer())
            ->setNetAmount($netAmount)
            ->setGrossAmount($grossAmount)
            ->setFkStore($storeTransfer->getIdStore())
            ->setFkCurrency($currencyTransfer->getIdCurrency())
            ->setCurrency($currencyTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function buildProduct(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductTransfer
            ->setSkuProductAbstract($productConcreteTransfer->getAbstractSku())
            ->setSkuProduct($productConcreteTransfer->getSku())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete());

        return $priceProductTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    protected function createPriceProductCriteriaTransfer(): PriceProductCriteriaTransfer
    {
        $config = $this->createSharedPriceProductConfig();
        $priceProductDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType($config->getPriceDimensionDefault());

        return (new PriceProductCriteriaTransfer())
            ->setPriceDimension($priceProductDimensionTransfer);
    }

    /**
     * @return void
     */
    public function testRemovePriceProductStoreShouldDeletePriceFromDatabase(): void
    {
        // Assign
        /** @var \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface $priceProductFacade */
        $priceProductFacade = $this->getPriceProductFacade();

        $priceProductTransfer = $this->createProductWithAmount(
            100,
            90,
            '',
            '',
            static::EUR_ISO_CODE,
        );

        // Act
        $priceProductFacade->removePriceProductStore($priceProductTransfer);

        // Assert
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setCurrencyIsoCode(static::EUR_ISO_CODE)
            ->setSku($priceProductTransfer->getSkuProduct());

        $priceProduct = $priceProductFacade->findPriceProductFor($priceProductFilterTransfer);

        $this->assertNull($priceProduct, 'Price product should be removed from db');
    }

    /**
     * @return void
     */
    public function testFindPriceTypeByName(): void
    {
        $priceTypeTransfer = $this->tester->havePriceType();

        $findedPriceTypeTransfer = $this->getPriceProductFacade()->findPriceTypeByName($priceTypeTransfer->getName());

        $this->assertSame($priceTypeTransfer->getIdPriceType(), $findedPriceTypeTransfer->getIdPriceType());
    }

    /**
     * @return void
     */
    public function testGetValidPricesReturnsCollectionOfValidTransfers(): void
    {
        //Arrange
        $priceProductTransfers = [];
        for ($i = 0; $i < static::COUNT_PRODUCT_WITH_PRICES; $i++) {
            $grossPrice = rand(10, 100);
            $netPrice = $grossPrice - rand(1, 9);
            $priceProductTransfers[] = $this->createProductWithAmount(
                $grossPrice,
                $netPrice,
                '',
                '',
                static::EUR_ISO_CODE,
            );
        }
        $priceProductFilterTransfers = [];
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceProductFilterTransfers[] = (new PriceProductFilterTransfer())
                ->setCurrencyIsoCode(static::EUR_ISO_CODE)
                ->setSku($priceProductTransfer->getSkuProduct())
                ->setPriceMode(static::PRICE_MODE_GROSS);
        }

        //Act
        $resultPriceProductPrices = $this->getPriceProductFacade()->getValidPrices($priceProductFilterTransfers);

        //Assert
        $this->assertCount(count($priceProductTransfers), $resultPriceProductPrices);
    }

    /**
     * @return void
     */
    public function testGetValidPricesReturnsCollectionOfValidTransfersWithNumericSKUs(): void
    {
        //Arrange
        $priceProductTransfers = [];
        for ($i = 1; $i <= static::COUNT_PRODUCT_WITH_PRICES; $i++) {
            $grossPrice = rand(10, 100);
            $netPrice = $grossPrice - rand(1, 9);
            $skuAbstract = $i . '9000';
            $productConcreteTransfer = $this->tester->haveProduct([], ['sku' => $skuAbstract]);
            $priceProductTransfers[] = $this->createProductWithAmount(
                $grossPrice,
                $netPrice,
                $productConcreteTransfer->getAbstractSku(),
                '',
                static::EUR_ISO_CODE,
            );
        }
        $priceProductFilterTransfers = [];
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceProductFilterTransfers[] = (new PriceProductFilterTransfer())
                ->setCurrencyIsoCode(static::EUR_ISO_CODE)
                ->setSku($priceProductTransfer->getSkuProduct())
                ->setPriceMode(static::PRICE_MODE_GROSS);
        }

        //Act
        $resultPriceProductPrices = $this->getPriceProductFacade()->getValidPrices($priceProductFilterTransfers);

        //Assert
        $this->assertCount(count($priceProductTransfers), $resultPriceProductPrices);
    }

    /**
     * @return void
     */
    public function testGetValidPricesReturnsProductPricesUsingAbstractProduct(): void
    {
        //Arrange
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductTransfer = $this->createPriceProductForAbstractProduct(
            $productConcreteTransfer->getAbstractSku(),
            $productConcreteTransfer->getFkProductAbstract(),
        );

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setCurrencyIsoCode(static::EUR_ISO_CODE)
            ->setSku($productConcreteTransfer->getSku())
            ->setPriceMode(static::PRICE_MODE_GROSS);

        //Act
        $resultPriceProductTransfers = $this->getPriceProductFacade()->getValidPrices([$priceProductFilterTransfer]);

        //Assert
        $this->assertCount(1, $resultPriceProductTransfers);
        /** @var \Generated\Shared\Transfer\PriceProductTransfer $resultPriceProductTransfer */
        $resultPriceProductTransfer = $resultPriceProductTransfers[0];

        $this->assertSame($priceProductTransfer->getIdProductAbstract(), $resultPriceProductTransfer->getIdProductAbstract());
        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getNetAmount(),
            $resultPriceProductTransfer->getMoneyValue()->getNetAmount(),
        );
        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getGrossAmount(),
            $resultPriceProductTransfer->getMoneyValue()->getGrossAmount(),
        );
    }

    /**
     * @return void
     */
    public function testGetValidPricesReturnsProductPricesUsingConcreteProduct(): void
    {
        //Arrange
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductTransfer = $this->createPriceProductForConcreteProduct(
            $productConcreteTransfer->getAbstractSku(),
            $productConcreteTransfer->getIdProductConcrete(),
        );

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setCurrencyIsoCode(static::EUR_ISO_CODE)
            ->setSku($productConcreteTransfer->getSku())
            ->setPriceMode(static::PRICE_MODE_GROSS);

        //Act
        $resultPriceProductTransfers = $this->getPriceProductFacade()->getValidPrices([$priceProductFilterTransfer]);

        //Assert
        $this->assertCount(1, $resultPriceProductTransfers);
        /** @var \Generated\Shared\Transfer\PriceProductTransfer $resultPriceProductTransfer */
        $resultPriceProductTransfer = $resultPriceProductTransfers[0];

        $this->assertSame($priceProductTransfer->getIdProduct(), $resultPriceProductTransfer->getIdProduct());
        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getNetAmount(),
            $resultPriceProductTransfer->getMoneyValue()->getNetAmount(),
        );
        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getGrossAmount(),
            $resultPriceProductTransfer->getMoneyValue()->getGrossAmount(),
        );
    }

    /**
     * @return void
     */
    public function testGetValidPricesReturnsProductPricesMergingConcreteWithAbstract(): void
    {
        //Arrange
        $productConcreteTransfer = $this->tester->haveProduct();

        $this->createPriceProductForAbstractProduct(
            $productConcreteTransfer->getAbstractSku(),
            $productConcreteTransfer->getFkProductAbstract(),
        );

        $priceProductTransfer = $this->createPriceProductForConcreteProduct(
            $productConcreteTransfer->getAbstractSku(),
            $productConcreteTransfer->getIdProductConcrete(),
            $productConcreteTransfer->getSku(),
        );

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setCurrencyIsoCode(static::EUR_ISO_CODE)
            ->setSku($productConcreteTransfer->getSku())
            ->setPriceMode(static::PRICE_MODE_GROSS);

        //Act
        $resultPriceProductTransfers = $this->getPriceProductFacade()->getValidPrices([$priceProductFilterTransfer]);

        //Assert
        $this->assertCount(1, $resultPriceProductTransfers);
        /** @var \Generated\Shared\Transfer\PriceProductTransfer $resultPriceProductTransfer */
        $resultPriceProductTransfer = $resultPriceProductTransfers[0];

        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getNetAmount(),
            $resultPriceProductTransfer->getMoneyValue()->getNetAmount(),
        );
        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getGrossAmount(),
            $resultPriceProductTransfer->getMoneyValue()->getGrossAmount(),
        );
    }

    /**
     * @return void
     */
    public function testGetValidPricesReturnsProductPricesMergingConcreteWithAbstractWithDifferentCurrencies(): void
    {
        //Arrange
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductTransfer = $this->createPriceProductForAbstractProduct(
            $productConcreteTransfer->getAbstractSku(),
            $productConcreteTransfer->getFkProductAbstract(),
            static::EUR_ISO_CODE,
        );

        $this->createPriceProductForConcreteProduct(
            $productConcreteTransfer->getAbstractSku(),
            $productConcreteTransfer->getIdProductConcrete(),
            $productConcreteTransfer->getSku(),
            static::USD_ISO_CODE,
        );

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setCurrencyIsoCode(static::EUR_ISO_CODE)
            ->setSku($productConcreteTransfer->getSku())
            ->setPriceMode(static::PRICE_MODE_GROSS);

        //Act
        $resultPriceProductTransfers = $this->getPriceProductFacade()->getValidPrices([$priceProductFilterTransfer]);

        //Assert
        $this->assertCount(1, $resultPriceProductTransfers);
        /** @var \Generated\Shared\Transfer\PriceProductTransfer $resultPriceProductTransfer */
        $resultPriceProductTransfer = $resultPriceProductTransfers[0];

        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getFkCurrency(),
            $resultPriceProductTransfer->getMoneyValue()->getFkCurrency(),
        );
        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getNetAmount(),
            $resultPriceProductTransfer->getMoneyValue()->getNetAmount(),
        );
        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getGrossAmount(),
            $resultPriceProductTransfer->getMoneyValue()->getGrossAmount(),
        );
    }

    /**
     * @return void
     */
    public function testExpandProductConcreteWithPricesWillAddConcreteProductPricesWhenTheyAreDefinedForConcreteProduct(): void
    {
        //Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductTransfer = $this->createPriceProductForConcreteProduct(
            $productConcreteTransfer->getAbstractSku(),
            $productConcreteTransfer->getIdProductConcrete(),
        );

        //Act
        $productConcreteTransfer = $this->getPriceProductFacade()->expandProductConcreteWithPrices($productConcreteTransfer);

        //Assert
        $this->assertGreaterThan(0, $productConcreteTransfer->getPrices()->count());

        $resultPriceProductTransfer = $productConcreteTransfer->getPrices()->offsetGet(0);
        $this->assertSame($priceProductTransfer->getIdProduct(), $resultPriceProductTransfer->getIdProduct());
        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getNetAmount(),
            $resultPriceProductTransfer->getMoneyValue()->getNetAmount(),
        );
        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getGrossAmount(),
            $resultPriceProductTransfer->getMoneyValue()->getGrossAmount(),
        );
    }

    /**
     * @return void
     */
    public function testExpandProductConcreteWithPricesWillNotAddConcreteProductPricesWhenTheyAreDefinedOnlyForAbstractProduct(): void
    {
        //Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->createPriceProductForAbstractProduct(
            $productConcreteTransfer->getAbstractSku(),
            $productConcreteTransfer->getFkProductAbstract(),
        );

        //Act
        $productConcreteTransfer = $this->getPriceProductFacade()->expandProductConcreteWithPrices($productConcreteTransfer);

        //Assert
        $this->assertSame(0, $productConcreteTransfer->getPrices()->count());
    }

    /**
     * @return void
     */
    public function testValidatePricesIsSuccessful(): void
    {
        // Arrange
        $priceProductTransfer = $this->tester->havePriceProduct([PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku']);
        $priceProductTransfer->getMoneyValue()->setNetAmount(10);
        $priceProductTransfer->getMoneyValue()->setGrossAmount(100);

        // Act
        $validationResponseTransfer = $this->getPriceProductFacade()
            ->validatePrices(new ArrayObject([$priceProductTransfer]));

        // Assert
        $this->assertTrue($validationResponseTransfer->getIsSuccess());
        $this->assertCount(0, $validationResponseTransfer->getValidationErrors());
    }

    /**
     * @return void
     */
    public function testValidatePricesFailsValidUniqueStoreCurrencyGrossNetConstraint(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();

        $priceProductTransfer1 = $this->tester->havePriceProductAbstract($productTransfer->getFkProductAbstract(), [
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku',
        ]);
        $priceProductTransfer2 = clone $priceProductTransfer1;

        $priceProductTransfer2->getMoneyValue()->setStore($priceProductTransfer1->getMoneyValue()->getStore());
        $priceProductTransfer2->getMoneyValue()->setFkStore($priceProductTransfer1->getMoneyValue()->getFkStore());
        $priceProductTransfer2->getMoneyValue()->setCurrency($priceProductTransfer1->getMoneyValue()->getCurrency());
        $priceProductTransfer2->getMoneyValue()->setIdEntity(null);
        $priceProductTransfer2->setPriceType($priceProductTransfer1->getPriceType());
        $priceProductTransfer2->setIdProductAbstract($priceProductTransfer1->getIdProductAbstract());

        // Act
        $validationResponseTransfer = $this->getPriceProductFacade()
            ->validatePrices(new ArrayObject([$priceProductTransfer2]));

        // Assert
        $this->assertFalse($validationResponseTransfer->getIsSuccess());
        $this->assertCount(1, $validationResponseTransfer->getValidationErrors());
        $this->assertSame(
            'The set of inputs Store and Currency needs to be unique.',
            $validationResponseTransfer->getValidationErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testValidatePricesFailsValidCurrencyAssignedToStoreConstraint(): void
    {
        // Arrange
        $storeTransfer = $this->tester->getLocator()->store()->facade()->getCurrentStore();
        $currencyTransfer = $this->tester->haveCurrencyTransfer();
        $priceProductTransfer = (new PriceProductTransfer())
            ->setPriceType($this->tester->havePriceType())
            ->setMoneyValue(
                (new MoneyValueTransfer())
                    ->setStore($storeTransfer)
                    ->setCurrency((new CurrencyTransfer())->setCode(static::FAKE_CURRENCY)->setName(static::FAKE_CURRENCY))
                    ->setFkStore($storeTransfer->getIdStore())
                    ->setFkCurrency($currencyTransfer->getIdCurrency())
                    ->setGrossAmount(1)
                    ->setNetAmount(1),
            );

        // Act
        $validationResponseTransfer = $this->getPriceProductFacade()
            ->validatePrices(new ArrayObject([$priceProductTransfer]));

        // Assert
        $this->assertFalse($validationResponseTransfer->getIsSuccess());
        $this->assertCount(1, $validationResponseTransfer->getValidationErrors());
        $this->assertSame(
            sprintf(
                'Currency "%s" is not assigned to the store "%s"',
                static::FAKE_CURRENCY,
                $priceProductTransfer->getMoneyValue()->getStore()->getName(),
            ),
            $validationResponseTransfer->getValidationErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testValidateFailsValidNetAmountValue(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();
        $priceProductTransfer = $this->tester->havePriceProductAbstract($productTransfer->getFkProductAbstract(), [
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku',
        ]);
        $priceProductTransfer->getMoneyValue()->setNetAmount(-1);

        // Act
        $validationResponseTransfer = $this->getPriceProductFacade()
            ->validatePrices(new ArrayObject([$priceProductTransfer]));

        // Assert
        $this->assertFalse($validationResponseTransfer->getIsSuccess());
        $this->assertSame(
            'This value is not valid.',
            $validationResponseTransfer->getValidationErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testValidatePricesFailsValidCurrencyValue(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();
        $priceProductTransfer = $this->tester->havePriceProductAbstract($productTransfer->getFkProductAbstract(), [
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku',
        ]);
        $priceProductTransfer->getMoneyValue()->setFkCurrency(null);

        // Act
        $validationResponseTransfer = $this->getPriceProductFacade()
            ->validatePrices(new ArrayObject([$priceProductTransfer]));

        // Assert
        $validationError = $validationResponseTransfer->getValidationErrors()->offsetGet(0);
        $this->assertFalse($validationResponseTransfer->getIsSuccess());
        $this->assertSame('This field is missing.', $validationError->getMessage());
        $this->assertSame('[0][moneyValue][fkCurrency]', $validationError->getPropertyPath());
    }

    /**
     * @return void
     */
    public function testValidatePricesFailsValidStoreValue(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();
        $priceProductTransfer = $this->tester->havePriceProductAbstract($productTransfer->getFkProductAbstract(), [
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku',
        ]);
        $priceProductTransfer->getMoneyValue()->setFkStore(null);

        // Act
        $collectionValidationResponseTransfer = $this->getPriceProductFacade()
            ->validatePrices(new ArrayObject([$priceProductTransfer]));

        // Assert
        $validationError = $collectionValidationResponseTransfer->getValidationErrors()->offsetGet(0);
        $this->assertFalse($collectionValidationResponseTransfer->getIsSuccess());
        $this->assertSame('This field is missing.', $validationError->getMessage());
        $this->assertSame('[0][moneyValue][fkStore]', $validationError->getPropertyPath());
    }

    /**
     * @return void
     */
    public function testExpandWishlistItemWithPrices(): void
    {
        // Arrange
        $priceProductFacade = $this->getPriceProductFacade();

        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($priceProductFacade->getDefaultPriceTypeName());

        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductTransfer = $this->createPriceProductTransfer(
            $productConcreteTransfer,
            $priceTypeTransfer,
            10,
            9,
            static::EUR_ISO_CODE,
        );
        $priceProductTransfer = $this->tester->havePriceProduct($priceProductTransfer->toArray());

        $customer = $this->tester->haveCustomer();
        $wishlistTransfer = $this->tester->haveWishlist([
            WishlistTransfer::FK_CUSTOMER => $customer->getIdCustomer(),
        ]);
        $wishlistItem = [
            WishlistItemTransfer::FK_CUSTOMER => $customer->getIdCustomer(),
            WishlistItemTransfer::FK_WISHLIST => $wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::SKU => $productConcreteTransfer->getSku(),
        ];

        $wishlistItemTransfer = $this->tester->haveItemInWishlist($wishlistItem);
        $priceCountBefore = $wishlistItemTransfer->getPrices()->count();

        // Act
        $wishlistItemTransfer = $this->getPriceProductFacade()->expandWishlistItem($wishlistItemTransfer);

        // Assert
        $this->assertSame($priceCountBefore + 1, $wishlistItemTransfer->getPrices()->count());
        $this->assertSame($priceProductTransfer->getSkuProduct(), $wishlistItemTransfer->getSku());
    }

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();
        $this->clearProductPriceTransferCache();
    }

    /**
     * @return void
     */
    protected function clearProductPriceTransferCache(): void
    {
        $reflectionClass = new ReflectionClass(Reader::class);
        $reflectionProperty = $reflectionClass->getProperty('resolvedPriceProductTransferCollection');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue([]);
    }

    /**
     * @param string $abstractSku
     * @param int $idAbstractProduct
     * @param string|null $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProductForAbstractProduct(
        string $abstractSku,
        int $idAbstractProduct,
        ?string $currencyIsoCode = null
    ): PriceProductTransfer {
        $priceProductTransfer = (new PriceProductTransfer())
            ->setIdProductAbstract($idAbstractProduct)
            ->setSkuProductAbstract($abstractSku)
            ->setPriceDimension(
                (new PriceProductDimensionTransfer())
                    ->setType($this->createSharedPriceProductConfig()->getPriceDimensionDefault()),
            );

        $moneyValueTransfer = $this->createMoneyValueTransfer(
            rand(10, 100),
            rand(1, 9),
            $this->createStoreFacade()->getCurrentStore(),
            $this->getCurrencyTransfer($currencyIsoCode ?? static::EUR_ISO_CODE),
        );

        $priceProductTransfer->setMoneyValue($moneyValueTransfer);

        return $this->getPriceProductFacade()->createPriceForProduct($priceProductTransfer);
    }

    /**
     * @param string $abstractSku
     * @param int $idConcreteProduct
     * @param string|null $sku
     * @param string|null $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProductForConcreteProduct(
        string $abstractSku,
        int $idConcreteProduct,
        ?string $sku = null,
        ?string $currencyIsoCode = null
    ): PriceProductTransfer {
        $priceProductTransfer = (new PriceProductTransfer())
            ->setIdProduct($idConcreteProduct)
            ->setSkuProductAbstract($abstractSku)
            ->setSkuProduct($sku)
            ->setPriceDimension(
                (new PriceProductDimensionTransfer())
                    ->setType($this->createSharedPriceProductConfig()->getPriceDimensionDefault()),
            );

        $moneyValueTransfer = $this->createMoneyValueTransfer(
            rand(10, 100),
            rand(1, 9),
            $this->createStoreFacade()->getCurrentStore(),
            $this->getCurrencyTransfer($currencyIsoCode ?? static::EUR_ISO_CODE),
        );

        $priceProductTransfer->setMoneyValue($moneyValueTransfer);

        return $this->getPriceProductFacade()->createPriceForProduct($priceProductTransfer);
    }
}
