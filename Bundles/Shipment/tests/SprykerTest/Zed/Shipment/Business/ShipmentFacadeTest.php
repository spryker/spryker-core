<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business;

use ArrayObject;
use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentCarrierRequestTransfer;
use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice;
use Orm\Zed\Store\Persistence\SpyStore;
use Spryker\Shared\Shipment\ShipmentConfig;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodAvailabilityPluginInterface;
use Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodDeliveryTimePluginInterface;
use Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodPricePluginInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Business
 * @group Facade
 * @group ShipmentFacadeTest
 * Add your own group annotations below this line
 */
class ShipmentFacadeTest extends Test
{
    public const DELIVERY_TIME_PLUGIN = 'example_delivery_time_plugin';
    public const AVAILABILITY_PLUGIN = 'example_availability_plugin';
    public const PRICE_PLUGIN = 'example_price_plugin';

    public const AVAILABLE = true;
    public const NOT_AVAILABLE = false;

    public const DEFAULT_DELIVERY_TIME = 'example delivery time';
    public const DEFAULT_PLUGIN_PRICE = 1500;

    protected const VALUE_ANOTHER_EXPENSE_TYPE = 'VALUE_ANOTHER_EXPENSE_TYPE';

    protected const NOT_UNIQUE_SHIPMENT_NAME_STANDART = 'Standard';
    protected const NOT_UNIQUE_SHIPMENT_NAME_EXPRESS = 'Express';
    protected const UNIQUE_SHIPMENT_NAME = 'Example unique shipment name';
    protected const FK_SHIPMENT_CARRIER = 1;
    protected const FK_SHIPMENT_METHOD = 1;

    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected $store;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->store = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
    }

    /**
     * @return void
     */
    public function testTransformShipmentMethodEntityToShipmentMethodTransfer()
    {
        // Arrange
        $shipmentMethod = [
            'id_shipment_method' => 5,
            'name' => 'TestName',
            'is_active' => false,
        ];

        $shipmentMethodPrices = [
            [
                'id_shipment_method_price' => 3,
                'fk_shipment_method' => $shipmentMethod['id_shipment_method'],
                'fk_currency' => 2,
                'fk_store' => 4,
                'default_gross_price' => 100,
                'default_net_price' => 200,
            ],
            [
                'id_shipment_method_price' => 4,
                'fk_shipment_method' => $shipmentMethod['id_shipment_method'],
                'fk_currency' => 3,
                'fk_store' => 5,
                'default_gross_price' => 300,
                'default_net_price' => 400,
            ],
        ];

        $stores = [];
        $stores[4] = new SpyStore();
        $stores[5] = new SpyStore();
        $stores[4]->fromArray(['id_store' => 4]);
        $stores[5]->fromArray(['id_store' => 5]);

        $shipmentMethodEntity = new SpyShipmentMethod();
        $shipmentMethodEntity->fromArray($shipmentMethod);
        foreach ($shipmentMethodPrices as $shipmentMethodPrice) {
            $shipmentMethodPriceEntity = new SpyShipmentMethodPrice();
            $shipmentMethodPriceEntity->fromArray($shipmentMethodPrice);
            $shipmentMethodPriceEntity->setStore($stores[$shipmentMethodPrice['fk_store']]);
            $shipmentMethodEntity->addShipmentMethodPrice($shipmentMethodPriceEntity);
        }

        $expectedPriceCollection = new ArrayObject();
        $expectedPriceCollection->append(
            (new MoneyValueTransfer())
                ->setIdEntity(3)
                ->setFkCurrency(2)
                ->setFkStore(4)
                ->setGrossAmount(100)
                ->setNetAmount(200)
                ->setCurrency((new CurrencyTransfer())->setIdCurrency(2))
        );
        $expectedPriceCollection->append(
            (new MoneyValueTransfer())
                ->setIdEntity(4)
                ->setFkCurrency(3)
                ->setFkStore(5)
                ->setGrossAmount(300)
                ->setNetAmount(400)
                ->setCurrency((new CurrencyTransfer())->setIdCurrency(3))
        );
        $expectedShipmentMethodTransfer = (new ShipmentMethodTransfer())
            ->setIdShipmentMethod(5)
            ->setName('TestName')
            ->setIsActive(false)
            ->setPrices($expectedPriceCollection);

        $this->mockCurrencyFacade();

        // Act
        $actualShipmentMethodTransfer = $this->tester->getShipmentFacade()->transformShipmentMethodEntityToShipmentMethodTransfer($shipmentMethodEntity);

        // Assert
        $this->assertEquals(
            $expectedShipmentMethodTransfer->toArray(true),
            $actualShipmentMethodTransfer->toArray(true)
        );
    }

    /**
     * @return array
     */
    public function priceModes()
    {
        return [
            [ShipmentConstants::PRICE_MODE_GROSS],
            [ShipmentConstants::PRICE_MODE_NET],
        ];
    }

    /**
     * @dataProvider multiCurrencyPricesDataProvider
     *
     * @param string $currencyCode
     * @param string $expectedPriceResult
     *
     * @return void
     */
    public function testFindAvailableMethodByIdShouldReturnShipmentMethodById($currencyCode, $expectedPriceResult): void
    {
        $this->tester->ensureShipmentMethodTableIsEmpty();
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(ShipmentConstants::PRICE_MODE_GROSS)
            ->setCurrency((new CurrencyTransfer())->setCode($currencyCode))
            ->setStore($this->store);

        $priceList = $this->createDefaultPriceList();

        $idShipmentMethod = $this->tester->haveShipmentMethod([], [], $priceList, [$this->store->getIdStore()])->getIdShipmentMethod();

        // Act
        $shipmentMethodsTransfer = $this->tester->getShipmentFacade()->findAvailableMethodById($idShipmentMethod, $quoteTransfer);

        // Assert
        $this->assertSame($shipmentMethodsTransfer->getStoreCurrencyPrice(), $expectedPriceResult);
    }

    /**
     * @return void
     */
    public function testIsShipmentMethodActiveShouldReturnTrueWhenActive()
    {
        $this->tester->disableAllShipmentMethods();

        $priceList = $this->createDefaultPriceList();

        $idShipmentMethod = $this->tester->haveShipmentMethod([], [], $priceList)->getIdShipmentMethod();

        $isActive = $this->tester->getShipmentFacade()->isShipmentMethodActive($idShipmentMethod);

        $this->assertTrue($isActive);
    }

    /**
     * @return void
     */
    public function testIsShipmentMethodActiveShouldReturnFalseWhenInActive()
    {
        $this->tester->disableAllShipmentMethods();

        $priceList = $this->createDefaultPriceList();

        $idShipmentMethod = $this->tester->haveShipmentMethod([ShipmentMethodTransfer::IS_ACTIVE => false], [], $priceList)->getIdShipmentMethod();

        $isActive = $this->tester->getShipmentFacade()->isShipmentMethodActive($idShipmentMethod);

        $this->assertFalse($isActive);
    }

    /**
     * @return void
     */
    public function testFilterObsoleteShipmentExpensesShouldNotFilterExpensesWhenShipmentMethodIsSet(): void
    {
        // Arrange
        $calculableObjectTransfer = $this->tester->buildCalculableObjectTransfer();

        $shipmentExpenseTransfer = (new ExpenseTransfer())
            ->setType(ShipmentConfig::SHIPMENT_EXPENSE_TYPE);

        $calculableObjectTransfer->addExpense($shipmentExpenseTransfer);

        // Act
        $this->tester->getFacade()->filterObsoleteShipmentExpenses($calculableObjectTransfer);

        // Assert
        $this->assertTrue($this->hasShipmentExpense($calculableObjectTransfer, $shipmentExpenseTransfer));
    }

    /**
     * @return void
     */
    public function testFilterObsoleteShipmentExpensesShouldFilterShipmentExpensesWhenShipmentMethodIsNotSet(): void
    {
        // Arrange
        $calculableObjectTransfer = $this->tester->buildCalculableObjectTransfer([
            QuoteTransfer::SHIPMENT => null,
        ]);

        $shipmentExpenseTransfer = (new ExpenseTransfer())
            ->setType(ShipmentConfig::SHIPMENT_EXPENSE_TYPE);

        $calculableObjectTransfer->addExpense($shipmentExpenseTransfer);

        // Act
        $this->tester->getFacade()->filterObsoleteShipmentExpenses($calculableObjectTransfer);

        // Assert
        $this->assertFalse($this->hasShipmentExpense($calculableObjectTransfer, $shipmentExpenseTransfer));
    }

    /**
     * @return void
     */
    public function testFilterObsoleteShipmentExpensesShouldNotFilterNonShipmentExpensesWhenShipmentMethodIsNotSet(): void
    {
        // Arrange
        $calculableObjectTransfer = $this->tester->buildCalculableObjectTransfer([
            QuoteTransfer::SHIPMENT => null,
        ]);

        $shipmentExpenseTransfer = (new ExpenseTransfer())
            ->setType(ShipmentConfig::SHIPMENT_EXPENSE_TYPE);

        $calculableObjectTransfer->addExpense($shipmentExpenseTransfer);

        $anotherExpenseTransfer = (new ExpenseTransfer())
            ->setType(static::VALUE_ANOTHER_EXPENSE_TYPE);

        $calculableObjectTransfer->addExpense($anotherExpenseTransfer);

        // Act
        $this->tester->getFacade()->filterObsoleteShipmentExpenses($calculableObjectTransfer);

        // Assert
        $this->assertTrue($this->hasShipmentExpense($calculableObjectTransfer, $anotherExpenseTransfer));
        $this->assertFalse($this->hasShipmentExpense($calculableObjectTransfer, $shipmentExpenseTransfer));
    }

    /**
     * @return array
     */
    public function multiCurrencyPricesDataProvider(): array
    {
        return [
            ['EUR', 3100],
            ['USD', 3200],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $shipmentExpenseTransfer
     *
     * @return bool
     */
    protected function hasShipmentExpense(CalculableObjectTransfer $calculableObjectTransfer, ExpenseTransfer $shipmentExpenseTransfer): bool
    {
        return in_array($shipmentExpenseTransfer, $calculableObjectTransfer->getExpenses()->getArrayCopy(), true);
    }

    /**
     * @return void
     */
    protected function mockCurrencyFacade()
    {
        $currencyFacadeMock = $this->createMock(ShipmentToCurrencyInterface::class);
        $currencyFacadeMock
            ->expects($this->any())
            ->method('getByIdCurrency')
            ->willReturnCallback(
                function ($idCurrency) {
                    return (new CurrencyTransfer())->setIdCurrency($idCurrency);
                }
            );

        $this->tester->setDependency(ShipmentDependencyProvider::FACADE_CURRENCY, $currencyFacadeMock);
    }

    /**
     * @param string $deliveryTime
     * @param bool $isAvailable
     * @param int $price
     *
     * @return void
     */
    protected function mockShipmentMethodPluginsResult($deliveryTime, $isAvailable, $price)
    {
        $deliveryTimePlugin = $this->createMock(ShipmentMethodDeliveryTimePluginInterface::class);
        $deliveryTimePlugin->expects($this->any())->method('getTime')->willReturn($deliveryTime);

        $availabilityPlugin = $this->createMock(ShipmentMethodAvailabilityPluginInterface::class);
        $availabilityPlugin->expects($this->any())->method('isAvailable')->willReturn($isAvailable);

        $pricePlugin = $this->createMock(ShipmentMethodPricePluginInterface::class);
        $pricePlugin->expects($this->any())->method('getPrice')->willReturn($price);

        $this->tester->setDependency(ShipmentDependencyProvider::PLUGINS, [
            ShipmentDependencyProvider::AVAILABILITY_PLUGINS => [
                static::AVAILABILITY_PLUGIN => $availabilityPlugin,
            ],
            ShipmentDependencyProvider::PRICE_PLUGINS => [
                static::PRICE_PLUGIN => $pricePlugin,
            ],
            ShipmentDependencyProvider::DELIVERY_TIME_PLUGINS => [
                static::DELIVERY_TIME_PLUGIN => $deliveryTimePlugin,
            ],
        ]);
    }

    /**
     * @return array
     */
    protected function createDefaultPriceList()
    {
        $priceList = [
            $this->store->getName() => [
                'EUR' => [
                    'netAmount' => 3100,
                    'grossAmount' => 3100,
                ],
                'USD' => [
                    'netAmount' => 3200,
                    'grossAmount' => 3200,
                ],
            ],
        ];

        return $priceList;
    }

    /**
     * @return void
     */
    public function testIsNewShipmentMethodUniqueForCarrierMethodWitchExistingMethodShouldReturnFalseWhenNotUnique(): void
    {
        $shipmentExpenseTransfer = (new ShipmentMethodTransfer())
            ->setName(static::NOT_UNIQUE_SHIPMENT_NAME_STANDART)
            ->setFkShipmentCarrier(static::FK_SHIPMENT_CARRIER);

        $isShipmentMethodUniqueForCarrier = $this->tester->getShipmentFacade()
            ->isShipmentMethodUniqueForCarrier($shipmentExpenseTransfer);

        $this->assertFalse($isShipmentMethodUniqueForCarrier);
    }

    /**
     * @return void
     */
    public function testIsNewShipmentMethodUniqueForCarrierMethodWithNotExistingMethodShouldReturnTrueWhenUnique(): void
    {
        $shipmentExpenseTransfer = (new ShipmentMethodTransfer())
            ->setName(static::UNIQUE_SHIPMENT_NAME)
            ->setFkShipmentCarrier(static::FK_SHIPMENT_CARRIER);

        $isShipmentMethodUniqueForCarrier = $this->tester->getShipmentFacade()
            ->isShipmentMethodUniqueForCarrier($shipmentExpenseTransfer);

        $this->assertTrue($isShipmentMethodUniqueForCarrier);
    }

    /**
     * @return void
     */
    public function testIsShipmentMethodUniqueForCarrierMethodWitchExistingMethodShouldReturnFalseWhenNotUnique(): void
    {
        $shipmentExpenseTransfer = (new ShipmentMethodTransfer())
            ->setName(static::NOT_UNIQUE_SHIPMENT_NAME_EXPRESS)
            ->setIdShipmentMethod(static::FK_SHIPMENT_METHOD)
            ->setFkShipmentCarrier(static::FK_SHIPMENT_CARRIER);

        $isShipmentMethodUniqueForCarrier = $this->tester->getShipmentFacade()
            ->isShipmentMethodUniqueForCarrier($shipmentExpenseTransfer);

        $this->assertFalse($isShipmentMethodUniqueForCarrier);
    }

    /**
     * @return void
     */
    public function testIsShipmentMethodUniqueForCarrierMethodWithNotExistingMethodShouldReturnTrueWhenUnique(): void
    {
        $shipmentExpenseTransfer = (new ShipmentMethodTransfer())
            ->setName(static::UNIQUE_SHIPMENT_NAME)
            ->setIdShipmentMethod(static::FK_SHIPMENT_METHOD)
            ->setFkShipmentCarrier(static::FK_SHIPMENT_CARRIER);

        $isShipmentMethodUniqueForCarrier = $this->tester->getShipmentFacade()
            ->isShipmentMethodUniqueForCarrier($shipmentExpenseTransfer);

        $this->assertTrue($isShipmentMethodUniqueForCarrier);
    }

    /**
     * @return void
     */
    public function testNoRenamingShipmentMethodUniqueForCarrierMethodShouldReturnTrue(): void
    {
        $shipmentCarrierTransfer = $this->tester->haveShipmentCarrier();
        $shipmentExpenseTransfer = (new ShipmentMethodTransfer())
            ->setName(static::NOT_UNIQUE_SHIPMENT_NAME_STANDART)
            ->setIdShipmentMethod(static::FK_SHIPMENT_METHOD)
            ->setFkShipmentCarrier($shipmentCarrierTransfer->getIdShipmentCarrier());

        $isShipmentMethodUniqueForCarrier = $this->tester->getShipmentFacade()
            ->isShipmentMethodUniqueForCarrier($shipmentExpenseTransfer);

        $this->assertTrue($isShipmentMethodUniqueForCarrier);
    }

    /**
     * @return void
     */
    public function testShipmentCarrierByIdShouldReturnShipmentCarrier(): void
    {
        // Arrange
        $shipmentCarrierTransfer = $this->tester->haveShipmentCarrier();
        $shipmentCarrierRequestTransfer = (new ShipmentCarrierRequestTransfer())
            ->setIdCarrier($shipmentCarrierTransfer->getIdShipmentCarrier());

        // Act
        $shipmentCarrierTransfer = $this->tester
            ->getShipmentFacade()
            ->findShipmentCarrier($shipmentCarrierRequestTransfer);

        // Assert
        $this->assertNotNull($shipmentCarrierTransfer);
    }

    /**
     * @return void
     */
    public function testFindShipmentCarrierByNameAndExcludedCarrierIdsShouldReturnValidShipmentCarrier(): void
    {
        // Arrange
        $shipmentCarrierTransfer = $this->tester->haveShipmentCarrier();
        $shipmentCarrierTransferWithDuplicatedName = $this->tester->haveShipmentCarrier([
            ShipmentCarrierTransfer::NAME => $shipmentCarrierTransfer->getName(),
        ]);

        $shipmentCarrierRequestTransfer = (new ShipmentCarrierRequestTransfer())
            ->setCarrierName($shipmentCarrierTransfer->getName())
            ->setExcludedCarrierIds([$shipmentCarrierTransferWithDuplicatedName->getIdShipmentCarrier()]);

        // Act
        $foundShipmentCarrierTransfer = $this->tester
            ->getShipmentFacade()
            ->findShipmentCarrier($shipmentCarrierRequestTransfer);

        // Assert
        $this->assertNotNull($foundShipmentCarrierTransfer);
        $this->assertSame($foundShipmentCarrierTransfer->getIdShipmentCarrier(), $shipmentCarrierTransfer->getIdShipmentCarrier());
    }

    /**
     * @return void
     */
    public function getAvailableMethodsByShipmentShouldReturnAvailableShipmentMethods(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())->build();
        $quoteTransfer->setStore($this->store)
            ->setPriceMode(ShipmentConstants::PRICE_MODE_GROSS)
            ->setCurrency((new CurrencyTransfer())->setCode('EUR'));

        $priceList = $this->createDefaultPriceList();
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], $priceList, [$this->store->getIdStore()]);

        $quoteTransfer = $this->tester->addNewItemIntoQuoteTransfer($quoteTransfer, 'DE', $shipmentMethodTransfer);
        $quoteTransfer = $this->tester->addNewItemIntoQuoteTransfer($quoteTransfer, 'AT', $shipmentMethodTransfer);

        // Act
        $shipmentMethodsCollectionTransfers = $this->tester->getShipmentFacade()->getAvailableMethodsByShipment($quoteTransfer);
        $shipmentMethodsCollectionTransfer = current($shipmentMethodsCollectionTransfers);
        $shipmentMethodsTransfer = current($shipmentMethodsCollectionTransfer);

        // Assert
        $this->assertCount(1, $shipmentMethodsTransfer->getMethods());
    }
}
