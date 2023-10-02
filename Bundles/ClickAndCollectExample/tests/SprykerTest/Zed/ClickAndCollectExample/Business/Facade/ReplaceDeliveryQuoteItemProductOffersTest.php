<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ClickAndCollectExample\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
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
 * @group ReplaceDeliveryQuoteItemProductOffersTest
 * Add your own group annotations below this line
 */
class ReplaceDeliveryQuoteItemProductOffersTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ClickAndCollectExample\ClickAndCollectExampleBusinessTester
     */
    protected ClickAndCollectExampleBusinessTester $tester;

    /**
     * @return void
     */
    protected function _setUp(): void
    {
        parent::_setUp();
        $this->tester->mockClickAndCollectExampleConfig();
    }

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
        $quoteResponseTransfer = $this->tester->getFacade()->replaceDeliveryQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer = $quoteResponseTransfer->getQuoteTransferOrFail()->getItems()[0];
        $this->assertSame(
            $productOfferTransfer1->getProductOfferReference(),
            $quoteItemTransfer->getProductOfferReference(),
        );
        $this->assertNull($quoteItemTransfer->getGroupKey());
        $this->assertEmpty($quoteResponseTransfer->getErrors());
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
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP));

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer);
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $quoteResponseTransfer = $this->tester->getFacade()->replaceDeliveryQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer = $quoteResponseTransfer->getQuoteTransferOrFail()->getItems()[0];
        $this->assertNotNull($quoteItemTransfer->getShipmentType());
        $this->assertNotNull($quoteItemTransfer->getGroupKey());
        $this->assertNull($quoteItemTransfer->getProductOfferReference());
        $this->assertEmpty($quoteResponseTransfer->getErrors());
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
            ->setShipmentType((new ShipmentTypeTransfer())->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_PICKUP));

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer);
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $quoteResponseTransfer = $this->tester->getFacade()->replaceDeliveryQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer = $quoteResponseTransfer->getQuoteTransferOrFail()->getItems()[0];
        $this->assertSame(
            $productOfferTransfer->getProductOfferReference(),
            $quoteItemTransfer->getProductOfferReference(),
        );
        $this->assertNotNull($quoteItemTransfer->getShipmentType());
        $this->assertNotNull($quoteItemTransfer->getGroupKey());
        $this->assertNotNull($quoteItemTransfer->getProductOfferReference());
        $this->assertEmpty($quoteResponseTransfer->getErrors());
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
        $quoteResponseTransfer = $this->tester->getFacade()->replaceDeliveryQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer = $quoteResponseTransfer->getQuoteTransferOrFail()->getItems()[0];
        $this->assertSame(
            $productOfferTransfer->getProductOfferReference(),
            $quoteItemTransfer->getProductOfferReference(),
        );
        $this->assertNull($quoteItemTransfer->getShipmentType());
        $this->assertCount(1, $quoteResponseTransfer->getErrors());
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
        $quoteResponseTransfer = $this->tester->getFacade()->replaceDeliveryQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer = $quoteResponseTransfer->getQuoteTransferOrFail()->getItems()[0];
        $this->assertSame(
            $productOfferTransfer->getProductOfferReference(),
            $quoteItemTransfer->getProductOfferReference(),
        );
        $this->assertNull($quoteItemTransfer->getShipmentType());
        $this->assertCount(1, $quoteResponseTransfer->getErrors());
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
        $quoteResponseTransfer = $this->tester->getFacade()->replaceDeliveryQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer = $quoteResponseTransfer->getQuoteTransferOrFail()->getItems()[0];
        $this->assertSame(
            $productOfferTransfer->getProductOfferReference(),
            $quoteItemTransfer->getProductOfferReference(),
        );
        $this->assertNull($quoteItemTransfer->getShipmentType());
        $this->assertCount(1, $quoteResponseTransfer->getErrors());
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
        $quoteResponseTransfer = $this->tester->getFacade()->replaceDeliveryQuoteItemProductOffers($quoteTransfer);

        // Assert
        $quoteItemTransfer = $quoteResponseTransfer->getQuoteTransferOrFail()->getItems()[0];
        $this->assertSame(
            $productOfferTransfer->getProductOfferReference(),
            $quoteItemTransfer->getProductOfferReference(),
        );
        $this->assertNull($quoteItemTransfer->getShipmentType());
        $this->assertCount(1, $quoteResponseTransfer->getErrors());
    }
}
