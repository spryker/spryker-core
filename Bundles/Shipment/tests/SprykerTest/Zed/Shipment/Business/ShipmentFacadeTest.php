<?php

namespace SprykerTest\Zed\Shipment\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Orm\Zed\Currency\Persistence\SpyCurrencyQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPriceQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Orm\Zed\Store\Persistence\SpyStore;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\Price\PriceMode;
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

    const DELIVERY_TIME_PLUGIN = 'example_delivery_time_plugin';
    const AVAILABILITY_PLUGIN = 'example_availability_plugin';
    const PRICE_PLUGIN = 'example_price_plugin';

    const AVAILABLE = true;
    const NOT_AVAILABLE = false;

    const DEFAULT_DELIVERY_TIME = 'example delivery time';
    const DEFAULT_PLUGIN_PRICE = 1500;

    const ID_STORE = 1;

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

        ($shipmentMethodEntity = new SpyShipmentMethod())->fromArray($shipmentMethod);
        foreach ($shipmentMethodPrices as $shipmentMethodPrice) {
            ($shipmentMethodPriceEntity = new SpyShipmentMethodPrice())->fromArray($shipmentMethodPrice);
            $shipmentMethodPriceEntity->setStore($stores[$shipmentMethodPrice['fk_store']]);
            $shipmentMethodEntity->addShipmentMethodPrice($shipmentMethodPriceEntity);
        }

        $expectedShipmentMethodTransfer = [
            'id_shipment_method' => 5,
            'name' => 'TestName',
            'is_active' => false,
            'prices' => [
                [
                    'id_entity' => 3,
                    'fk_currency' => 2,
                    'fk_store' => 4,
                    'currency' => [
                        'id_currency' => 2,
                        'code' => null,
                        'name' => null,
                        'symbol' => null,
                        'is_default' => null,
                        'fraction_digits' => null,
                    ],
                    'gross_amount' => 100,
                    'net_amount' => 200,
                ],
                [
                    'id_entity' => 4,
                    'fk_currency' => 3,
                    'fk_store' => 5,
                    'currency' => [
                        'id_currency' => 3,
                        'code' => null,
                        'name' => null,
                        'symbol' => null,
                        'is_default' => null,
                        'fraction_digits' => null,
                    ],
                    'gross_amount' => 300,
                    'net_amount' => 400,
                ],
            ],
            'fk_sales_expense' => null,
            'fk_shipment_carrier' => null,
            'fk_tax_set' => null,
            'availability_plugin' => null,
            'price_plugin' => null,
            'delivery_time_plugin' => null,
            'carrier_name' => null,
            'tax_rate' => null,
            'delivery_time' => null,
            'store_currency_price' => null,
        ];

        $this->mockCurrencyFacade();

        // Act
        $actualShipmentMethodTransfer = $this->createFacade()->transformShipmentMethodEntityToShipmentMethodTransfer($shipmentMethodEntity);

        // Assert
        $this->assertEquals(
            $expectedShipmentMethodTransfer,
            $actualShipmentMethodTransfer->toArray(true)
        );
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
     * @return \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface
     */
    protected function createFacade()
    {
        return $this->tester->getLocator()->shipment()->facade();
    }

    /**
     * @return void
     */
    public function testGetAvailableMethodsRetrievesActiveShipmentMethods()
    {
        // Arrange
        $this->disableAllShipmentMethods();

        $shipmentMethodTransferCollection = $this->haveActiveShipmentMethods(3);
        $this->updateShipmentMethod(['is_active' => false], [$shipmentMethodTransferCollection[1]->getIdShipmentMethod()]);

        $expectedResult = [
            $shipmentMethodTransferCollection[0]->getIdShipmentMethod(),
            $shipmentMethodTransferCollection[2]->getIdShipmentMethod(),
        ];

        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(PriceMode::PRICE_MODE_GROSS)
            ->setCurrency((new CurrencyTransfer())->setCode('EUR'));

        // Act
        $shipmentMethodsTransfer = $this->createFacade()->getAvailableMethods($quoteTransfer);

        // Assert
        $this->assertEquals($expectedResult, $this->getIdShipmentMethodCollection($shipmentMethodsTransfer));
    }

    /**
     * @return void
     */
    public function testGetAvailableMethodsSetsDeliveryTimeUsingDeliveryTimePlugin()
    {
        // Arrange
        $this->disableAllShipmentMethods();

        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(PriceMode::PRICE_MODE_GROSS)
            ->setCurrency((new CurrencyTransfer())->setCode('EUR'));

        $idShipmentMethod = $this->tester
            ->haveShipmentMethod(['delivery_time_plugin' => static::DELIVERY_TIME_PLUGIN])
            ->getIdShipmentMethod();
        $expectedDeliveryTimeResult = 'example time';

        $this->setShipmentMethodPluginResult($expectedDeliveryTimeResult, static::AVAILABLE, static::DEFAULT_PLUGIN_PRICE);

        // Act
        $shipmentMethodsTransfer = $this->createFacade()->getAvailableMethods($quoteTransfer);
        $actualDeliveryTimeResult = $this->findShipmentMethod($shipmentMethodsTransfer, $idShipmentMethod)->getDeliveryTime();

        // Assert
        $this->assertEquals($expectedDeliveryTimeResult, $actualDeliveryTimeResult);
    }

    /**
     * @return void
     */
    public function testGetAvailableMethodsExcludesShipmentMethodsUsingAvailabilityPlugin()
    {
        // Arrange
        $this->disableAllShipmentMethods();

        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(PriceMode::PRICE_MODE_GROSS)
            ->setCurrency((new CurrencyTransfer())->setCode('EUR'));

        $shipmentMethodTransferCollection = $this->haveActiveShipmentMethods(3);
        $this->updateShipmentMethod(
            ['availability_plugin' => static::AVAILABILITY_PLUGIN],
            [$shipmentMethodTransferCollection[1]->getIdShipmentMethod()]
        );
        $expectedResult = [
            $shipmentMethodTransferCollection[0]->getIdShipmentMethod(),
            $shipmentMethodTransferCollection[2]->getIdShipmentMethod(),
        ];

        $this->setShipmentMethodPluginResult(static::DEFAULT_DELIVERY_TIME, static::NOT_AVAILABLE, static::DEFAULT_PLUGIN_PRICE);

        // Act
        $shipmentMethodsTransfer = $this->createFacade()->getAvailableMethods($quoteTransfer);

        // Assert
        $this->assertEquals($expectedResult, $this->getIdShipmentMethodCollection($shipmentMethodsTransfer));
    }

    /**
     * @return void
     */
    public function testGetAvailableMethodsAppliesPricePlugin()
    {
        // Arrange
        $this->disableAllShipmentMethods();

        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(PriceMode::PRICE_MODE_GROSS)
            ->setCurrency((new CurrencyTransfer())->setCode('EUR'));

        $priceList = [
            static::ID_STORE => [
                $this->getIdCurrencyByCurrencyIsoCode('EUR') => [
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
        $this->setShipmentMethodPluginResult(static::DEFAULT_DELIVERY_TIME, static::AVAILABLE, $expectedStoreCurrencyPriceResult);

        // Act
        $shipmentMethodsTransfer = $this->createFacade()->getAvailableMethods($quoteTransfer);
        $actualStoreCurrencyPriceResult = $this->findShipmentMethod($shipmentMethodsTransfer, $idShipmentMethod)->getStoreCurrencyPrice();

        // Assert
        $this->assertEquals($expectedStoreCurrencyPriceResult, $actualStoreCurrencyPriceResult);
    }

    /**
     * @return void
     */
    public function testGetAvailableMethodsExcludesShipmentMethodsWithoutPrice()
    {
        // Arrange
        $this->disableAllShipmentMethods();

        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(PriceMode::PRICE_MODE_GROSS)
            ->setCurrency((new CurrencyTransfer())->setCode('EUR'));

        $shipmentMethodTransferCollection = $this->haveActiveShipmentMethods(4);
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
        $shipmentMethodsTransfer = $this->createFacade()->getAvailableMethods($quoteTransfer);

        // Assert
        $this->assertEquals($expectedResult, $this->getIdShipmentMethodCollection($shipmentMethodsTransfer));
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
        $this->disableAllShipmentMethods();

        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode($priceMode)
            ->setCurrency((new CurrencyTransfer())->setCode('EUR'));

        $grossPrice = 5500;
        $netPrice = 6500;
        $priceList = [
            static::ID_STORE => [
                $this->getIdCurrencyByCurrencyIsoCode('EUR') => [
                    'netAmount' => $netPrice,
                    'grossAmount' => $grossPrice,
                ],
            ],
        ];
        $idShipmentMethod = $this->tester->haveShipmentMethod([], [], $priceList)->getIdShipmentMethod();

        $expectedResult = $priceMode === PriceMode::PRICE_MODE_GROSS ? $grossPrice : $netPrice;

        // Act
        $shipmentMethodsTransfer = $this->createFacade()->getAvailableMethods($quoteTransfer);
        $actualResult = $this->findShipmentMethod($shipmentMethodsTransfer, $idShipmentMethod)->getStoreCurrencyPrice();

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function priceModes()
    {
        return [
            [PriceMode::PRICE_MODE_GROSS],
            [PriceMode::PRICE_MODE_NET],
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
        $this->disableAllShipmentMethods();

        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(PriceMode::PRICE_MODE_GROSS)
            ->setCurrency((new CurrencyTransfer())->setCode($currencyCode));

        $priceList = [
            static::ID_STORE => [
                $this->getIdCurrencyByCurrencyIsoCode('EUR') => [
                    'netAmount' => 3100,
                    'grossAmount' => 3100,
                ],
                $this->getIdCurrencyByCurrencyIsoCode('USD') => [
                    'netAmount' => 3200,
                    'grossAmount' => 3200,
                ],
            ],
        ];

        $idShipmentMethod = $this->tester->haveShipmentMethod([], [], $priceList)->getIdShipmentMethod();

        // Act
        $shipmentMethodsTransfer = $this->createFacade()->getAvailableMethods($quoteTransfer);
        $actualPriceResult = $this->findShipmentMethod($shipmentMethodsTransfer, $idShipmentMethod)->getStoreCurrencyPrice();

        // Assert
        $this->assertEquals($expectedPriceResult, $actualPriceResult);
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
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     *
     * @return int[]
     */
    protected function getIdShipmentMethodCollection(ShipmentMethodsTransfer $shipmentMethodsTransfer)
    {
        $idShipmentMethodCollection = array_column($shipmentMethodsTransfer->toArray(true)['methods'], 'id_shipment_method');
        sort($idShipmentMethodCollection);

        return $idShipmentMethodCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|mixed|null
     */
    protected function findShipmentMethod(ShipmentMethodsTransfer $shipmentMethodsTransfer, $idShipmentMethod)
    {
        foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodTransfer) {
            if ($shipmentMethodTransfer->getIdShipmentMethod() === $idShipmentMethod) {
                return $shipmentMethodTransfer;
            }
        }

        return null;
    }

    /**
     * @param array $data
     * @param array|null $idFilter
     *
     * @return void
     */
    protected function updateShipmentMethod(array $data, array $idFilter = null)
    {
        $shipmentMethodQuery = SpyShipmentMethodQuery::create();

        if ($idFilter !== null) {
            $shipmentMethodQuery->filterByIdShipmentMethod($idFilter, Criteria::IN);
        }

        $shipmentMethodCollection = $shipmentMethodQuery->find();
        foreach ($shipmentMethodCollection as $shipmentMethodEntity) {
            $shipmentMethodEntity->fromArray($data);
            $shipmentMethodEntity->save();
        }
    }

    /**
     * @param string $deliveryTime
     * @param bool $isAvailable
     * @param int $price
     *
     * @return void
     */
    protected function setShipmentMethodPluginResult($deliveryTime, $isAvailable, $price)
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
     * @return void
     */
    protected function disableAllShipmentMethods()
    {
        $this->updateShipmentMethod(['is_active' => false]);
    }

    /**
     * @param int $shipmentMethodCount
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    protected function haveActiveShipmentMethods($shipmentMethodCount)
    {
        $shipmentMethodTransferCollection = [];
        for ($i = 0; $i < $shipmentMethodCount; $i++) {
            $shipmentMethodTransferCollection[$i] = $this->tester->haveShipmentMethod(['is_active' => true]);
        }

        return $shipmentMethodTransferCollection;
    }

    /**
     * @param string $currencyIsoCode
     *
     * @return int
     */
    protected function getIdCurrencyByCurrencyIsoCode($currencyIsoCode)
    {
        return SpyCurrencyQuery::create()->findOneByCode($currencyIsoCode)->getIdCurrency();
    }

}
