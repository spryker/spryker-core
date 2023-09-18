<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferAvailability\Communication;

use Codeception\Test\Unit;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Oms\OmsDependencyProvider;
use Spryker\Zed\ProductOfferAvailability\Communication\Plugin\TaxApp\ProductOfferAvailabilityOrderTaxAppExpanderPlugin;
use Spryker\Zed\ProductOfferStock\ProductOfferStockDependencyProvider;
use SprykerTest\Zed\ProductOfferAvailability\Helper\Plugin\ProductOfferOmsReservationReaderStrategyPluginForTesting;
use SprykerTest\Zed\ProductOfferAvailability\Helper\Plugin\StockAddressProductOfferStockExpanderPluginForTesting;
use SprykerTest\Zed\ProductOfferAvailability\ProductOfferAvailabilityCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferAvailability
 * @group Communication
 * @group ProductOfferAvailabilityOrderItemsExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductOfferAvailabilityOrderItemsExpanderPluginTest extends Unit
{
    protected ProductOfferAvailabilityCommunicationTester $tester;

    /**
     * @return void
     */
    public function testOrderItemsAreExpandedWithMerchantStockAddressWhenOrderItemsHaveProductOfferReference(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $stockTransfer = $this->tester->haveStockWithStoreAssigned($storeTransfer);
        $stockAddressTransfer = $this->tester->haveStockAddressRelatedToStock($stockTransfer);
        $productOffer = $this->tester->haveProductOfferWithStoreAssigned($storeTransfer);
        $this->tester->haveProductOfferStockWithStockAndProductOfferAssigned($stockTransfer, $productOffer);

        $this->tester->setDependency(ProductOfferStockDependencyProvider::PLUGINS_STOCK_TRANSFER_PRODUCT_OFFER_STOCK_EXPANDER, [
            new StockAddressProductOfferStockExpanderPluginForTesting($stockTransfer->getIdStock(), $stockAddressTransfer),
        ]);

        // Act
        $expandedOrderTransfer = (new ProductOfferAvailabilityOrderTaxAppExpanderPlugin())->expand(
            $this->tester->haveOrderWithOneItem($productOffer, $storeTransfer, 1),
        );

        // Assert
        $this->tester->assertExpandedOrderTransferHasOneMerchantStockAddressHydrated(
            $stockAddressTransfer,
            $expandedOrderTransfer,
            new Decimal(1),
        );
    }

    /**
     * @return void
     */
    public function testOrderItemMustBeHydratedWithMerchantStockAddressFromMerchantStockWithMostQuantityInStockIfDontExistMerchantStockSetAsNeverOutOfStock()
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();

        $stockTransferWithLessQuantity = $this->tester->haveStockWithStoreAssigned($storeTransfer);
        $stockAddressTransferWithLessQuantity = $this->tester->haveStockAddressRelatedToStock($stockTransferWithLessQuantity);

        $stockTransferWithMoreQuantity = $this->tester->haveStockWithStoreAssigned($storeTransfer);
        $stockAddressTransferWithMoreQuantity = $this->tester->haveStockAddressRelatedToStock($stockTransferWithMoreQuantity);

        $productOfferTransfer = $this->tester->haveProductOfferWithStoreAssigned($storeTransfer);
        $this->tester->haveProductOfferStockWithStockAndProductOfferAssigned($stockTransferWithLessQuantity, $productOfferTransfer, 5);
        $this->tester->haveProductOfferStockWithStockAndProductOfferAssigned($stockTransferWithMoreQuantity, $productOfferTransfer, 10);

        $this->tester->setDependency(ProductOfferStockDependencyProvider::PLUGINS_STOCK_TRANSFER_PRODUCT_OFFER_STOCK_EXPANDER, [
            new StockAddressProductOfferStockExpanderPluginForTesting($stockTransferWithLessQuantity->getIdStock(), $stockAddressTransferWithLessQuantity),
            new StockAddressProductOfferStockExpanderPluginForTesting($stockTransferWithMoreQuantity->getIdStock(), $stockAddressTransferWithMoreQuantity),
        ]);

        // Act
        $expandedOrderTransfer = (new ProductOfferAvailabilityOrderTaxAppExpanderPlugin())->expand(
            $this->tester->haveOrderWithOneItem($productOfferTransfer, $storeTransfer, 1),
        );

        // Assert
        $this->tester->assertExpandedOrderTransferHasOneMerchantStockAddressHydrated(
            $stockAddressTransferWithMoreQuantity,
            $expandedOrderTransfer,
            new Decimal(1),
        );
    }

    /**
     * @return void
     */
    public function testOrderItemMustBeHydratedWithMerchantStockAddressFromMerchantStockSetToNeverOutOfStockEvenIfAMerchantStockWithMoreQuantityExists()
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();

        $stockTransferWithLessQuantity = $this->tester->haveStockWithStoreAssigned($storeTransfer);
        $stockAddressTransferWithLessQuantity = $this->tester->haveStockAddressRelatedToStock($stockTransferWithLessQuantity);

        $stockTransferWithMoreQuantity = $this->tester->haveStockWithStoreAssigned($storeTransfer);
        $stockAddressTransferWithMoreQuantity = $this->tester->haveStockAddressRelatedToStock($stockTransferWithMoreQuantity);

        $stockTransferNeverOutOfStock = $this->tester->haveStockWithStoreAssigned($storeTransfer);
        $stockAddressTransferNeverOutOfStock = $this->tester->haveStockAddressRelatedToStock($stockTransferNeverOutOfStock);

        $productOfferTransfer = $this->tester->haveProductOfferWithStoreAssigned($storeTransfer);
        $this->tester->haveProductOfferStockWithStockAndProductOfferAssigned($stockTransferNeverOutOfStock, $productOfferTransfer, 0, true);
        $this->tester->haveProductOfferStockWithStockAndProductOfferAssigned($stockTransferWithLessQuantity, $productOfferTransfer, 5);
        $this->tester->haveProductOfferStockWithStockAndProductOfferAssigned($stockTransferWithMoreQuantity, $productOfferTransfer, 10);

        $this->tester->setDependency(ProductOfferStockDependencyProvider::PLUGINS_STOCK_TRANSFER_PRODUCT_OFFER_STOCK_EXPANDER, [
            new StockAddressProductOfferStockExpanderPluginForTesting($stockTransferWithLessQuantity->getIdStock(), $stockAddressTransferWithLessQuantity),
            new StockAddressProductOfferStockExpanderPluginForTesting($stockTransferWithMoreQuantity->getIdStock(), $stockAddressTransferWithMoreQuantity),
            new StockAddressProductOfferStockExpanderPluginForTesting($stockTransferNeverOutOfStock->getIdStock(), $stockAddressTransferNeverOutOfStock),
        ]);

        // Act
        $expandedOrderTransfer = (new ProductOfferAvailabilityOrderTaxAppExpanderPlugin())->expand(
            $this->tester->haveOrderWithOneItem($productOfferTransfer, $storeTransfer, 1),
        );

        // Assert
        $this->tester->assertExpandedOrderTransferHasOneMerchantStockAddressHydrated(
            $stockAddressTransferNeverOutOfStock,
            $expandedOrderTransfer,
            new Decimal(1),
        );
    }

    /**
     * @return void
     */
    public function testOrderItemMustBeHydratedWithMerchantStockAddressSplitByTheQuantityToShipIfMerchantStocksDontHaveEnoughQuantityInStock()
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();

        $stockTransfer1 = $this->tester->haveStockWithStoreAssigned($storeTransfer);
        $stockAddressTransfer1 = $this->tester->haveStockAddressRelatedToStock($stockTransfer1);

        $stockTransfer2 = $this->tester->haveStockWithStoreAssigned($storeTransfer);
        $stockAddressTransfer2 = $this->tester->haveStockAddressRelatedToStock($stockTransfer2);

        $stockTransfer3 = $this->tester->haveStockWithStoreAssigned($storeTransfer);
        $stockAddressTransfer3 = $this->tester->haveStockAddressRelatedToStock($stockTransfer3);

        $productOfferTransfer = $this->tester->haveProductOfferWithStoreAssigned($storeTransfer);
        $this->tester->haveProductOfferStockWithStockAndProductOfferAssigned($stockTransfer1, $productOfferTransfer, 7);
        $this->tester->haveProductOfferStockWithStockAndProductOfferAssigned($stockTransfer2, $productOfferTransfer, 5);
        $this->tester->haveProductOfferStockWithStockAndProductOfferAssigned($stockTransfer3, $productOfferTransfer, 3);

        $this->tester->setDependency(ProductOfferStockDependencyProvider::PLUGINS_STOCK_TRANSFER_PRODUCT_OFFER_STOCK_EXPANDER, [
            new StockAddressProductOfferStockExpanderPluginForTesting($stockTransfer1->getIdStock(), $stockAddressTransfer1),
            new StockAddressProductOfferStockExpanderPluginForTesting($stockTransfer2->getIdStock(), $stockAddressTransfer2),
            new StockAddressProductOfferStockExpanderPluginForTesting($stockTransfer3->getIdStock(), $stockAddressTransfer3),
        ]);

        // Act
        $expandedOrderTransfer = (new ProductOfferAvailabilityOrderTaxAppExpanderPlugin())->expand(
            $this->tester->haveOrderWithOneItem($productOfferTransfer, $storeTransfer, 10),
        );

        // Assert
        $this->tester->assertExpandedOrderTransferHasMerchantStockAddressesHydratedWithRightOrdering(
            $expandedOrderTransfer,
            [
                [
                    'stock_address' => $stockAddressTransfer1,
                    'quantity_to_ship' => new Decimal(7),
                ],
                [
                    'stock_address' => $stockAddressTransfer2,
                    'quantity_to_ship' => new Decimal(3),
                ],
            ],
        );
    }

    /**
     * "Availability" means the subtraction of stock quantity minus pending orders reserved items.
     *
     * @return void
     */
    public function testOrderItemMustCheckReservedQuantityOfProductOfferToKnowIfMerchantStockHasEnoughAvailability(): void
    {
        // Arrange
        $this->tester->setDependency(OmsDependencyProvider::PLUGINS_OMS_RESERVATION_READER_STRATEGY, [
            new ProductOfferOmsReservationReaderStrategyPluginForTesting(1),
        ]);

        $storeTransfer = $this->tester->haveStore();
        $stockTransfer = $this->tester->haveStockWithStoreAssigned($storeTransfer);
        $productOffer = $this->tester->haveProductOfferWithStoreAssigned($storeTransfer);
        $this->tester->haveProductOfferStockWithStockAndProductOfferAssigned($stockTransfer, $productOffer, 1);

        // Act
        $expandedOrderTransfer = (new ProductOfferAvailabilityOrderTaxAppExpanderPlugin())->expand(
            $this->tester->haveOrderWithOneItem($productOffer, $storeTransfer, 1),
        );

        // Assert
        $this->tester->assertExpandedOrderTransferHasNoMerchantStockAddressHydrated(
            $expandedOrderTransfer,
        );
    }

    /**
     * @return void
     */
    public function testOrderItemMustCatchExceptionAndReturnNoMerchantStockAddressIfAnyProductOfferStocksIsFound(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productOffer = $this->tester->haveProductOfferWithStoreAssigned($storeTransfer);

        // Act
        $expandedOrderTransfer = (new ProductOfferAvailabilityOrderTaxAppExpanderPlugin())->expand(
            $this->tester->haveOrderWithOneItem($productOffer, $storeTransfer, 1),
        );

        // Assert
        $this->tester->assertExpandedOrderTransferHasNoMerchantStockAddressHydrated(
            $expandedOrderTransfer,
        );
    }

    /**
     * @return void
     */
    public function testOrderItemMustHaveNoMerchantStockAddressIfMerchantStocksDontHaveEnoughAvailabilityToSendAllItems()
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();

        $stockTransfer1 = $this->tester->haveStockWithStoreAssigned($storeTransfer);
        $stockAddressTransfer1 = $this->tester->haveStockAddressRelatedToStock($stockTransfer1);

        $stockTransfer2 = $this->tester->haveStockWithStoreAssigned($storeTransfer);
        $stockAddressTransfer2 = $this->tester->haveStockAddressRelatedToStock($stockTransfer2);

        $stockTransfer3 = $this->tester->haveStockWithStoreAssigned($storeTransfer);
        $stockAddressTransfer3 = $this->tester->haveStockAddressRelatedToStock($stockTransfer3);

        $productOfferTransfer = $this->tester->haveProductOfferWithStoreAssigned($storeTransfer);
        $this->tester->haveProductOfferStockWithStockAndProductOfferAssigned($stockTransfer1, $productOfferTransfer, 5);
        $this->tester->haveProductOfferStockWithStockAndProductOfferAssigned($stockTransfer2, $productOfferTransfer, 1);
        $this->tester->haveProductOfferStockWithStockAndProductOfferAssigned($stockTransfer3, $productOfferTransfer, 1);

        $this->tester->setDependency(ProductOfferStockDependencyProvider::PLUGINS_STOCK_TRANSFER_PRODUCT_OFFER_STOCK_EXPANDER, [
            new StockAddressProductOfferStockExpanderPluginForTesting($stockTransfer1->getIdStock(), $stockAddressTransfer1),
            new StockAddressProductOfferStockExpanderPluginForTesting($stockTransfer2->getIdStock(), $stockAddressTransfer2),
            new StockAddressProductOfferStockExpanderPluginForTesting($stockTransfer3->getIdStock(), $stockAddressTransfer3),
        ]);

        // Act
        $expandedOrderTransfer = (new ProductOfferAvailabilityOrderTaxAppExpanderPlugin())->expand(
            $this->tester->haveOrderWithOneItem($productOfferTransfer, $storeTransfer, 10),
        );

        // Assert
        $this->tester->assertExpandedOrderTransferHasNoMerchantStockAddressHydrated(
            $expandedOrderTransfer,
        );
    }
}
