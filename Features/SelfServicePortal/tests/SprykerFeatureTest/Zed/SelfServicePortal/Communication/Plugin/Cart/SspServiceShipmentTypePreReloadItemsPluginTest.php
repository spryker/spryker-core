<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Cart;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductClassTransfer;
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
    protected const SERVICE_PRODUCT_CLASS = 'service';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

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
            if ($this->tester->hasProductClass($item, static::SERVICE_PRODUCT_CLASS)) {
                $this->assertNotNull($item->getShipmentType(), 'Service items without shipment type should be filtered out');
            }
        }
    }

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

    protected function createItemTransferWithShipmentType(): ItemTransfer
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setShipmentType(
            (new ShipmentTypeTransfer())
                ->setUuid('test-shipment-type-uuid')
                ->setName('Test Shipment Type'),
        );
        $itemTransfer->setSku('test-sku-with-shipment');
        $itemTransfer->addProductClass($this->createServiceProductClass());

        return $itemTransfer;
    }

    protected function createItemTransferWithoutShipmentType(): ItemTransfer
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku('test-service-sku-without-shipment');
        $itemTransfer->addProductClass($this->createServiceProductClass());

        return $itemTransfer;
    }

    public function createServiceProductClass(): ProductClassTransfer
    {
        return (new ProductClassTransfer())->setName(static::SERVICE_PRODUCT_CLASS);
    }

    protected function createNonServiceItemTransfer(): ItemTransfer
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku('test-non-service-sku');

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

    protected function createPluginWithRealImplementation(MessengerFacadeInterface $messengerFacadeMock): SspServiceShipmentTypePreReloadItemsPlugin
    {
        $configMock = $this->getMockBuilder(SelfServicePortalConfig::class)
            ->getMock();
        $configMock->method('getServiceProductClassName')
            ->willReturn(static::SERVICE_PRODUCT_CLASS);

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
