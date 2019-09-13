<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business;

use ArrayObject;
use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPriceQuery;
use Orm\Zed\Store\Persistence\SpyStore;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\Shipment\ShipmentConfig;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodAvailabilityPluginInterface;
use Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodDeliveryTimePluginInterface;
use Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodPricePluginInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;

/**
 * Auto-generated group annotations
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
     * @return void
     */
    public function testGetAvailableMethodsRetrievesActiveShipmentMethods()
    {
        // Arrange
        $this->tester->disableAllShipmentMethods();

        $shipmentMethodTransferCollection = $this->tester->haveActiveShipmentMethods(3);
        $this->tester->updateShipmentMethod(['is_active' => false], [$shipmentMethodTransferCollection[1]->getIdShipmentMethod()]);

        $expectedResult = [
            $shipmentMethodTransferCollection[0]->getIdShipmentMethod(),
            $shipmentMethodTransferCollection[2]->getIdShipmentMethod(),
        ];

        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(ShipmentConstants::PRICE_MODE_GROSS)
            ->setCurrency((new CurrencyTransfer())->setCode('EUR'));

        // Act
        $shipmentMethodsTransfer = $this->tester->getShipmentFacade()->getAvailableMethods($quoteTransfer);

        // Assert
        $this->assertEquals($expectedResult, $this->tester->getIdShipmentMethodCollection($shipmentMethodsTransfer));
    }

    /**
     * @return void
     */
    public function testGetAvailableMethodsSetsDeliveryTimeUsingDeliveryTimePlugin()
    {
        // Arrange
        $this->tester->disableAllShipmentMethods();

        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(ShipmentConstants::PRICE_MODE_GROSS)
            ->setCurrency((new CurrencyTransfer())->setCode('EUR'));

        $idShipmentMethod = $this->tester
            ->haveShipmentMethod(['delivery_time_plugin' => static::DELIVERY_TIME_PLUGIN])
            ->getIdShipmentMethod();
        $expectedDeliveryTimeResult = 'example time';

        $this->mockShipmentMethodPluginsResult($expectedDeliveryTimeResult, static::AVAILABLE, static::DEFAULT_PLUGIN_PRICE);

        // Act
        $shipmentMethodsTransfer = $this->tester->getShipmentFacade()->getAvailableMethods($quoteTransfer);
        $actualDeliveryTimeResult = $this->tester->findShipmentMethod($shipmentMethodsTransfer, $idShipmentMethod)->getDeliveryTime();

        // Assert
        $this->assertEquals($expectedDeliveryTimeResult, $actualDeliveryTimeResult);
    }

    /**
     * @return void
     */
    public function testGetAvailableMethodsExcludesShipmentMethodsUsingAvailabilityPlugin()
    {
        // Arrange
        $this->tester->disableAllShipmentMethods();

        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(ShipmentConstants::PRICE_MODE_GROSS)
            ->setCurrency((new CurrencyTransfer())->setCode('EUR'));

        $shipmentMethodTransferCollection = $this->tester->haveActiveShipmentMethods(3);
        $this->tester->updateShipmentMethod(
            ['availability_plugin' => static::AVAILABILITY_PLUGIN],
            [$shipmentMethodTransferCollection[1]->getIdShipmentMethod()]
        );
        $expectedResult = [
            $shipmentMethodTransferCollection[0]->getIdShipmentMethod(),
            $shipmentMethodTransferCollection[2]->getIdShipmentMethod(),
        ];

        $this->mockShipmentMethodPluginsResult(static::DEFAULT_DELIVERY_TIME, static::NOT_AVAILABLE, static::DEFAULT_PLUGIN_PRICE);

        // Act
        $shipmentMethodsTransfer = $this->tester->getShipmentFacade()->getAvailableMethods($quoteTransfer);

        // Assert
        $this->assertEquals($expectedResult, $this->tester->getIdShipmentMethodCollection($shipmentMethodsTransfer));
    }

    /**
     * @return void
     */
    public function testGetAvailableMethodsAppliesPricePlugin()
    {
        // Arrange
        $this->tester->disableAllShipmentMethods();

        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(ShipmentConstants::PRICE_MODE_GROSS)
            ->setCurrency((new CurrencyTransfer())->setCode('EUR'));

        $priceList = [
            $this->tester->getDefaultStoreName() => [
                'EUR' => [
                    'netAmount' => 1000,
                    'grossAmount' => 1000,
                ],
            ],
        ];

        $idShipmentMethod = $this->tester
            ->haveShipmentMethod(
                ['price_plugin' => static::PRICE_PLUGIN],
                [],
                $priceList
            )
            ->getIdShipmentMethod();

        $expectedStoreCurrencyPriceResult = 1234;
        $this->mockShipmentMethodPluginsResult(static::DEFAULT_DELIVERY_TIME, static::AVAILABLE, $expectedStoreCurrencyPriceResult);

        // Act
        $shipmentMethodsTransfer = $this->tester->getShipmentFacade()->getAvailableMethods($quoteTransfer);
        $actualStoreCurrencyPriceResult = $this->tester->findShipmentMethod($shipmentMethodsTransfer, $idShipmentMethod)->getStoreCurrencyPrice();

        // Assert
        $this->assertEquals($expectedStoreCurrencyPriceResult, $actualStoreCurrencyPriceResult);
    }

    /**
     * @return void
     */
    public function testGetAvailableMethodsExcludesShipmentMethodsWithoutPrice()
    {
        // Arrange
        $this->tester->disableAllShipmentMethods();

        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(ShipmentConstants::PRICE_MODE_GROSS)
            ->setCurrency((new CurrencyTransfer())->setCode('EUR'));

        $shipmentMethodTransferCollection = $this->tester->haveActiveShipmentMethods(4);
        $excludeIdShipmentCollection = [
            $shipmentMethodTransferCollection[1]->getIdShipmentMethod(),
            $shipmentMethodTransferCollection[3]->getIdShipmentMethod(),
        ];
        $expectedResult = [
            $shipmentMethodTransferCollection[0]->getIdShipmentMethod(),
            $shipmentMethodTransferCollection[2]->getIdShipmentMethod(),
        ];

        SpyShipmentMethodPriceQuery::create()
            ->filterByFkShipmentMethod($excludeIdShipmentCollection, Criteria::IN)
            ->find()
            ->delete();

        // Act
        $shipmentMethodsTransfer = $this->tester->getShipmentFacade()->getAvailableMethods($quoteTransfer);

        // Assert
        $this->assertEquals($expectedResult, $this->tester->getIdShipmentMethodCollection($shipmentMethodsTransfer));
    }

    /**
     * @dataProvider priceModes
     *
     * @param string $priceMode
     *
     * @return void
     */
    public function testGetAvailableMethodsRetrievesModeSpecificPrice($priceMode)
    {
        // Arrange
        $this->tester->disableAllShipmentMethods();

        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode($priceMode)
            ->setCurrency((new CurrencyTransfer())->setCode('EUR'));

        $grossPrice = 5500;
        $netPrice = 6500;
        $priceList = [
            $this->tester->getDefaultStoreName() => [
                'EUR' => [
                    'netAmount' => $netPrice,
                    'grossAmount' => $grossPrice,
                ],
            ],
        ];
        $idShipmentMethod = $this->tester->haveShipmentMethod([], [], $priceList)->getIdShipmentMethod();

        $expectedResult = $priceMode === ShipmentConstants::PRICE_MODE_GROSS ? $grossPrice : $netPrice;

        // Act
        $shipmentMethodsTransfer = $this->tester->getShipmentFacade()->getAvailableMethods($quoteTransfer);
        $actualResult = $this->tester->findShipmentMethod($shipmentMethodsTransfer, $idShipmentMethod)->getStoreCurrencyPrice();

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
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
     * @dataProvider multiCurrencyPrices
     *
     * @param string $currencyCode
     * @param int $expectedPriceResult
     *
     * @return void
     */
    public function testGetAvailableMethodsRetrievesStoreAndCurrencySpecificPrice($currencyCode, $expectedPriceResult)
    {
        // Arrange
        $this->tester->disableAllShipmentMethods();

        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(ShipmentConstants::PRICE_MODE_GROSS)
            ->setCurrency((new CurrencyTransfer())->setCode($currencyCode));

        $priceList = $this->createDefaultPriceList();

        $idShipmentMethod = $this->tester->haveShipmentMethod([], [], $priceList)->getIdShipmentMethod();

        // Act
        $shipmentMethodsTransfer = $this->tester->getShipmentFacade()->getAvailableMethods($quoteTransfer);
        $actualPriceResult = $this->tester->findShipmentMethod($shipmentMethodsTransfer, $idShipmentMethod)->getStoreCurrencyPrice();

        // Assert
        $this->assertSame($expectedPriceResult, $actualPriceResult);
    }

    /**
     * @dataProvider multiCurrencyPrices
     *
     * @param string $currencyCode
     * @param string $expectedPriceResult
     *
     * @return void
     */
    public function testFindAvailableMethodByIdShouldReturnShipmentMethodById($currencyCode, $expectedPriceResult)
    {
        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(ShipmentConstants::PRICE_MODE_GROSS)
            ->setCurrency((new CurrencyTransfer())->setCode($currencyCode));

        $priceList = $this->createDefaultPriceList();

        $idShipmentMethod = $this->tester->haveShipmentMethod([], [], $priceList)->getIdShipmentMethod();

        $shipmentMethodsTransfer = $this->tester->getShipmentFacade()->findAvailableMethodById($idShipmentMethod, $quoteTransfer);

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
    public function multiCurrencyPrices()
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
            $this->tester->getDefaultStoreName() => [
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
        $shipmentExpenseTransfer = (new ShipmentMethodTransfer())
            ->setName(static::NOT_UNIQUE_SHIPMENT_NAME_STANDART)
            ->setIdShipmentMethod(static::FK_SHIPMENT_METHOD)
            ->setFkShipmentCarrier(static::FK_SHIPMENT_CARRIER);

        $isShipmentMethodUniqueForCarrier = $this->tester->getShipmentFacade()
            ->isShipmentMethodUniqueForCarrier($shipmentExpenseTransfer);

        $this->assertTrue($isShipmentMethodUniqueForCarrier);
    }
}
