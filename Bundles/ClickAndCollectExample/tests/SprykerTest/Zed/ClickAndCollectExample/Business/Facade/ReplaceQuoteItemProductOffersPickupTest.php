<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ClickAndCollectExample\Business\Facade;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\DecimalObject\Decimal;
use SprykerTest\Zed\ClickAndCollectExample\ClickAndCollectExampleBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ClickAndCollectExample
 * @group Business
 * @group Facade
 * @group ReplaceQuoteItemProductOffersPickupTest
 * Add your own group annotations below this line
 */
class ReplaceQuoteItemProductOffersPickupTest extends ClickAndCollectExampleFacadeMocks
{
    /**
     * @return void
     */
    public function testReplacesWithSuitableProductOffer(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        /*
         * Used in the context of {@link \Spryker\Zed\ServicePointCart\Communication\Controller\GatewayController::replaceQuoteItemsAction} or RestApi so there is a current store
         */
        $this->tester->addCurrentStore($storeTransfer);
        $productConcreteTransfer = $this->tester->haveProduct();
        $servicePointTransfer = $this->tester->haveServicePoint([
            ServicePointTransfer::IS_ACTIVE => true,
            ServicePointTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $serviceTransfer = $this->tester->createServiceTransfer($servicePointTransfer, [
            ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP,
            ShipmentTypeTransfer::IS_ACTIVE => true,
        ]);

        $productOfferTransfer1 = $this->tester->createPickupReplacementProductOffer(
            $productConcreteTransfer,
            $serviceTransfer,
            [
                ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP,
                ShipmentTypeTransfer::IS_ACTIVE => true,
                ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
                ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
            ],
        );

        $this->mockAvailabilityFacade([$productOfferTransfer1]);

        $productOfferTransfer2 = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
        ]);

        $itemTransfer = $this->tester->createItemTransfer($productConcreteTransfer)
            ->setServicePoint($servicePointTransfer)
            ->setMerchantReference($productOfferTransfer2->getMerchantReference())
            ->setQuantity(1)
            ->setProductOfferReference($productOfferTransfer2->getProductOfferReference())
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP));

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer);
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $quoteReplacementResponseTransfer = $this->tester->getFacade()->replaceQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer = $quoteReplacementResponseTransfer->getQuoteOrFail()->getItems()[0];
        $this->assertSame($productOfferTransfer1->getProductOfferReference(), $quoteItemTransfer->getProductOfferReference());
        $this->assertNull($quoteItemTransfer->getGroupKey());
        $this->assertEmpty($quoteReplacementResponseTransfer->getErrors());
        $this->assertEmpty($quoteReplacementResponseTransfer->getFailedReplacementItems());
    }

    /**
     * @group test
     *
     * @return void
     */
    public function testReplacesWithSuitableProductOffersWithDifferentServicePoints(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        /*
         * Used in the context of {@link \Spryker\Zed\ServicePointCart\Communication\Controller\GatewayController::replaceQuoteItemsAction} or RestApi so there is a current store
         */
        $this->tester->addCurrentStore($storeTransfer);
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductTransfer = $this->tester->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSkuOrFail(),
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSkuOrFail(),
            PriceProductTransfer::MONEY_VALUE => [
                [MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStoreOrFail()],
            ],
        ]);
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP,
            ShipmentTypeTransfer::IS_ACTIVE => true,
        ]);
        $serviceTypeTransfer = $this->tester->haveServiceType();
        $this->tester->haveShipmentTypeServiceTypeRelation($shipmentTypeTransfer, $serviceTypeTransfer);

        $servicePointTransfer1 = $this->tester->haveServicePoint([
            ServicePointTransfer::IS_ACTIVE => true,
            ServicePointTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $serviceTransfer1 = $this->tester->haveService([
            ServiceTransfer::SERVICE_POINT => $servicePointTransfer1->toArray(),
            ServiceTransfer::SERVICE_TYPE => $serviceTypeTransfer->toArray(),
            ServiceTransfer::IS_ACTIVE => true,
        ]);

        $servicePointTransfer2 = $this->tester->haveServicePoint([
            ServicePointTransfer::IS_ACTIVE => true,
            ServicePointTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $serviceTransfer2 = $this->tester->haveService([
            ServiceTransfer::SERVICE_POINT => $servicePointTransfer2->toArray(),
            ServiceTransfer::SERVICE_TYPE => $serviceTypeTransfer->toArray(),
            ServiceTransfer::IS_ACTIVE => true,
        ]);

        $productOfferTransfer1 = $this->tester->createPickupReplacementProductOffer(
            $productConcreteTransfer,
            $serviceTransfer1,
            [
                PriceProductOfferTransfer::FK_PRICE_PRODUCT_STORE => $priceProductTransfer->getMoneyValue()->getIdEntityOrFail(),
                ProductOfferStockTransfer::QUANTITY => new Decimal(1),
                ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => false,
                ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
                ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
            ],
        );
        $productOfferTransfer2 = $this->tester->createPickupReplacementProductOffer(
            $productConcreteTransfer,
            $serviceTransfer2,
            [
                PriceProductOfferTransfer::FK_PRICE_PRODUCT_STORE => $priceProductTransfer->getMoneyValue()->getIdEntityOrFail(),
                ProductOfferStockTransfer::QUANTITY => new Decimal(1),
                ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => false,
                ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
                ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
            ],
        );
        $this->mockAvailabilityFacade([$productOfferTransfer1, $productOfferTransfer2]);

        $productOfferTransferToBeReplaced = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
        ]);

        $itemTransfer1 = $this->tester->createItemTransfer($productConcreteTransfer)
            ->setServicePoint($servicePointTransfer1)
            ->setMerchantReference($productOfferTransferToBeReplaced->getMerchantReferenceOrFail())
            ->setQuantity(1)
            ->setProductOfferReference($productOfferTransferToBeReplaced->getProductOfferReferenceOrFail())
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP));
        $itemTransfer2 = $this->tester->createItemTransfer($productConcreteTransfer)
            ->setServicePoint($servicePointTransfer2)
            ->setMerchantReference($productOfferTransferToBeReplaced->getMerchantReferenceOrFail())
            ->setQuantity(1)
            ->setProductOfferReference($productOfferTransferToBeReplaced->getProductOfferReferenceOrFail())
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP));

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer);
        $quoteTransfer
            ->addItem($itemTransfer1)
            ->addItem($itemTransfer2);

        // Act
        $quoteReplacementResponseTransfer = $this->tester->getFacade()->replaceQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer1 = $quoteReplacementResponseTransfer->getQuoteOrFail()->getItems()->offsetGet(0);
        $this->assertSame($productOfferTransfer1->getProductOfferReference(), $quoteItemTransfer1->getProductOfferReference());
        $this->assertNull($quoteItemTransfer1->getGroupKey());

        $quoteItemTransfer2 = $quoteReplacementResponseTransfer->getQuoteOrFail()->getItems()->offsetGet(1);
        $this->assertSame($productOfferTransfer2->getProductOfferReference(), $quoteItemTransfer2->getProductOfferReference());
        $this->assertNull($quoteItemTransfer2->getGroupKey());

        $this->assertEmpty($quoteReplacementResponseTransfer->getErrors());
        $this->assertEmpty($quoteReplacementResponseTransfer->getFailedReplacementItems());
    }

    /**
     * @return void
     */
    public function testReplacesWithSuitableProductOfferWhereMerchantReferenceIsNull(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        /*
         * Used in the context of {@link \Spryker\Zed\ServicePointCart\Communication\Controller\GatewayController::replaceQuoteItemsAction} or RestApi so there is a current store
         */
        $this->tester->addCurrentStore($storeTransfer);
        $productConcreteTransfer = $this->tester->haveProduct();
        $servicePointTransfer = $this->tester->haveServicePoint([
            ServicePointTransfer::IS_ACTIVE => true,
            ServicePointTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $serviceTransfer = $this->tester->createServiceTransfer($servicePointTransfer, [
            ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP,
            ShipmentTypeTransfer::IS_ACTIVE => true,
        ]);

        $productOfferTransfer1 = $this->tester->createPickupReplacementProductOffer(
            $productConcreteTransfer,
            $serviceTransfer,
            [
                ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP,
                ShipmentTypeTransfer::IS_ACTIVE => true,
                ProductOfferTransfer::MERCHANT_REFERENCE => null,
                ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
            ],
        );

        $this->mockAvailabilityFacade([$productOfferTransfer1]);

        $productOfferTransfer2 = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => null,
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
        ]);

        $itemTransfer = $this->tester->createItemTransfer($productConcreteTransfer)
            ->setServicePoint($servicePointTransfer)
            ->setMerchantReference($productOfferTransfer2->getMerchantReference())
            ->setQuantity(1)
            ->setProductOfferReference($productOfferTransfer2->getProductOfferReference())
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP));

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer);
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $quoteReplacementResponseTransfer = $this->tester->getFacade()->replaceQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer = $quoteReplacementResponseTransfer->getQuoteOrFail()->getItems()[0];
        $this->assertSame($productOfferTransfer1->getProductOfferReference(), $quoteItemTransfer->getProductOfferReference());
        $this->assertNull($quoteItemTransfer->getGroupKey());
        $this->assertEmpty($quoteReplacementResponseTransfer->getErrors());
        $this->assertEmpty($quoteReplacementResponseTransfer->getFailedReplacementItems());
    }

    /**
     * @return void
     */
    public function testShouldNotReplaceWithTheSameOffer(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        /*
         * Used in the context of {@link \Spryker\Zed\ServicePointCart\Communication\Controller\GatewayController::replaceQuoteItemsAction} or RestApi so there is a current store
         */
        $this->tester->addCurrentStore($storeTransfer);
        $productConcreteTransfer = $this->tester->haveProduct();
        $servicePointTransfer = $this->tester->haveServicePoint([
            ServicePointTransfer::IS_ACTIVE => true,
            ServicePointTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $serviceTransfer = $this->tester->createServiceTransfer($servicePointTransfer, [
            ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP,
            ShipmentTypeTransfer::IS_ACTIVE => true,
        ]);

        $productOfferTransfer = $this->tester->createPickupReplacementProductOffer(
            $productConcreteTransfer,
            $serviceTransfer,
            [
                ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP,
                ShipmentTypeTransfer::IS_ACTIVE => true,
                ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
                ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
            ],
        );

        $this->mockAvailabilityFacade([$productOfferTransfer]);

        $itemTransfer = $this->tester->createItemTransfer($productConcreteTransfer)
            ->setServicePoint($servicePointTransfer)
            ->setMerchantReference($productOfferTransfer->getMerchantReference())
            ->setQuantity(1)
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP));

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer);
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $quoteReplacementResponseTransfer = $this->tester->getFacade()->replaceQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer = $quoteReplacementResponseTransfer->getQuoteOrFail()->getItems()[0];
        $this->assertSame($productOfferTransfer->getProductOfferReference(), $quoteItemTransfer->getProductOfferReference());
        $this->assertNotNull($quoteItemTransfer->getGroupKey());
        $this->assertEmpty($quoteReplacementResponseTransfer->getErrors());
        $this->assertEmpty($quoteReplacementResponseTransfer->getFailedReplacementItems());
    }

    /**
     * @return void
     */
    public function testFailsWithSuitableProductOfferAndNotSuitableServicePoint(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        /*
         * Used in the context of {@link \Spryker\Zed\ServicePointCart\Communication\Controller\GatewayController::replaceQuoteItemsAction} or RestApi so there is a current store
         */
        $this->tester->addCurrentStore($storeTransfer);
        $productConcreteTransfer = $this->tester->haveProduct();
        $servicePointTransfer = $this->tester->haveServicePoint([
            ServicePointTransfer::IS_ACTIVE => true,
            ServicePointTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $serviceTransfer = $this->tester->createServiceTransfer($servicePointTransfer, [
            ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP,
            ShipmentTypeTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->createPickupReplacementProductOffer(
            $productConcreteTransfer,
            $serviceTransfer,
            [
                ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP,
                ShipmentTypeTransfer::IS_ACTIVE => true,
                ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
                ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
            ],
        );

        $productOfferTransfer2 = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
        ]);

        $servicePointTransfer = $this->tester->haveServicePoint([
            ServicePointTransfer::IS_ACTIVE => true,
            ServicePointTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        $itemTransfer = $this->tester->createItemTransfer($productConcreteTransfer)
            ->setServicePoint($servicePointTransfer)
            ->setMerchantReference($productOfferTransfer2->getMerchantReference())
            ->setQuantity(1)
            ->setProductOfferReference($productOfferTransfer2->getProductOfferReference())
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP));

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer);
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $quoteReplacementResponseTransfer = $this->tester->getFacade()->replaceQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer = $quoteReplacementResponseTransfer->getQuoteOrFail()->getItems()[0];
        $this->assertNotNull($quoteItemTransfer->getGroupKey());
        $this->assertNotNull($quoteItemTransfer->getProductOfferReference());
        $this->assertCount(1, $quoteReplacementResponseTransfer->getErrors());
        $this->assertCount(1, $quoteReplacementResponseTransfer->getFailedReplacementItems());
    }

    /**
     * @return void
     */
    public function testFailsToReplaceProductOfferFromAnotherMerchant(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        /*
         * Used in the context of {@link \Spryker\Zed\ServicePointCart\Communication\Controller\GatewayController::replaceQuoteItemsAction} or RestApi so there is a current store
         */
        $this->tester->addCurrentStore($storeTransfer);
        $productConcreteTransfer = $this->tester->haveProduct();
        $servicePointTransfer = $this->tester->haveServicePoint([
            ServicePointTransfer::IS_ACTIVE => true,
            ServicePointTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $serviceTransfer = $this->tester->createServiceTransfer($servicePointTransfer, [
            ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP,
            ShipmentTypeTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->createPickupReplacementProductOffer(
            $productConcreteTransfer,
            $serviceTransfer,
            [
                ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP,
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
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP));

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
        /*
         * Used in the context of {@link \Spryker\Zed\ServicePointCart\Communication\Controller\GatewayController::replaceQuoteItemsAction} or RestApi so there is a current store
         */
        $this->tester->addCurrentStore($storeTransfer);
        $productConcreteTransfer = $this->tester->haveProduct();
        $servicePointTransfer = $this->tester->haveServicePoint([
            ServicePointTransfer::IS_ACTIVE => true,
            ServicePointTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $serviceTransfer = $this->tester->createServiceTransfer($servicePointTransfer, [
            ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP,
            ShipmentTypeTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->createPickupReplacementProductOffer(
            $productConcreteTransfer,
            $serviceTransfer,
            [
                ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP,
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
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP));

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
    public function testFailsWithTwoItemsWithSameProductOfferAndLowProductOfferAvailability(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        /*
         * Used in the context of {@link \Spryker\Zed\ServicePointCart\Communication\Controller\GatewayController::replaceQuoteItemsAction} or RestApi so there is a current store
         */
        $this->tester->addCurrentStore($storeTransfer);
        $productConcreteTransfer = $this->tester->haveProduct();
        $servicePointTransfer = $this->tester->haveServicePoint([
            ServicePointTransfer::IS_ACTIVE => true,
            ServicePointTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $serviceTransfer = $this->tester->createServiceTransfer($servicePointTransfer, [
            ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP,
            ShipmentTypeTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->createPickupReplacementProductOffer(
            $productConcreteTransfer,
            $serviceTransfer,
            [
                ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP,
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

        $itemTransfer1 = $this->tester->createItemTransfer($productConcreteTransfer)
            ->setServicePoint($servicePointTransfer)
            ->setMerchantReference($productOfferTransfer->getMerchantReference())
            ->setQuantity(1)
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP));
        $itemTransfer2 = $this->tester->createItemTransfer($productConcreteTransfer)
            ->setServicePoint($servicePointTransfer)
            ->setMerchantReference($productOfferTransfer->getMerchantReference())
            ->setQuantity(1)
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP));

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer);
        $quoteTransfer
            ->addItem($itemTransfer1)
            ->addItem($itemTransfer2);

        // Act
        $quoteReplacementResponseTransfer = $this->tester->getFacade()->replaceQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer1 = $quoteReplacementResponseTransfer->getQuoteOrFail()->getItems()->offsetGet(0);
        $this->assertSame(
            $productOfferTransfer->getProductOfferReference(),
            $quoteItemTransfer1->getProductOfferReference(),
        );

        $quoteItemTransfer2 = $quoteReplacementResponseTransfer->getQuoteOrFail()->getItems()->offsetGet(1);
        $this->assertSame(
            $productOfferTransfer->getProductOfferReference(),
            $quoteItemTransfer2->getProductOfferReference(),
        );

        $this->assertCount(2, $quoteReplacementResponseTransfer->getErrors());
        $this->assertCount(2, $quoteReplacementResponseTransfer->getFailedReplacementItems());
    }

    /**
     * @return void
     */
    public function testFailsWithTwoItemsWithDifferentProductOfferFromSameMerchantAndLowProductOfferAvailability(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        /*
         * Used in the context of {@link \Spryker\Zed\ServicePointCart\Communication\Controller\GatewayController::replaceQuoteItemsAction} or RestApi so there is a current store
         */
        $this->tester->addCurrentStore($storeTransfer);
        $productConcreteTransfer = $this->tester->haveProduct();
        $servicePointTransfer = $this->tester->haveServicePoint([
            ServicePointTransfer::IS_ACTIVE => true,
            ServicePointTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $serviceTransfer = $this->tester->createServiceTransfer($servicePointTransfer, [
            ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP,
            ShipmentTypeTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->createPickupReplacementProductOffer(
            $productConcreteTransfer,
            $serviceTransfer,
            [
                ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP,
                ShipmentTypeTransfer::IS_ACTIVE => true,
                ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
                ProductOfferStockTransfer::QUANTITY => 1,
                ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => false,
                ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
            ],
        );

        $productOfferTransfer1 = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
        ]);
        $productOfferTransfer2 = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
        ]);

        $itemTransfer1 = $this->tester->createItemTransfer($productConcreteTransfer)
            ->setServicePoint($servicePointTransfer)
            ->setMerchantReference($productOfferTransfer1->getMerchantReference())
            ->setQuantity(1)
            ->setProductOfferReference($productOfferTransfer1->getProductOfferReference())
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP));
        $itemTransfer2 = $this->tester->createItemTransfer($productConcreteTransfer)
            ->setServicePoint($servicePointTransfer)
            ->setMerchantReference($productOfferTransfer2->getMerchantReference())
            ->setQuantity(1)
            ->setProductOfferReference($productOfferTransfer2->getProductOfferReference())
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP));

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer);
        $quoteTransfer
            ->addItem($itemTransfer1)
            ->addItem($itemTransfer2);

        // Act
        $quoteReplacementResponseTransfer = $this->tester->getFacade()->replaceQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer1 = $quoteReplacementResponseTransfer->getQuoteOrFail()->getItems()->offsetGet(0);
        $this->assertSame(
            $productOfferTransfer1->getProductOfferReference(),
            $quoteItemTransfer1->getProductOfferReference(),
        );

        $quoteItemTransfer2 = $quoteReplacementResponseTransfer->getQuoteOrFail()->getItems()->offsetGet(1);
        $this->assertSame(
            $productOfferTransfer2->getProductOfferReference(),
            $quoteItemTransfer2->getProductOfferReference(),
        );

        $this->assertCount(2, $quoteReplacementResponseTransfer->getErrors());
        $this->assertCount(2, $quoteReplacementResponseTransfer->getFailedReplacementItems());
    }

    /**
     * @return void
     */
    public function testFailsIfTargetProductOfferIsInactive(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        /*
         * Used in the context of {@link \Spryker\Zed\ServicePointCart\Communication\Controller\GatewayController::replaceQuoteItemsAction} or RestApi so there is a current store
         */
        $this->tester->addCurrentStore($storeTransfer);
        $productConcreteTransfer = $this->tester->haveProduct();
        $servicePointTransfer = $this->tester->haveServicePoint([
            ServicePointTransfer::IS_ACTIVE => true,
            ServicePointTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $serviceTransfer = $this->tester->createServiceTransfer($servicePointTransfer, [
            ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP,
            ShipmentTypeTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->createPickupReplacementProductOffer(
            $productConcreteTransfer,
            $serviceTransfer,
            [
                ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP,
                ProductOfferTransfer::IS_ACTIVE => false,
                ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
                ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
            ],
        );

        $productOfferTransfer2 = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
        ]);

        $itemTransfer = $this->tester->createItemTransfer($productConcreteTransfer)
            ->setServicePoint($servicePointTransfer)
            ->setMerchantReference($productOfferTransfer2->getMerchantReference())
            ->setQuantity(1)
            ->setProductOfferReference($productOfferTransfer2->getProductOfferReference())
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP));

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer);
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $quoteReplacementResponseTransfer = $this->tester->getFacade()->replaceQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer = $quoteReplacementResponseTransfer->getQuoteOrFail()->getItems()[0];
        $this->assertNotNull($quoteItemTransfer->getGroupKey());
        $this->assertNotNull($quoteItemTransfer->getProductOfferReference());
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
        /*
         * Used in the context of {@link \Spryker\Zed\ServicePointCart\Communication\Controller\GatewayController::replaceQuoteItemsAction} or RestApi so there is a current store
         */
        $this->tester->addCurrentStore($storeTransfer);
        $productConcreteTransfer = $this->tester->haveProduct();
        $servicePointTransfer = $this->tester->haveServicePoint([
            ServicePointTransfer::IS_ACTIVE => true,
            ServicePointTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $serviceTransfer = $this->tester->createServiceTransfer($servicePointTransfer, [
            ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP,
            ShipmentTypeTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->createPickupReplacementProductOffer(
            $productConcreteTransfer,
            $serviceTransfer,
            [
                ShipmentTypeTransfer::KEY => ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP,
                ProductOfferTransfer::IS_ACTIVE => false,
                ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
                ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
            ],
        );

        $productOfferTransfer2 = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => ClickAndCollectExampleBusinessTester::TEST_MERCHANT_REFERENCE_1,
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
        ]);

        $itemTransfer = $this->tester->createItemTransfer($productConcreteTransfer)
            ->setServicePoint($servicePointTransfer)
            ->setMerchantReference($productOfferTransfer2->getMerchantReference())
            ->setQuantity(1)
            ->setProductOfferReference($productOfferTransfer2->getProductOfferReference())
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP));

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer);
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $quoteReplacementResponseTransfer = $this->tester->getFacade()->replaceQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer = $quoteReplacementResponseTransfer->getQuoteOrFail()->getItems()[0];
        $this->assertSame(
            $productOfferTransfer2->getProductOfferReference(),
            $quoteItemTransfer->getProductOfferReference(),
        );
        $this->assertCount(1, $quoteReplacementResponseTransfer->getErrors());
        $this->assertCount(1, $quoteReplacementResponseTransfer->getFailedReplacementItems());
    }
}
