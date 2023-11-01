<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ClickAndCollectExample\Business\Facade;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteReplacementResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestServicePointTransfer;
use Generated\Shared\Transfer\RestShipmentsTransfer;
use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ShipmentMethodCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Zed\ClickAndCollectExample\Business\Replacer\QuoteProductOfferReplacer;
use Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToServicePointFacadeBridge;
use Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToShipmentFacadeBridge;
use SprykerTest\Zed\ClickAndCollectExample\ClickAndCollectExampleBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ClickAndCollectExample
 * @group Business
 * @group Facade
 * @group ValidateReplaceCheckoutDataTest
 * Add your own group annotations below this line
 */
class ValidateReplaceCheckoutDataTest extends ClickAndCollectExampleFacadeMocks
{
    /**
     * @return void
     */
    public function testValidatesExistingReplacement(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())
            ->setGroupKey('groupKey');
        $quoteTransfer = (new QuoteTransfer())
            ->addItem($itemTransfer);
        $quoteReplacementResponseTransfer = (new QuoteReplacementResponseTransfer())
            ->setQuote($quoteTransfer);
        $shipmentTypeTransfer = (new ShipmentTypeTransfer())
            ->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_DELIVERY);
        $shipmentMethodTransfer = (new ShipmentMethodTransfer())
            ->setIdShipmentMethod(1)
            ->setShipmentType($shipmentTypeTransfer);
        $servicePointTransfer = (new ServicePointTransfer())
            ->setUuid('uuid');
        $servicePointCollectionTransfer = (new ServicePointCollectionTransfer())
            ->addServicePoint($servicePointTransfer);
        $restShipmentsTransfer = (new RestShipmentsTransfer())
            ->addItem($itemTransfer->getGroupKeyOrFail())
            ->setIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethodOrFail());
        $restServicePointTransfer = (new RestServicePointTransfer())
            ->addItem($itemTransfer->getGroupKeyOrFail())
            ->setIdServicePoint($servicePointTransfer->getUuidOrFail());
        $checkoutDataTransfer = (new CheckoutDataTransfer())
            ->addShipment($restShipmentsTransfer)
            ->addServicePoint($restServicePointTransfer)
            ->setQuote($quoteTransfer);
        $shipmentMethodCollectionTransfer = (new ShipmentMethodCollectionTransfer())
            ->addShipmentMethod($shipmentMethodTransfer);

        $this->mockShipmentFacade($shipmentMethodCollectionTransfer);
        $this->mockServicePointFacade($servicePointCollectionTransfer);
        $this->mockQuoteProductOfferReplacer($quoteReplacementResponseTransfer, 1);

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateQuoteItemProductOfferReplacement($checkoutDataTransfer);

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testValidatesNotExistingReplacement(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())
            ->setGroupKey('groupKey');
        $quoteTransfer = (new QuoteTransfer())
            ->addItem($itemTransfer);
        $quoteErrorTransfer = (new QuoteErrorTransfer())
            ->setMessage('message');
        $quoteReplacementResponseTransfer = (new QuoteReplacementResponseTransfer())
            ->setQuote($quoteTransfer)
            ->addError($quoteErrorTransfer);
        $shipmentTypeTransfer = (new ShipmentTypeTransfer())
            ->setKey(ClickAndCollectExampleBusinessTester::TEST_SHIPMENT_TYPE_KEY_DELIVERY);
        $shipmentMethodTransfer = (new ShipmentMethodTransfer())
            ->setIdShipmentMethod(1)
            ->setShipmentType($shipmentTypeTransfer);
        $servicePointTransfer = (new ServicePointTransfer())
            ->setUuid('uuid');
        $servicePointCollectionTransfer = (new ServicePointCollectionTransfer())
            ->addServicePoint($servicePointTransfer);
        $restShipmentsTransfer = (new RestShipmentsTransfer())
            ->addItem($itemTransfer->getGroupKeyOrFail())
            ->setIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethodOrFail());
        $restServicePointTransfer = (new RestServicePointTransfer())
            ->addItem($itemTransfer->getGroupKeyOrFail())
            ->setIdServicePoint($servicePointTransfer->getUuidOrFail());
        $checkoutDataTransfer = (new CheckoutDataTransfer())
            ->addShipment($restShipmentsTransfer)
            ->addServicePoint($restServicePointTransfer)
            ->setQuote($quoteTransfer);
        $shipmentMethodCollectionTransfer = (new ShipmentMethodCollectionTransfer())
            ->addShipmentMethod($shipmentMethodTransfer);

        $this->mockShipmentFacade($shipmentMethodCollectionTransfer);
        $this->mockServicePointFacade($servicePointCollectionTransfer);
        $this->mockQuoteProductOfferReplacer($quoteReplacementResponseTransfer, 1);

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateQuoteItemProductOfferReplacement($checkoutDataTransfer);

        // Assert
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer $shipmentMethodCollectionTransfer
     *
     * @return void
     */
    protected function mockShipmentFacade(ShipmentMethodCollectionTransfer $shipmentMethodCollectionTransfer): void
    {
        $clickAndCollectExampleToShipmentFacadeMock = $this->getMockBuilder(ClickAndCollectExampleToShipmentFacadeBridge::class)
            ->disableOriginalConstructor()
            ->getMock();
        $clickAndCollectExampleToShipmentFacadeMock->method('getShipmentMethodCollection')->willReturn($shipmentMethodCollectionTransfer);

        $this->tester->mockFactoryMethod('getShipmentFacade', $clickAndCollectExampleToShipmentFacadeMock);
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointCollectionTransfer $servicePointCollectionTransfer
     *
     * @return void
     */
    protected function mockServicePointFacade(ServicePointCollectionTransfer $servicePointCollectionTransfer): void
    {
        $clickAndCollectExampleToServicePointFacadeMock = $this->getMockBuilder(ClickAndCollectExampleToServicePointFacadeBridge::class)
            ->disableOriginalConstructor()
            ->getMock();
        $clickAndCollectExampleToServicePointFacadeMock->method('getServicePointCollection')->willReturn($servicePointCollectionTransfer);

        $this->tester->mockFactoryMethod('getServicePointFacade', $clickAndCollectExampleToServicePointFacadeMock);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteReplacementResponseTransfer $quoteReplacementResponseTransfer
     * @param int $callCount
     *
     * @return void
     */
    protected function mockQuoteProductOfferReplacer(QuoteReplacementResponseTransfer $quoteReplacementResponseTransfer, int $callCount): void
    {
        $quoteProductOfferReplacerMock = $this->getMockBuilder(QuoteProductOfferReplacer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $quoteProductOfferReplacerMock->expects($this->exactly($callCount))
            ->method('replaceQuoteItemProductOffers')
            ->willReturn($quoteReplacementResponseTransfer);

        $this->tester->mockFactoryMethod('createQuoteProductOfferReplacer', $quoteProductOfferReplacerMock);
    }
}
