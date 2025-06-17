<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Cart;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Cart\ServicePointItemExpanderPlugin;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalDependencyProvider;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Cart
 * @group ServicePointItemExpanderPluginTest
 */
class ServicePointItemExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_SERVICE_POINT_UUID = 'test-service-point-uuid';

    /**
     * @var string
     */
    protected const TEST_STORE_NAME = 'DE';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    /**
     * @return void
     */
    public function testExpandItemsExpandsItemsWithServicePoint(): void
    {
        // Arrange
        $servicePointFacadeMock = $this->createServicePointFacadeMock();
        $servicePointFacadeMock->expects($this->once())
            ->method('getServicePointCollection')
            ->willReturn($this->tester->createServicePointCollectionTransfer());

        $this->tester->setDependency(
            SelfServicePortalDependencyProvider::FACADE_SERVICE_POINT,
            $servicePointFacadeMock,
        );

        $cartChangeTransfer = $this->createCartChangeTransferWithServicePoint(static::TEST_SERVICE_POINT_UUID);
        $servicePointItemExpanderPlugin = new ServicePointItemExpanderPlugin();

        // Act
        $resultCartChangeTransfer = $servicePointItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $itemTransfer = $resultCartChangeTransfer->getItems()[0];
        $this->assertNotNull($itemTransfer->getServicePoint());
        $this->assertSame(static::TEST_SERVICE_POINT_UUID, $itemTransfer->getServicePointOrFail()->getUuidOrFail());
        $this->assertNotNull($itemTransfer->getServicePointOrFail()->getIdServicePoint());
        $this->assertNotNull($itemTransfer->getServicePointOrFail()->getName());
    }

    /**
     * @return void
     */
    public function testExpandItemsDoesNothingWhenNoServicePointUuidProvided(): void
    {
        // Arrange
        $servicePointFacadeMock = $this->createServicePointFacadeMock();
        $servicePointFacadeMock->expects($this->never())
            ->method('getServicePointCollection');

        $this->tester->setDependency(
            SelfServicePortalDependencyProvider::FACADE_SERVICE_POINT,
            $servicePointFacadeMock,
        );

        $cartChangeTransfer = $this->createCartChangeTransferWithoutServicePoint();

        $servicePointItemExpanderPlugin = new ServicePointItemExpanderPlugin();

        // Act
        $resultCartChangeTransfer = $servicePointItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $itemTransfer = $resultCartChangeTransfer->getItems()[0];
        $this->assertNull($itemTransfer->getServicePoint());
    }

    /**
     * @return void
     */
    public function testExpandItemsDoesNothingWhenNoItemsProvided(): void
    {
        // Arrange
        $servicePointFacadeMock = $this->createServicePointFacadeMock();
        $servicePointFacadeMock->expects($this->never())
            ->method('getServicePointCollection');

        $this->tester->setDependency(
            SelfServicePortalDependencyProvider::FACADE_SERVICE_POINT,
            $servicePointFacadeMock,
        );

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setItems(new ArrayObject());
        $cartChangeTransfer->setQuote(
            (new QuoteTransfer())->setStore((new StoreTransfer())->setName(static::TEST_STORE_NAME)),
        );

        $servicePointItemExpanderPlugin = new ServicePointItemExpanderPlugin();

        // Act
        $resultCartChangeTransfer = $servicePointItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $this->assertCount(0, $resultCartChangeTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testExpandItemsDoesNothingWhenServicePointFacadeReturnsNoResults(): void
    {
        // Arrange
        $servicePointFacadeMock = $this->createServicePointFacadeMock();
        $servicePointFacadeMock->expects($this->once())
            ->method('getServicePointCollection')
            ->willReturn(new ServicePointCollectionTransfer());

        $this->tester->setDependency(
            SelfServicePortalDependencyProvider::FACADE_SERVICE_POINT,
            $servicePointFacadeMock,
        );

        $cartChangeTransfer = $this->createCartChangeTransferWithServicePoint(static::TEST_SERVICE_POINT_UUID);

        $servicePointItemExpanderPlugin = new ServicePointItemExpanderPlugin();

        // Act
        $resultCartChangeTransfer = $servicePointItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $itemTransfer = $resultCartChangeTransfer->getItems()[0];
        $this->assertNotNull($itemTransfer->getServicePoint());
        $this->assertSame(static::TEST_SERVICE_POINT_UUID, $itemTransfer->getServicePointOrFail()->getUuidOrFail());
        $this->assertNull($itemTransfer->getServicePointOrFail()->getIdServicePoint());
        $this->assertNull($itemTransfer->getServicePointOrFail()->getName());
    }

    /**
     * @return void
     */
    public function testExpandItemsExpandsBundleItemsWithServicePoint(): void
    {
        // Arrange
        $servicePointFacadeMock = $this->createServicePointFacadeMock();
        $servicePointFacadeMock->expects($this->once())
            ->method('getServicePointCollection')
            ->willReturn($this->tester->createServicePointCollectionTransfer());

        $this->tester->setDependency(
            SelfServicePortalDependencyProvider::FACADE_SERVICE_POINT,
            $servicePointFacadeMock,
        );

        $itemTransfers = new ArrayObject([
            (new ItemTransfer())->setServicePoint(
                (new ServicePointTransfer())->setUuid(static::TEST_SERVICE_POINT_UUID),
            ),
        ]);
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setItems($itemTransfers);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setStore((new StoreTransfer())->setName(static::TEST_STORE_NAME));
        $quoteTransfer->setBundleItems($itemTransfers);

        $cartChangeTransfer->setQuote($quoteTransfer);

        $servicePointItemExpanderPlugin = new ServicePointItemExpanderPlugin();

        // Act
        $resultCartChangeTransfer = $servicePointItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $bundleItemTransfer = $resultCartChangeTransfer->getQuoteOrFail()->getBundleItems()[0];
        $this->assertNotNull($bundleItemTransfer->getServicePoint());
        $this->assertSame(static::TEST_SERVICE_POINT_UUID, $bundleItemTransfer->getServicePointOrFail()->getUuidOrFail());
        $this->assertNotNull($bundleItemTransfer->getServicePointOrFail()->getIdServicePoint());
        $this->assertNotNull($bundleItemTransfer->getServicePointOrFail()->getName());
    }

    /**
     * @param string|null $servicePointUuid
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransferWithServicePoint(?string $servicePointUuid = null): CartChangeTransfer
    {
        $cartChangeTransfer = new CartChangeTransfer();
        $itemTransfer = new ItemTransfer();

        if ($servicePointUuid) {
            $servicePointTransfer = new ServicePointTransfer();
            $servicePointTransfer->setUuid($servicePointUuid);
            $itemTransfer->setServicePoint($servicePointTransfer);
        }

        $cartChangeTransfer->setItems(new ArrayObject([$itemTransfer]));
        $cartChangeTransfer->setQuote(
            (new QuoteTransfer())->setStore((new StoreTransfer())->setName(static::TEST_STORE_NAME))
                ->setBundleItems(new ArrayObject()),
        );

        return $cartChangeTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransferWithoutServicePoint(): CartChangeTransfer
    {
        $cartChangeTransfer = new CartChangeTransfer();
        $itemTransfer = new ItemTransfer();

        $cartChangeTransfer->setItems(new ArrayObject([$itemTransfer]));
        $cartChangeTransfer->setQuote(
            (new QuoteTransfer())->setStore((new StoreTransfer())->setName(static::TEST_STORE_NAME))
                ->setBundleItems(new ArrayObject()),
        );

        return $cartChangeTransfer;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface
     */
    protected function createServicePointFacadeMock(): ServicePointFacadeInterface
    {
        return $this->getMockBuilder(ServicePointFacadeInterface::class)
            ->getMock();
    }
}
