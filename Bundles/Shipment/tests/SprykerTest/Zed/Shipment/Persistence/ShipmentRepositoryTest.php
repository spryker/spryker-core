<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Shipment\Persistence\ShipmentRepository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Persistence
 * @group ShipmentRepositoryTest
 * Add your own group annotations below this line
 */
class ShipmentRepositoryTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_STORE_NAME = 'DE';

    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentPersistenceTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface
     */
    protected $shipmentRepository;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->shipmentRepository = new ShipmentRepository();
    }

    /**
     * @return void
     */
    public function testGetActiveShipmentMethodsForStoreReturnsCorrectShipmentMethods(): void
    {
        // Arrange
        $this->tester->ensureShipmentMethodTableIsEmpty();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => static::TEST_STORE_NAME,
        ]);

        $priceList = [
            $storeTransfer->getName() => [
                'EUR' => [
                    'netAmount' => 3100,
                    'grossAmount' => 3100,
                ],
            ],
        ];

        $idStoreList = [
            $storeTransfer->getIdStore(),
        ];

        $activeShipmentMethodTransfer = $this->tester->haveShipmentMethod([
            ShipmentMethodTransfer::IS_ACTIVE => true,
        ], [], $priceList, $idStoreList);
        $inactiveShipmentMethodTransfer = $this->tester->haveShipmentMethod([
            ShipmentMethodTransfer::IS_ACTIVE => false,
        ], [], $priceList, $idStoreList);

        // Act
        $shipmentMethodTransfers = $this->shipmentRepository->getActiveShipmentMethodsForStore($storeTransfer->getIdStore());

        // Assert
        $this->assertCount(1, $shipmentMethodTransfers);
        $shipmentMethodIds = $this->getShipmentMethodIds($shipmentMethodTransfers);
        $this->assertContains($activeShipmentMethodTransfer->getIdShipmentMethod(), $shipmentMethodIds);
        $this->assertNotContains($inactiveShipmentMethodTransfer->getIdShipmentMethod(), $shipmentMethodIds);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ShipmentMethodTransfer> $shipmentMethods
     *
     * @return array<int>
     */
    protected function getShipmentMethodIds(array $shipmentMethods): array
    {
        return array_map(function (ShipmentMethodTransfer $shipmentMethodTransfer): int {
            return $shipmentMethodTransfer->getIdShipmentMethod();
        }, $shipmentMethods);
    }
}
