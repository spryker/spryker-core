<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ClickAndCollectExample\Business\Facade;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use SprykerTest\Zed\ClickAndCollectExample\ClickAndCollectExampleBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ClickAndCollectExample
 * @group Business
 * @group Facade
 * @group ReplaceQuoteItemProductOffersDeliveryTest
 * Add your own group annotations below this line
 */
class ReplaceQuoteItemProductOffersDeliveryTest extends ClickAndCollectExampleFacadeMocks
{
    /**
     * @return void
     */
    public function testReplacesWithSuitableProductOffer(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productConcreteTransfer = $this->tester->haveProduct();
        $productOfferTransfer1 = $this->tester->createReplacementProductOffer(
            $productConcreteTransfer,
            [
                ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_DELIVERY,
                ShipmentTypeTransfer::IS_ACTIVE => true,
                ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
                ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
            ],
        );

        $this->mockAvailabilityFacade($productOfferTransfer1);

        $productOfferTransfer2 = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);

        $itemTransfer = $this->tester->createItemTransfer($productConcreteTransfer)
            ->setMerchantReference($productOfferTransfer2->getMerchantReference())
            ->setQuantity(1)
            ->setProductOfferReference($productOfferTransfer2->getProductOfferReference())
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_DELIVERY));

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer);
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $quoteReplacementResponseTransfer = $this->tester->getFacade()->replaceQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer = $quoteReplacementResponseTransfer->getQuoteOrFail()->getItems()[0];
        $this->assertSame(
            $productOfferTransfer1->getProductOfferReference(),
            $quoteItemTransfer->getProductOfferReference(),
        );
        $this->assertNull($quoteItemTransfer->getGroupKey());
        $this->assertEmpty($quoteReplacementResponseTransfer->getErrors());
        $this->assertEmpty($quoteReplacementResponseTransfer->getFailedReplacementItems());
    }

    /**
     * @return void
     */
    public function testProductConcreteNotReplaced(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productConcreteTransfer = $this->tester->haveProduct();
        $servicePointTransfer = $this->tester->haveServicePoint([
            ServicePointTransfer::IS_ACTIVE => true,
            ServicePointTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        $itemTransfer = $this->tester->createItemTransfer($productConcreteTransfer)
            ->setServicePoint($servicePointTransfer)
            ->setSku($productConcreteTransfer->getSku())
            ->setQuantity(1)
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_DELIVERY));

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer);
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $quoteReplacementResponseTransfer = $this->tester->getFacade()->replaceQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer = $quoteReplacementResponseTransfer->getQuoteOrFail()->getItems()[0];
        $this->assertNotNull($quoteItemTransfer->getGroupKey());
        $this->assertNull($quoteItemTransfer->getProductOfferReference());
        $this->assertEmpty($quoteReplacementResponseTransfer->getErrors());
        $this->assertEmpty($quoteReplacementResponseTransfer->getFailedReplacementItems());
    }

    /**
     * @return void
     */
    public function testSkipsProductOfferReplacementWithAnotherShipmentType(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productConcreteTransfer = $this->tester->haveProduct();

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);

        $itemTransfer = $this->tester->createItemTransfer($productConcreteTransfer)
            ->setQuantity(1)
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_FOREIGN));

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer);
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $quoteReplacementResponseTransfer = $this->tester->getFacade()->replaceQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer = $quoteReplacementResponseTransfer->getQuoteOrFail()->getItems()[0];
        $this->assertSame(
            $productOfferTransfer->getProductOfferReference(),
            $quoteItemTransfer->getProductOfferReference(),
        );
        $this->assertNotNull($quoteItemTransfer->getGroupKey());
        $this->assertNotNull($quoteItemTransfer->getProductOfferReference());
        $this->assertEmpty($quoteReplacementResponseTransfer->getErrors());
        $this->assertEmpty($quoteReplacementResponseTransfer->getFailedReplacementItems());
    }

    /**
     * @return void
     */
    public function testFailsToReplaceProductOfferFromAnotherMerchant(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productConcreteTransfer = $this->tester->haveProduct();
        $servicePointTransfer = $this->tester->haveServicePoint([
            ServicePointTransfer::IS_ACTIVE => true,
            ServicePointTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $serviceTransfer = $this->tester->createServiceTransfer($servicePointTransfer, [
            ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_DELIVERY,
            ShipmentTypeTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->createPickupReplacementProductOffer(
            $productConcreteTransfer,
            $serviceTransfer,
            [
                ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_DELIVERY,
                ShipmentTypeTransfer::IS_ACTIVE => true,
                ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
                ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
            ],
        );

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_2,
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
        ]);

        $itemTransfer = $this->tester->createItemTransfer($productConcreteTransfer)
            ->setServicePoint($servicePointTransfer)
            ->setMerchantReference($productOfferTransfer->getMerchantReference())
            ->setQuantity(1)
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_DELIVERY));

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer);
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $quoteReplacementResponseTransfer = $this->tester->getFacade()->replaceQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer = $quoteReplacementResponseTransfer->getQuoteOrFail()->getItems()[0];
        $this->assertSame(
            $productOfferTransfer->getProductOfferReference(),
            $quoteItemTransfer->getProductOfferReference(),
        );
        $this->assertCount(1, $quoteReplacementResponseTransfer->getErrors());
        $this->assertCount(1, $quoteReplacementResponseTransfer->getFailedReplacementItems());
    }

    /**
     * @return void
     */
    public function testFailsWithLowProductOfferAvailability(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productConcreteTransfer = $this->tester->haveProduct();
        $servicePointTransfer = $this->tester->haveServicePoint([
            ServicePointTransfer::IS_ACTIVE => true,
            ServicePointTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $serviceTransfer = $this->tester->createServiceTransfer($servicePointTransfer, [
            ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_DELIVERY,
            ShipmentTypeTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->createPickupReplacementProductOffer(
            $productConcreteTransfer,
            $serviceTransfer,
            [
                ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_DELIVERY,
                ShipmentTypeTransfer::IS_ACTIVE => true,
                ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
                ProductOfferStockTransfer::QUANTITY => 1,
                ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => false,
                ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
            ],
        );

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
        ]);

        $itemTransfer = $this->tester->createItemTransfer($productConcreteTransfer)
            ->setServicePoint($servicePointTransfer)
            ->setMerchantReference($productOfferTransfer->getMerchantReference())
            ->setQuantity(5)
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_DELIVERY));

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer);
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $quoteReplacementResponseTransfer = $this->tester->getFacade()->replaceQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer = $quoteReplacementResponseTransfer->getQuoteOrFail()->getItems()[0];
        $this->assertSame(
            $productOfferTransfer->getProductOfferReference(),
            $quoteItemTransfer->getProductOfferReference(),
        );
        $this->assertCount(1, $quoteReplacementResponseTransfer->getErrors());
        $this->assertCount(1, $quoteReplacementResponseTransfer->getFailedReplacementItems());
    }

    /**
     * @return void
     */
    public function testFailsIfTargetProductOfferIsInactive(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->createReplacementProductOffer(
            $productConcreteTransfer,
            [
                ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_DELIVERY,
                ShipmentTypeTransfer::IS_ACTIVE => true,
                ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
            ],
        );

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
            ProductOfferTransfer::IS_ACTIVE => false,
        ]);

        $itemTransfer = $this->tester->createItemTransfer($productConcreteTransfer)
            ->setMerchantReference($productOfferTransfer->getMerchantReference())
            ->setQuantity(1)
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_DELIVERY));

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer);
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $quoteReplacementResponseTransfer = $this->tester->getFacade()->replaceQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer = $quoteReplacementResponseTransfer->getQuoteOrFail()->getItems()[0];
        $this->assertSame(
            $productOfferTransfer->getProductOfferReference(),
            $quoteItemTransfer->getProductOfferReference(),
        );
        $this->assertCount(1, $quoteReplacementResponseTransfer->getErrors());
        $this->assertCount(1, $quoteReplacementResponseTransfer->getFailedReplacementItems());
    }

    /**
     * @return void
     */
    public function testFailsWithProductOfferFromAnotherStore(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->createReplacementProductOffer(
            $productConcreteTransfer,
            [
                ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_DELIVERY,
                ShipmentTypeTransfer::IS_ACTIVE => true,
                ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
            ],
        );

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);

        $itemTransfer = $this->tester->createItemTransfer($productConcreteTransfer)
            ->setMerchantReference($productOfferTransfer->getMerchantReference())
            ->setQuantity(1)
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_DELIVERY));

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer);
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $quoteReplacementResponseTransfer = $this->tester->getFacade()->replaceQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer = $quoteReplacementResponseTransfer->getQuoteOrFail()->getItems()[0];
        $this->assertSame(
            $productOfferTransfer->getProductOfferReference(),
            $quoteItemTransfer->getProductOfferReference(),
        );
        $this->assertCount(1, $quoteReplacementResponseTransfer->getErrors());
        $this->assertCount(1, $quoteReplacementResponseTransfer->getFailedReplacementItems());
    }
}
