<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ProductOptionCriteriaTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ProductOptionTranslationTransfer;
use Generated\Shared\Transfer\ProductOptionValueStorePricesRequestTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery;
use Spryker\Shared\Price\PriceConfig;
use Spryker\Shared\ProductOption\ProductOptionConstants;
use Spryker\Zed\Currency\Business\CurrencyFacade;
use Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface;
use SprykerTest\Shared\ProductOption\Helper\ProductOptionGroupDataHelper;
use SprykerTest\Shared\Propel\Helper\InstancePoolingHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Business
 * @group Facade
 * @group ProductOptionFacadeTest
 * Add your own group annotations below this line
 */
class ProductOptionFacadeTest extends Unit
{
    use InstancePoolingHelperTrait;

    /**
     * @var string
     */
    public const DEFAULT_LOCALE_ISO_CODE = 'en_US';

    /**
     * @var int
     */
    public const DEFAULT_ID_CURRENCY = 5;

    /**
     * @var int|null
     */
    public const DEFAULT_ID_STORE = null;

    /**
     * @var int
     */
    public const DEFAULT_NET_PRICE = 100;

    /**
     * @var int
     */
    public const DEFAULT_GROSS_PRICE = 200;

    /**
     * @var string
     */
    protected const DEFAULT_STORE_NAME = 'DE';

    /**
     * @var \SprykerTest\Zed\ProductOption\ProductOptionBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->addDependencies();
    }

    /**
     * @return void
     */
    public function testGetProductAbstractOptionGroupStatusesByProductAbstractIdsShouldReturnStatusesWhenAbstractProductsHaveProductOptions(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $productOptionGroupTransfer = $this->tester->haveProductOptionGroup();

        $this->getProductOptionFacade()->addProductAbstractToProductOptionGroup(
            $productAbstractTransfer->getSku(),
            $productOptionGroupTransfer->getIdProductOptionGroup(),
        );

        // Act
        $productAbstractOptionGroupStatuses = $this->getProductOptionFacade()->getProductAbstractOptionGroupStatusesByProductAbstractIds([
            $productAbstractTransfer->getIdProductAbstract(),
        ]);

        $productAbstractOptionGroupStatusTransfer = reset($productAbstractOptionGroupStatuses);

        // Assert
        $this->assertNotNull($productAbstractOptionGroupStatusTransfer);
        $this->assertSame($productAbstractTransfer->getIdProductAbstract(), (int)$productAbstractOptionGroupStatusTransfer->getIdProductAbstract());
        $this->assertSame((bool)$productOptionGroupTransfer->getActive(), (bool)$productAbstractOptionGroupStatusTransfer->getIsActive());
        $this->assertSame($productOptionGroupTransfer->getName(), $productAbstractOptionGroupStatusTransfer->getProductOptionGroupName());
    }

    /**
     * @return void
     */
    public function testGetProductAbstractOptionGroupStatusesByProductAbstractIdsShouldReturnEmptyArrayWhenAbstractProductsDoesNotHaveProductOptions(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();

        // Act
        $productAbstractOptionGroupStatuses = $this->getProductOptionFacade()->getProductAbstractOptionGroupStatusesByProductAbstractIds([
            $productAbstractTransfer->getIdProductAbstract(),
        ]);

        // Assert
        $this->assertEmpty($productAbstractOptionGroupStatuses);
    }

    /**
     * @return void
     */
    public function testSaveProductOptionGroupShouldPersistProvidedOption(): void
    {
        $productOptionFacade = $this->getProductOptionFacade();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        $idOfProductOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupEntity = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfProductOptionGroup);

        $this->assertNotEmpty($idOfProductOptionGroup);
        $this->assertSame($productOptionGroupTransfer->getName(), $productOptionGroupEntity->getName());
        $this->assertSame($productOptionGroupTransfer->getActive(), $productOptionGroupEntity->getActive());

        $productOptionValues = $productOptionGroupEntity->getSpyProductOptionValues();
        $productOptionValueEntity = $productOptionValues[0];

        $this->assertSame($productOptionValueTransfer->getValue(), $productOptionValueEntity->getValue());
        $this->assertSame($productOptionValueTransfer->getSku(), $productOptionValueEntity->getSku());
    }

    /**
     * @return void
     */
    public function testSaveProductOptionGroupUpdatesCurrencyPrices(): void
    {
        // Assign
        $expectedNetResult = 5;
        $expectedGrossResult = 6;
        $productOptionFacade = $this->getProductOptionFacade();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);
        $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionValueTransfer->setPrices(new ArrayObject());
        $this->tester->addPrice(
            $productOptionValueTransfer,
            static::DEFAULT_ID_STORE,
            static::DEFAULT_ID_CURRENCY,
            $expectedNetResult,
            $expectedGrossResult,
        );

        // Act
        $idProductOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        // Assert
        $productOptionPriceEntity = $this->tester->getFirstProductOptionValuePriceByIdProductOptionGroup($idProductOptionGroup);
        $actualNetPrice = $productOptionPriceEntity->getNetPrice();
        $actualGrossPrice = $productOptionPriceEntity->getGrossPrice();

        $this->assertSame($expectedNetResult, $actualNetPrice);
        $this->assertSame($expectedGrossResult, $actualGrossPrice);
    }

    /**
     * @return void
     */
    public function testSaveProductOptionGroupInsertsNewCurrencyPrices(): void
    {
        // Assign
        $expectedNetResult = static::DEFAULT_NET_PRICE;
        $expectedGrossResult = static::DEFAULT_GROSS_PRICE;
        $productOptionFacade = $this->getProductOptionFacade();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        // Act
        $idProductOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        // Assert
        $productOptionPriceEntity = $this->tester->getFirstProductOptionValuePriceByIdProductOptionGroup($idProductOptionGroup);
        $actualNetPrice = $productOptionPriceEntity->getNetPrice();
        $actualGrossPrice = $productOptionPriceEntity->getGrossPrice();

        $this->assertSame($expectedNetResult, $actualNetPrice);
        $this->assertSame($expectedGrossResult, $actualGrossPrice);
    }

    /**
     * @return void
     */
    public function testSaveProductGroupOptionAndAssignProductAbstract(): void
    {
        $this->markTestSkipped('ProductAbstract not assigned');

        $productOptionFacade = $this->getProductOptionFacade();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        $productAbstractEntity = $this->tester->createProductAbstract('testingSku');

        $productOptionGroupTransfer->setProductsToBeAssigned([$productAbstractEntity->getIdProductAbstract()]);

        $idOfProductOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupEntity = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfProductOptionGroup);

        $assignedProductAbstractEntity = $productOptionGroupEntity->getSpyProductAbstracts()[0];

        $this->assertSame($assignedProductAbstractEntity->getSku(), $productAbstractEntity->getSku());
    }

    /**
     * @return void
     */
    public function testSaveProductGroupOptionAndDeAssignProductAbstract(): void
    {
        $productOptionFacade = $this->getProductOptionFacade();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        $productAbstractEntity = $this->tester->createProductAbstract('testingSku');

        $productOptionGroupTransfer->setProductsToBeAssigned([$productAbstractEntity->getIdProductAbstract()]);

        $idOfProductOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupTransfer->setProductsToBeAssigned([]);
        $productOptionGroupTransfer->setProductsToBeDeAssigned([$productAbstractEntity->getIdProductAbstract()]);

        $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupEntity = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfProductOptionGroup);

        $this->assertEmpty($productOptionGroupEntity->getSpyProductAbstracts());
    }

    /**
     * @return void
     */
    public function testSaveProductGroupOptionAndRemoveProductOptionValues(): void
    {
        $productOptionFacade = $this->getProductOptionFacade();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        $productAbstractEntity = $this->tester->createProductAbstract('testingSku');

        $productOptionGroupTransfer->setProductsToBeAssigned([$productAbstractEntity->getIdProductAbstract()]);

        $idOfProductOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupTransfer->setProductsToBeAssigned([]);
        $productOptionGroupTransfer->setProductOptionValuesToBeRemoved([$productOptionValueTransfer->getIdProductOptionValue()]);

        $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupEntity = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfProductOptionGroup);

        $this->assertEmpty($productOptionGroupEntity->getSpyProductOptionValues());
    }

    /**
     * @return void
     */
    public function testSaveProductOptionValuePersistsOption(): void
    {
        $productOptionFacade = $this->getProductOptionFacade();

        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer();

        $idProductOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionValueTransfer->setFkProductOptionGroup($idProductOptionGroup);

        $idProductOptionValue = $productOptionFacade->saveProductOptionValue($productOptionValueTransfer);

        $this->assertNotEmpty($idProductOptionGroup);

        $productOptionValueEntity = $this->tester->findOneProductOptionValueById($idProductOptionValue);

        $this->assertSame($productOptionValueTransfer->getSku(), $productOptionValueEntity->getSku());
        $this->assertSame($productOptionValueTransfer->getValue(), $productOptionValueEntity->getValue());
    }

    /**
     * @return void
     */
    public function testSaveProductOptionValueUpdatesCurrencyPrices(): void
    {
        // Assign
        $expectedNetResult = 5;
        $expectedGrossResult = 6;
        $productOptionFacade = $this->getProductOptionFacade();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionValueTransfer->setFkProductOptionGroup($this->tester->haveProductOptionGroup()->getIdProductOptionGroup());
        $productOptionFacade->saveProductOptionValue($productOptionValueTransfer);

        $productOptionValueTransfer->setPrices(new ArrayObject());
        $this->tester->addPrice(
            $productOptionValueTransfer,
            static::DEFAULT_ID_STORE,
            static::DEFAULT_ID_CURRENCY,
            $expectedNetResult,
            $expectedGrossResult,
        );

        // Act
        $idProductOptionValue = $productOptionFacade->saveProductOptionValue($productOptionValueTransfer);

        // Assert
        $productOptionPriceEntity = $this->tester->getFirstProductOptionValuePriceByIdProductOptionValue($idProductOptionValue);
        $actualNetPrice = $productOptionPriceEntity->getNetPrice();
        $actualGrossPrice = $productOptionPriceEntity->getGrossPrice();

        $this->assertSame($expectedNetResult, $actualNetPrice);
        $this->assertSame($expectedGrossResult, $actualGrossPrice);
    }

    /**
     * @return void
     */
    public function testSaveProductOptionValueInsertsNewCurrencyPrices(): void
    {
        // Assign
        $expectedNetResult = static::DEFAULT_NET_PRICE;
        $expectedGrossResult = static::DEFAULT_GROSS_PRICE;
        $productOptionFacade = $this->getProductOptionFacade();
        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionValueTransfer->setFkProductOptionGroup($this->tester->haveProductOptionGroup()->getIdProductOptionGroup());

        // Act
        $idProductOptionValue = $productOptionFacade->saveProductOptionValue($productOptionValueTransfer);

        // Assert
        $productOptionPriceEntity = $this->tester->getFirstProductOptionValuePriceByIdProductOptionValue($idProductOptionValue);
        $actualNetPrice = $productOptionPriceEntity->getNetPrice();
        $actualGrossPrice = $productOptionPriceEntity->getGrossPrice();

        $this->assertSame($expectedNetResult, $actualNetPrice);
        $this->assertSame($expectedGrossResult, $actualGrossPrice);
    }

    /**
     * @return void
     */
    public function testGetProductOptionValueShouldReturnPersistedOptionValue(): void
    {
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DEFAULT_STORE_NAME]);
        /*
         * Used in {@link \Spryker\Zed\Cart\Communication\Controller\GatewayController} context
         */
        $this->tester->addCurrentStore($storeTransfer);
        $productOptionFacade = $this->getProductOptionFacade();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $idOfPersistedOptionValue = $productOptionValueTransfer->getIdProductOptionValue();

        $productOptionTransfer = $productOptionFacade->getProductOptionValueById($idOfPersistedOptionValue);

        $this->assertSame($idOfPersistedOptionValue, $productOptionTransfer->getIdProductOptionValue());
        $this->assertSame($productOptionValueTransfer->getValue(), $productOptionTransfer->getValue());
        $this->assertSame($productOptionValueTransfer->getSku(), $productOptionTransfer->getSku());
    }

    /**
     * @return void
     */
    public function testGetProductOptionByIdShouldReturnPersistedOptionGroup(): void
    {
        $productOptionFacade = $this->getProductOptionFacade();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        $idOfPersistedOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $persistedProductOptionGroupTransfer = $productOptionFacade->getProductOptionGroupById($idOfPersistedOptionGroup);

        $this->assertNotEmpty($persistedProductOptionGroupTransfer);
        $this->assertSame($productOptionGroupTransfer->getName(), $persistedProductOptionGroupTransfer->getName());
    }

    /**
     * @return void
     */
    public function testGetProductOptionGroupByIdReturnsAllCurrencies(): void
    {
        // Assign
        $productOptionGroupTransfer = $this->tester->haveProductOptionGroupWithValues(
            [],
            [
                [
                    [],
                    [
                        [ProductOptionGroupDataHelper::CURRENCY_CODE => 'EUR'],
                        [ProductOptionGroupDataHelper::CURRENCY_CODE => 'USD'],
                    ],
                ],
            ],
        );

        $expectedCurrencies = ['EUR', 'USD'];

        // Act
        $this->enableInstancePooling(); // JoinWith needs it to populate all 3rd level joinWith records
        $actualProductGroupOption = $this->getProductOptionFacade()->getProductOptionGroupById($productOptionGroupTransfer->getIdProductOptionGroup());

        // Assert
        $actualCurrencies = array_map(
            function (MoneyValueTransfer $moneyValueTransfer) {
                return $moneyValueTransfer->getCurrency()->getCode();
            },
            $actualProductGroupOption->getProductOptionValues()[0]->getPrices()->getArrayCopy(),
        );

        $this->assertEquals($expectedCurrencies, $actualCurrencies);
    }

    /**
     * @return void
     */
    public function testGetProductOptionValueByIdReturnsRequestCurrencyAndStorePrices(): void
    {
        // Assign
        $expectedGrossAmount = 1144;
        $expectedNetAmount = 2233;

        $this->mockStoreFacadeDefaultStore();
        $this->mockCurrencyFacadeDefaultCurrency('USD');

        $productOptionGroupTransfer = $this->tester->haveProductOptionGroupWithValues(
            [],
            [
                [
                    [],
                    [
                        [
                            ProductOptionGroupDataHelper::CURRENCY_CODE => 'EUR',
                            ProductOptionGroupDataHelper::STORE_NAME => 'DE',
                        ],
                        [
                            ProductOptionGroupDataHelper::CURRENCY_CODE => 'USD',
                            ProductOptionGroupDataHelper::STORE_NAME => 'DE',
                            MoneyValueTransfer::GROSS_AMOUNT => $expectedGrossAmount,
                            MoneyValueTransfer::NET_AMOUNT => $expectedNetAmount,
                        ],
                    ],
                ],
            ],
        );

        // Act
        $this->enableInstancePooling(); // JoinWith needs it to populate all 3rd level joinWith records
        $actualProductOptionValue = $this->getProductOptionFacade()->getProductOptionValueById(
            $productOptionGroupTransfer->getProductOptionValues()[0]->getIdProductOptionValue(),
        );

        // Assert
        $this->assertSame($expectedGrossAmount, $actualProductOptionValue->getUnitGrossPrice());
        $this->assertSame($expectedNetAmount, $actualProductOptionValue->getUnitNetPrice());
    }

    /**
     * @return void
     */
    public function testGetProductOptionValueByIdReturnsDefaultStorePricesWhenPriceNotFoundForCurrentCurrency(): void
    {
        // Assign
        $expectedGrossAmount = 1144;
        $expectedNetAmount = 2233;

        $this->mockStoreFacadeDefaultStore('DE');
        $this->mockCurrencyFacadeDefaultCurrency('USD');

        $productOptionGroupTransfer = $this->tester->haveProductOptionGroupWithValues(
            [],
            [
                [
                    [],
                    [
                        [
                            ProductOptionGroupDataHelper::CURRENCY_CODE => 'EUR',
                            ProductOptionGroupDataHelper::STORE_NAME => 'DE',
                        ],
                        [
                            ProductOptionGroupDataHelper::CURRENCY_CODE => 'USD',
                            ProductOptionGroupDataHelper::STORE_NAME => null,
                            MoneyValueTransfer::GROSS_AMOUNT => $expectedGrossAmount,
                            MoneyValueTransfer::NET_AMOUNT => $expectedNetAmount,
                        ],
                    ],
                ],
            ],
        );

        // Act
        $this->enableInstancePooling(); // JoinWith needs it to populate all 3rd level joinWith records
        $actualProductOptionValue = $this->getProductOptionFacade()->getProductOptionValueById(
            $productOptionGroupTransfer->getProductOptionValues()[0]->getIdProductOptionValue(),
        );

        // Assert
        $this->assertSame($expectedGrossAmount, $actualProductOptionValue->getUnitGrossPrice());
        $this->assertSame($expectedNetAmount, $actualProductOptionValue->getUnitNetPrice());
    }

    /**
     * @return void
     */
    public function testGetProductOptionValueByIdReturnsMixedPricesWhenPricesAreNotFullyDefined(): void
    {
        // Assign
        $expectedGrossAmount = 1144;
        $expectedNetAmount = 2233;

        $this->mockStoreFacadeDefaultStore('DE');
        $this->mockCurrencyFacadeDefaultCurrency('USD');

        $productOptionGroupTransfer = $this->tester->haveProductOptionGroupWithValues(
            [],
            [
                [
                    [],
                    [
                        [
                            ProductOptionGroupDataHelper::CURRENCY_CODE => 'USD',
                            ProductOptionGroupDataHelper::STORE_NAME => 'DE',
                            MoneyValueTransfer::GROSS_AMOUNT => null,
                            MoneyValueTransfer::NET_AMOUNT => $expectedNetAmount,
                        ],
                        [
                            ProductOptionGroupDataHelper::CURRENCY_CODE => 'USD',
                            ProductOptionGroupDataHelper::STORE_NAME => null,
                            MoneyValueTransfer::GROSS_AMOUNT => $expectedGrossAmount,
                            MoneyValueTransfer::NET_AMOUNT => null,
                        ],
                    ],
                ],
            ],
        );

        // Act
        $this->enableInstancePooling(); // JoinWith needs it to populate all 3rd level joinWith records
        $actualProductOptionValue = $this->getProductOptionFacade()->getProductOptionValueById(
            $productOptionGroupTransfer->getProductOptionValues()[0]->getIdProductOptionValue(),
        );

        // Assert
        $this->assertSame($expectedGrossAmount, $actualProductOptionValue->getUnitGrossPrice());
        $this->assertSame($expectedNetAmount, $actualProductOptionValue->getUnitNetPrice());
    }

    /**
     * @return void
     */
    public function testCalculateTaxRateForProductOptionShouldSetRateToProvidedOptions(): void
    {
        $iso2Code = 'DE';
        $taxRate = 19;

        $productOptionFacade = $this->getProductOptionFacade();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        $taxSetEntity = $this->tester->createTaxSet($iso2Code, $taxRate);

        $productOptionGroupTransfer->setFkTaxSet($taxSetEntity->getIdTaxSet());

        $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setShippingAddress($this->tester->createAddressTransfer($iso2Code));

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setIdProductOptionValue($productOptionValueTransfer->getIdProductOptionValue());

        $itemTransfer = new ItemTransfer();
        $itemTransfer->addProductOption($productOptionTransfer);

        $quoteTransfer->addItem($itemTransfer);

        $productOptionFacade->calculateProductOptionTaxRate($quoteTransfer);

        $itemTransfer = $quoteTransfer->getItems()[0];
        $productOptionTransfer = $itemTransfer->getProductOptions()[0];

        $this->assertSame((float)$taxRate, $productOptionTransfer->getTaxRate());
    }

    /**
     * @return void
     */
    public function testCalculateTaxRateForProductOptionShouldSetRateToProvidedOptionsWithItemLevelShipments(): void
    {
        $iso2Code = 'DE';
        $taxRate = 19;

        $productOptionFacade = $this->getProductOptionFacade();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        $taxSetEntity = $this->tester->createTaxSet($iso2Code, $taxRate);

        $productOptionGroupTransfer->setFkTaxSet($taxSetEntity->getIdTaxSet());

        $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $quoteTransfer = new QuoteTransfer();

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setIdProductOptionValue($productOptionValueTransfer->getIdProductOptionValue());

        $shipment = new ShipmentTransfer();
        $shipment->setShippingAddress($this->tester->createAddressTransfer($iso2Code));

        $itemTransfer = new ItemTransfer();
        $itemTransfer->addProductOption($productOptionTransfer);
        $itemTransfer->setShipment($shipment);

        $quoteTransfer->addItem($itemTransfer);

        $productOptionFacade->calculateProductOptionTaxRate($quoteTransfer);

        $itemTransfer = $quoteTransfer->getItems()[0];
        $productOptionTransfer = $itemTransfer->getProductOptions()[0];

        $this->assertSame((float)$taxRate, $productOptionTransfer->getTaxRate());
    }

    /**
     * @return void
     */
    public function testToggleOptionActiveShouldActivateDeactiveOptionAcordingly(): void
    {
        $productOptionFacade = $this->getProductOptionFacade();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        $idOfPersistedOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionFacade->toggleOptionActive($idOfPersistedOptionGroup, 1);

        $productOptionGroupEntity = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfPersistedOptionGroup);

        $this->assertTrue($productOptionGroupEntity->getActive());

        $productOptionFacade->toggleOptionActive($idOfPersistedOptionGroup, 0);

        $productOptionGroupEntity = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfPersistedOptionGroup);

        $this->assertFalse($productOptionGroupEntity->getActive());
    }

    /**
     * @return void
     */
    public function testProductAbstractToProductOptionGroupShouldAddNewProductToGroup(): void
    {
        $productOptionFacade = $this->getProductOptionFacade();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        $idOfPersistedOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $testSku = 'testing-sku';
        $productAbstractEntity = $this->tester->createProductAbstract($testSku);

        $productOptionFacade->addProductAbstractToProductOptionGroup(
            $productAbstractEntity->getSku(),
            $idOfPersistedOptionGroup,
        );

        $groupProducts = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfPersistedOptionGroup);

        $assignedAbstractProducts = $groupProducts->getSpyProductAbstracts();

        $this->assertEquals($assignedAbstractProducts[0]->getSku(), $productAbstractEntity->getSku());
    }

    /**
     * @return void
     */
    public function testGetProductOptionValueStorePricesReturnsFormattedPrices(): void
    {
        // Assign
        $idCurrentStore = $this->getCurrentIdStore();
        $prices = new ArrayObject();
        $prices->append(
            (new MoneyValueTransfer())
                ->setFkCurrency(93)
                ->setFkStore($idCurrentStore)
                ->setGrossAmount(100)
                ->setNetAmount(200),
        );
        $prices->append(
            (new MoneyValueTransfer())
                ->setFkCurrency(250)
                ->setFkStore($idCurrentStore)
                ->setGrossAmount(300)
                ->setNetAmount(400),
        );
        $request = (new ProductOptionValueStorePricesRequestTransfer())->setPrices($prices);
        $expectedResult = [
            'EUR' => [
                PriceConfig::PRICE_MODE_GROSS => [ProductOptionConstants::AMOUNT => 100],
                PriceConfig::PRICE_MODE_NET => [ProductOptionConstants::AMOUNT => 200],
            ],
            'USD' => [
                PriceConfig::PRICE_MODE_GROSS => [ProductOptionConstants::AMOUNT => 300],
                PriceConfig::PRICE_MODE_NET => [ProductOptionConstants::AMOUNT => 400],
            ],
        ];

        // Act
        $actualResult = $this->getProductOptionFacade()->getProductOptionValueStorePrices($request)->getStorePrices();

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testGetProductOptionValueStorePricesReturnsDefaultStoreCurrencyWhenCurrencyNotExistsInCurrentStore(): void
    {
        // Assign
        $idCurrentStore = $this->getCurrentIdStore();
        $prices = new ArrayObject();
        $prices->append(
            (new MoneyValueTransfer())
                ->setFkCurrency(93)
                ->setFkStore($idCurrentStore)
                ->setGrossAmount(100)
                ->setNetAmount(200),
        );
        $prices->append(
            (new MoneyValueTransfer())
                ->setFkCurrency(250)
                ->setFkStore(static::DEFAULT_ID_STORE)
                ->setGrossAmount(300)
                ->setNetAmount(400),
        );
        $request = (new ProductOptionValueStorePricesRequestTransfer())->setPrices($prices);
        $expectedResult = [
            'EUR' => [
                PriceConfig::PRICE_MODE_GROSS => [ProductOptionConstants::AMOUNT => 100],
                PriceConfig::PRICE_MODE_NET => [ProductOptionConstants::AMOUNT => 200],
            ],
            'USD' => [
                PriceConfig::PRICE_MODE_GROSS => [ProductOptionConstants::AMOUNT => 300],
                PriceConfig::PRICE_MODE_NET => [ProductOptionConstants::AMOUNT => 400],
            ],
        ];

        // Act
        $actualResult = $this->getProductOptionFacade()->getProductOptionValueStorePrices($request)->getStorePrices();

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testGetProductOptionValueStorePricesReturnsADefaultStorePriceWhenACurrencyPriceIsNull(): void
    {
        // Assign
        $idCurrentStore = $this->getCurrentIdStore();
        $prices = new ArrayObject();
        $prices->append(
            (new MoneyValueTransfer())
                ->setFkCurrency(93)
                ->setFkStore($idCurrentStore)
                ->setGrossAmount(100)
                ->setNetAmount(null),
        );
        $prices->append(
            (new MoneyValueTransfer())
                ->setFkCurrency(250)
                ->setFkStore($idCurrentStore)
                ->setGrossAmount(null)
                ->setNetAmount(400),
        );
        $prices->append(
            (new MoneyValueTransfer())
                ->setFkCurrency(93)
                ->setFkStore(static::DEFAULT_ID_STORE)
                ->setGrossAmount(500)
                ->setNetAmount(600),
        );
        $prices->append(
            (new MoneyValueTransfer())
                ->setFkCurrency(250)
                ->setFkStore(static::DEFAULT_ID_STORE)
                ->setGrossAmount(700)
                ->setNetAmount(800),
        );
        $request = (new ProductOptionValueStorePricesRequestTransfer())->setPrices($prices);
        $expectedResult = [
            'EUR' => [
                PriceConfig::PRICE_MODE_GROSS => [ProductOptionConstants::AMOUNT => 100],
                PriceConfig::PRICE_MODE_NET => [ProductOptionConstants::AMOUNT => 600],
            ],
            'USD' => [
                PriceConfig::PRICE_MODE_GROSS => [ProductOptionConstants::AMOUNT => 700],
                PriceConfig::PRICE_MODE_NET => [ProductOptionConstants::AMOUNT => 400],
            ],
        ];

        // Act
        $actualResult = $this->getProductOptionFacade()->getProductOptionValueStorePrices($request)->getStorePrices();

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testGetProductOptionCollectionByProductOptionCriteriaWithOneIdReturnsCollection(): void
    {
        // Arrange
        $this->mockStoreFacadeDefaultStore();
        $productOptionFacade = $this->getProductOptionFacade();
        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionValueTransfer->setFkProductOptionGroup($this->tester->haveProductOptionGroup()->getIdProductOptionGroup());
        $idProductOptionValue = $productOptionFacade->saveProductOptionValue($productOptionValueTransfer);

        $productOptionIds = [$idProductOptionValue];
        $productOptionCriteriaTransfer = (new ProductOptionCriteriaTransfer())->setProductOptionIds($productOptionIds);

        // Act
        $actualResult = $this->getProductOptionFacade()->getProductOptionCollectionByProductOptionCriteria($productOptionCriteriaTransfer);

        // Assert
        $this->assertSame(count($productOptionIds), $actualResult->getProductOptions()->count());

        foreach ($actualResult->getProductOptions() as $productOption) {
            $this->assertTrue(in_array($productOption->getIdProductOptionValue(), $productOptionIds));
        }
    }

    /**
     * @return void
     */
    public function testGetProductOptionCollectionByProductOptionCriteriaWithTwoIdsReturnsCollection(): void
    {
        // Arrange
        $this->mockStoreFacadeDefaultStore();
        $productOptionFacade = $this->getProductOptionFacade();
        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionValueTransfer->setFkProductOptionGroup($this->tester->haveProductOptionGroup()->getIdProductOptionGroup());
        $idProductOptionValue1 = $productOptionFacade->saveProductOptionValue($productOptionValueTransfer);

        $productOptionValueTransfer = $this->createProductOptionValueTransfer('sku_for_testing_2');
        $productOptionValueTransfer->setFkProductOptionGroup($this->tester->haveProductOptionGroup()->getIdProductOptionGroup());
        $idProductOptionValue2 = $productOptionFacade->saveProductOptionValue($productOptionValueTransfer);

        $productOptionIds = [$idProductOptionValue1, $idProductOptionValue2];
        $productOptionCriteriaTransfer = (new ProductOptionCriteriaTransfer())->setProductOptionIds($productOptionIds);

        // Act
        $actualResult = $this->getProductOptionFacade()->getProductOptionCollectionByProductOptionCriteria($productOptionCriteriaTransfer);

        // Assert
        $this->assertSame(count($productOptionIds), $actualResult->getProductOptions()->count());

        foreach ($actualResult->getProductOptions() as $productOption) {
            $this->assertTrue(in_array($productOption->getIdProductOptionValue(), $productOptionIds));
        }
    }

    /**
     * @return void
     */
    public function testGetProductOptionCollectionByProductOptionCriteriaWithDeactivatedProductOptionGroupReturnsEmptyCollection(): void
    {
        // Arrange
        $this->mockStoreFacadeDefaultStore();
        $productOptionFacade = $this->getProductOptionFacade();
        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionValueTransfer->setFkProductOptionGroup($this->tester->haveProductOptionGroup([
            ProductOptionGroupTransfer::ACTIVE => false,
        ])->getIdProductOptionGroup());
        $productOptionFacade->saveProductOptionValue($productOptionValueTransfer);

        $productOptionValueTransfer = $this->createProductOptionValueTransfer('sku_for_testing_2');
        $productOptionValueTransfer->setFkProductOptionGroup($this->tester->haveProductOptionGroup()->getIdProductOptionGroup());
        $idProductOptionValue2 = $productOptionFacade->saveProductOptionValue($productOptionValueTransfer);

        $expectedProductOptionIds = [$idProductOptionValue2];
        $productOptionCriteriaTransfer = (new ProductOptionCriteriaTransfer())
            ->setProductOptionIds($expectedProductOptionIds)
            ->setProductOptionGroupIsActive(true)
            ->setProductConcreteSku(null);

        // Act
        $actualResult = $this->getProductOptionFacade()->getProductOptionCollectionByProductOptionCriteria($productOptionCriteriaTransfer);

        // Assert
        $this->assertSame(count($expectedProductOptionIds), $actualResult->getProductOptions()->count());

        foreach ($actualResult->getProductOptions() as $productOption) {
            $this->assertTrue(in_array($productOption->getIdProductOptionValue(), $expectedProductOptionIds));
        }
    }

    /**
     * @return void
     */
    public function testGetProductOptionCollectionByProductOptionCriteriaWithAssignedProductAbstractReturnsCollection(): void
    {
        // Arrange
        $this->mockStoreFacadeDefaultStore();
        $product = $this->tester->haveProduct();
        $productOptionFacade = $this->getProductOptionFacade();

        $idProductOptionGroup = $this->tester->haveProductOptionGroup([
            ProductOptionGroupTransfer::ACTIVE => true,
        ])->getIdProductOptionGroup();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();

        $productOptionValueTransfer->setFkProductOptionGroup($idProductOptionGroup);
        $idProductOptionValue = $productOptionFacade->saveProductOptionValue($productOptionValueTransfer);

        $productOptionFacade->addProductAbstractToProductOptionGroup($product->getAbstractSku(), $idProductOptionGroup);

        $productOptionIds = [$idProductOptionValue];
        $productOptionCriteriaTransfer = (new ProductOptionCriteriaTransfer())
            ->setProductOptionIds($productOptionIds)
            ->setProductOptionGroupIsActive(true)
            ->setProductConcreteSku($product->getSku());

        // Act
        $actualResult = $this->getProductOptionFacade()->getProductOptionCollectionByProductOptionCriteria($productOptionCriteriaTransfer);

        // Assert
        $this->assertCount(1, $actualResult->getProductOptions());
    }

    /**
     * @return void
     */
    public function testGetProductOptionCollectionByProductOptionCriteriaWithNonAssignedProductAbstractReturnsEmptyCollection(): void
    {
        // Arrange
        $product = $this->tester->haveProduct();
        $productOptionFacade = $this->getProductOptionFacade();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionValueTransfer->setFkProductOptionGroup($this->tester->haveProductOptionGroup([
            ProductOptionGroupTransfer::ACTIVE => true,
        ])->getIdProductOptionGroup());
        $productOptionFacade->saveProductOptionValue($productOptionValueTransfer);

        $productOptionValueTransfer = $this->createProductOptionValueTransfer('sku_for_testing_2');
        $productOptionValueTransfer->setFkProductOptionGroup($this->tester->haveProductOptionGroup()->getIdProductOptionGroup());
        $idProductOptionValue2 = $productOptionFacade->saveProductOptionValue($productOptionValueTransfer);

        $productOptionIds = [$idProductOptionValue2];
        $productOptionCriteriaTransfer = (new ProductOptionCriteriaTransfer())
            ->setProductOptionIds($productOptionIds)
            ->setProductOptionGroupIsActive(true)
            ->setProductConcreteSku($product->getSku());

        // Act
        $actualResult = $this->getProductOptionFacade()->getProductOptionCollectionByProductOptionCriteria($productOptionCriteriaTransfer);

        // Assert
        $this->assertCount(0, $actualResult->getProductOptions());
    }

    /**
     * @return void
     */
    public function testGetProductOptionCollectionByProductOptionCriteriaWithNoIdReturnsEmptyCollection(): void
    {
        // Arrange
        $productOptionIds = [];
        $productOptionCriteriaTransfer = (new ProductOptionCriteriaTransfer())->setProductOptionIds($productOptionIds);

        // Act
        $actualResult = $this->getProductOptionFacade()->getProductOptionCollectionByProductOptionCriteria($productOptionCriteriaTransfer);

        // Assert
        $this->assertSame(count($productOptionIds), $actualResult->getProductOptions()->count());
    }

    /**
     * @return int
     */
    protected function getCurrentIdStore(): int
    {
        return $this->mockStoreFacadeDefaultStore()->getIdStore();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionValueTransfer|null $productOptionValueTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    protected function createProductOptionGroupTransfer(?ProductOptionValueTransfer $productOptionValueTransfer = null): ProductOptionGroupTransfer
    {
        $productOptionGroupTransfer = new ProductOptionGroupTransfer();
        $productOptionGroupTransfer->setName('translation.key');
        $productOptionGroupTransfer->setActive(true);

        $groupNameTranslationTransfer = new ProductOptionTranslationTransfer();
        $groupNameTranslationTransfer->setKey($productOptionGroupTransfer->getName());
        $groupNameTranslationTransfer->setLocaleCode(static::DEFAULT_LOCALE_ISO_CODE);
        $groupNameTranslationTransfer->setName('Translation1');
        $productOptionGroupTransfer->addGroupNameTranslation($groupNameTranslationTransfer);

        if ($productOptionValueTransfer) {
            $productOptionTranslationTransfer = clone $groupNameTranslationTransfer;
            $productOptionTranslationTransfer->setKey($productOptionValueTransfer->getValue());
            $productOptionTranslationTransfer->setName('value translation');
            $productOptionTranslationTransfer->setLocaleCode(static::DEFAULT_LOCALE_ISO_CODE);
            $productOptionGroupTransfer->addProductOptionValueTranslation($productOptionTranslationTransfer);
        }

        return $productOptionGroupTransfer;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductOptionValueTransfer
     */
    protected function createProductOptionValueTransfer(string $sku = 'sku_for_testing'): ProductOptionValueTransfer
    {
        $productOptionValueTransfer = new ProductOptionValueTransfer();
        $productOptionValueTransfer->setValue('value.translation.key');
        $productOptionValueTransfer->setPrices(new ArrayObject());
        $productOptionValueTransfer->setSku($sku);

        $this->tester->addPrice(
            $productOptionValueTransfer,
            static::DEFAULT_ID_STORE,
            static::DEFAULT_ID_CURRENCY,
            static::DEFAULT_NET_PRICE,
            static::DEFAULT_GROSS_PRICE,
        );

        return $productOptionValueTransfer;
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface
     */
    protected function getProductOptionFacade(): ProductOptionFacadeInterface
    {
        return $this->tester->getLocator()->productOption()->facade();
    }

    /**
     * @uses StoreFacadeInterface::getCurrentStore()
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function mockStoreFacadeDefaultStore(): StoreTransfer
    {
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DEFAULT_STORE_NAME]);
        $this->tester->addCurrentStore($storeTransfer);

        return $storeTransfer;
    }

    /**
     * @uses CurrencyFacadeInterface::getCurrent()
     * @uses CurrencyFacadeInterface::fromIsoCode()
     *
     * @param string $currencyCode
     *
     * @return void
     */
    protected function mockCurrencyFacadeDefaultCurrency(string $currencyCode): void
    {
        $currencyTransfer = $this->tester->getLocator()->currency()->facade()->fromIsoCode($currencyCode);

        $currencyFacadeMock = $this->getMockBuilder(CurrencyFacade::class)
            ->onlyMethods(['getCurrent', 'fromIsoCode'])
            ->getMock();

        $currencyFacadeMock
            ->expects($this->any())
            ->method('getCurrent')
            ->willReturn($currencyTransfer);

        $currencyFacadeMock
            ->expects($this->any())
            ->method('fromIsoCode')
            ->willReturn($currencyTransfer);

        $this->tester->setDependencyCurrencyFacade($currencyFacadeMock);
    }
}
