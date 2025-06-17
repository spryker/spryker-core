<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Cart;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use ReflectionProperty;
use Spryker\Zed\Messenger\Business\MessengerFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Filter\QuoteItemFilter;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Cart\SspServiceShipmentTypePreReloadItemsPlugin;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Cart
 * @group SspServiceShipmentTypePreReloadItemsPluginTest
 */
class SspServiceShipmentTypePreReloadItemsPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const SERVICE_PRODUCT_TYPE = 'service';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    /**
     * @return void
     */
    public function testPreReloadItemsFiltersOutServicesWithoutShipmentTypes(): void
    {
        // Arrange
        $itemWithShipmentType = $this->createItemTransferWithShipmentType();
        $itemWithoutShipmentType = $this->createItemTransferWithoutShipmentType();

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setItems(new ArrayObject([
            $itemWithShipmentType,
            $itemWithoutShipmentType,
        ]));

        $messengerFacadeMock = $this->createMessengerFacadeMock();
        $messengerFacadeMock->expects($this->once())
            ->method('addErrorMessage');

        $plugin = $this->createPluginWithRealImplementation($messengerFacadeMock);

        // Act
        $resultQuoteTransfer = $plugin->preReloadItems($quoteTransfer);

        // Assert
        $this->assertCount(1, $resultQuoteTransfer->getItems());
        $this->assertSame($itemWithShipmentType->getSku(), $resultQuoteTransfer->getItems()[0]->getSku());
        foreach ($resultQuoteTransfer->getItems() as $item) {
            if (in_array(static::SERVICE_PRODUCT_TYPE, $item->getProductTypes() ?: [], true)) {
                $this->assertNotNull($item->getShipmentType(), 'Service items without shipment type should be filtered out');
            }
        }
    }

    /**
     * @return void
     */
    public function testPreReloadItemsDoesNothingWhenNoServiceItemsNeedFiltering(): void
    {
        // Arrange
        $itemWithShipmentType = $this->createItemTransferWithShipmentType();
        $nonServiceItem = $this->createNonServiceItemTransfer();

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setItems(new ArrayObject([
            $itemWithShipmentType,
            $nonServiceItem,
        ]));

        $messengerFacadeMock = $this->createMessengerFacadeMock();
        $messengerFacadeMock->expects($this->never())
            ->method('addErrorMessage');

        $plugin = $this->createPluginWithRealImplementation($messengerFacadeMock);

        // Act
        $resultQuoteTransfer = $plugin->preReloadItems($quoteTransfer);

        // Assert
        $this->assertCount(2, $resultQuoteTransfer->getItems());
        $this->assertSame($quoteTransfer->getItems()->count(), $resultQuoteTransfer->getItems()->count());
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransferWithShipmentType(): ItemTransfer
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setShipmentType(
            (new ShipmentTypeTransfer())
                ->setUuid('test-shipment-type-uuid')
                ->setName('Test Shipment Type'),
        );
        $itemTransfer->setSku('test-sku-with-shipment');
        $itemTransfer->setProductTypes([static::SERVICE_PRODUCT_TYPE]);

        return $itemTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransferWithoutShipmentType(): ItemTransfer
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku('test-service-sku-without-shipment');
        $itemTransfer->setProductTypes([static::SERVICE_PRODUCT_TYPE]);

        return $itemTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createNonServiceItemTransfer(): ItemTransfer
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku('test-non-service-sku');
        $itemTransfer->setProductTypes(['product']);

        return $itemTransfer;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Messenger\Business\MessengerFacadeInterface
     */
    protected function createMessengerFacadeMock(): MessengerFacadeInterface
    {
        return $this->getMockBuilder(MessengerFacadeInterface::class)
            ->getMock();
    }

    /**
     * @param \Spryker\Zed\Messenger\Business\MessengerFacadeInterface $messengerFacadeMock
     *
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Cart\SspServiceShipmentTypePreReloadItemsPlugin
     */
    protected function createPluginWithRealImplementation(MessengerFacadeInterface $messengerFacadeMock): SspServiceShipmentTypePreReloadItemsPlugin
    {
        $configMock = $this->getMockBuilder(SelfServicePortalConfig::class)
            ->getMock();
        $configMock->method('getServiceProductTypeName')
            ->willReturn(static::SERVICE_PRODUCT_TYPE);

        $quoteItemFilter = new QuoteItemFilter(
            $configMock,
            $messengerFacadeMock,
        );

        $factoryMock = $this->getMockBuilder(SelfServicePortalBusinessFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $factoryMock->method('createQuoteItemFilter')
            ->willReturn($quoteItemFilter);

        $plugin = new SspServiceShipmentTypePreReloadItemsPlugin();
        $reflectionProperty = new ReflectionProperty(get_class($plugin), 'businessFactory');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($plugin, $factoryMock);

        return $plugin;
    }
}
