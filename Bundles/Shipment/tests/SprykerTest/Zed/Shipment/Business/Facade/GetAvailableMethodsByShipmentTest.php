<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;
use Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodPricePluginInterface;
use SprykerTest\Zed\Shipment\ShipmentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Business
 * @group Facade
 * @group GetAvailableMethodsByShipmentTest
 * Add your own group annotations below this line
 */
class GetAvailableMethodsByShipmentTest extends Unit
{
    /**
     * @var string
     */
    protected const PRICE_PLUGIN_KEY = 'TEST_PRICE_PLUGIN';

    /**
     * @var string
     */
    protected const ITEM_SKU_1 = 'ITEM_SKU_1';

    /**
     * @var string
     */
    protected const ITEM_SKU_2 = 'ITEM_SKU_2';

    /**
     * @var array<string, int>
     */
    protected const ITEM_SHIPMENT_PRICE_MAP = [
        self::ITEM_SKU_1 => 0,
        self::ITEM_SKU_2 => 1000,
    ];

    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected ShipmentBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldCorrectlyShowPriceWhenPricePluginReturnsDifferentPriceForDifferentProducts(): void
    {
        // Arrange
        $this->tester->disableAllShipmentMethods();

        $this->tester->setDependency(ShipmentDependencyProvider::PRICE_PLUGINS, [
            static::PRICE_PLUGIN_KEY => $this->getShipmentMethodPricePlugin(static::ITEM_SHIPMENT_PRICE_MAP),
        ]);

        $storeTransfer = $this->tester->haveStore();
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod(
            [
                ShipmentMethodTransfer::IS_ACTIVE => true,
                ShipmentMethodTransfer::PRICE_PLUGIN => static::PRICE_PLUGIN_KEY,
            ],
            [],
            [],
            [$storeTransfer->getIdStoreOrFail()],
        );

        $shipment1Builder = (new ShipmentBuilder())
            ->withShippingAddress()
            ->withMethod($shipmentMethodTransfer->toArray());
        $shipment2Builder = (new ShipmentBuilder())
            ->withShippingAddress()
            ->withMethod($shipmentMethodTransfer->toArray());

        $quoteTransfer = (new QuoteBuilder())
            ->withStore($storeTransfer->toArray())
            ->withItem((new ItemBuilder([ItemTransfer::SKU => static::ITEM_SKU_1]))->withShipment($shipment1Builder))
            ->withAnotherItem((new ItemBuilder([ItemTransfer::SKU => static::ITEM_SKU_2]))->withShipment($shipment2Builder))
            ->build();

        // Act
        $shipmentMethodCollectionTransfer = $this->tester->getFacade()->getAvailableMethodsByShipment($quoteTransfer);

        // Assert
        $this->assertCount(2, $shipmentMethodCollectionTransfer->getShipmentMethods());

        $shipmentMethodsIterator = $shipmentMethodCollectionTransfer->getShipmentMethods()->getIterator();
        $this->assertShipmentMethodPrice($shipmentMethodsIterator->current(), static::ITEM_SHIPMENT_PRICE_MAP[static::ITEM_SKU_1]);
        $shipmentMethodsIterator->next();
        $this->assertShipmentMethodPrice($shipmentMethodsIterator->current(), static::ITEM_SHIPMENT_PRICE_MAP[static::ITEM_SKU_2]);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     * @param int $expectedPrice
     *
     * @return void
     */
    protected function assertShipmentMethodPrice(ShipmentMethodsTransfer $shipmentMethodsTransfer, int $expectedPrice): void
    {
        $this->assertCount(1, $shipmentMethodsTransfer->getMethods());
        $shipmentMethodTransfer = $shipmentMethodsTransfer->getMethods()->getIterator()->current();
        $this->assertSame($expectedPrice, $shipmentMethodTransfer->getStoreCurrencyPrice());
    }

    /**
     * @param array<string, int> $priceMap
     *
     * @return \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodPricePluginInterface
     */
    protected function getShipmentMethodPricePlugin(array $priceMap): ShipmentMethodPricePluginInterface
    {
        return new class ($priceMap) extends AbstractPlugin implements ShipmentMethodPricePluginInterface {
            /**
             * @var array<string, int>
             */
            protected array $priceMap;

            /**
             * @param array<string, int> $priceMap
             */
            public function __construct(array $priceMap)
            {
                $this->priceMap = $priceMap;
            }

            /**
             * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
             * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
             *
             * @return int
             */
            public function getPrice(ShipmentGroupTransfer $shipmentGroupTransfer, QuoteTransfer $quoteTransfer): int
            {
                $itemSku = $shipmentGroupTransfer->getItems()->getIterator()->current()->getSku();

                return $this->priceMap[$itemSku];
            }
        };
    }
}
