<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentsRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiBusinessFactory;
use Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeBridge;
use Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentsRestApi
 * @group Business
 * @group ExpandCheckoutDataWithAvailableShipmentMethodsTest
 * Add your own group annotations below this line
 */
class ExpandCheckoutDataWithAvailableShipmentMethodsTest extends Unit
{
    protected const DEFAULT_PRICE_LIST = [
        'DE' => [
            'EUR' => [
                'netAmount' => 10,
                'grossAmount' => 15,
            ],
        ],
    ];

    /**
     * @var \SprykerTest\Zed\ShipmentsRestApi\ShipmentsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandCheckoutDataWithAvailableShipmentMethods(): void
    {
        // Arrange
        $restCheckoutDataTransfer = $this->buildRestCheckoutData();

        // Act
        $restCheckoutDataTransfer = $this->tester->getFacade()->expandCheckoutDataWithAvailableShipmentMethods(
            $restCheckoutDataTransfer,
            new RestCheckoutRequestAttributesTransfer()
        );

        // Assert
        $this->assertCount(3, $restCheckoutDataTransfer->getAvailableShipmentMethods()->getShipmentMethods());
    }

    /**
     * @return void
     */
    public function testExpandCheckoutDataWithAvailableShipmentMethodsWithoutQuote(): void
    {
        // Arrange
        $restCheckoutDataTransfer = $this->buildRestCheckoutData();
        $restCheckoutDataTransfer->setQuote(null);

        // Act
        $restCheckoutDataTransfer = $this->tester->getFacade()->expandCheckoutDataWithAvailableShipmentMethods(
            $restCheckoutDataTransfer,
            new RestCheckoutRequestAttributesTransfer()
        );

        // Assert
        $this->assertNull($restCheckoutDataTransfer->getAvailableShipmentMethods());
    }

    /**
     * @return void
     */
    public function testExpandCheckoutDataWithAvailableShipmentMethodsWhenAvailableMethodsNotFound(): void
    {
        // Arrange
        $restCheckoutDataTransfer = $this->buildRestCheckoutData();

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiBusinessFactory $shipmentsRestApiBusinessFactoryMock */
        $shipmentsRestApiBusinessFactoryMock = $this->getMockBuilder(ShipmentsRestApiBusinessFactory::class)
            ->onlyMethods(['getShipmentFacade'])
            ->getMock();

        $shipmentsRestApiBusinessFactoryMock
            ->method('getShipmentFacade')
            ->willReturn($this->getShipmentFacadeMock());

        // Act
        $restCheckoutDataTransfer = $this->tester->getFacadeMock($shipmentsRestApiBusinessFactoryMock)
            ->expandCheckoutDataWithAvailableShipmentMethods(
                $restCheckoutDataTransfer,
                new RestCheckoutRequestAttributesTransfer()
            );

        // Assert
        $this->assertEmpty($restCheckoutDataTransfer->getAvailableShipmentMethods()->getShipmentMethods());
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutDataTransfer
     */
    protected function buildRestCheckoutData(): RestCheckoutDataTransfer
    {
        $quoteTransfer = $this->tester->buildQuote();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $shipmentMethodTransfer = $this->tester->haveShipmentMethod(
                [],
                [],
                static::DEFAULT_PRICE_LIST,
                [$quoteTransfer->getStore()->getIdStore()]
            );

            $itemTransfer->setShipment((new ShipmentTransfer())->setMethod($shipmentMethodTransfer));
        }

        return (new RestCheckoutDataTransfer())
            ->setQuote($quoteTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeInterface
     */
    protected function getShipmentFacadeMock(): ShipmentsRestApiToShipmentFacadeInterface
    {
        $shipmentFacadeMock = $this->getMockBuilder(ShipmentsRestApiToShipmentFacadeBridge::class)
            ->onlyMethods(['getAvailableMethodsByShipment'])
            ->disableOriginalConstructor()
            ->getMock();

        $shipmentFacadeMock
            ->method('getAvailableMethodsByShipment')
            ->willReturn(new ShipmentMethodsCollectionTransfer());

        return $shipmentFacadeMock;
    }
}
