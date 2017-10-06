<?php

namespace SprykerTest\Zed\Shipment\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CurrencyTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice;
use Orm\Zed\Store\Persistence\SpyStore;
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

    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testTransformShipmentMethodEntityToShipmentMethodTransfer()
    {
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

        $actualShipmentMethodTransfer = $this->createFacade()->transformShipmentMethodEntityToShipmentMethodTransfer($shipmentMethodEntity);

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

}
