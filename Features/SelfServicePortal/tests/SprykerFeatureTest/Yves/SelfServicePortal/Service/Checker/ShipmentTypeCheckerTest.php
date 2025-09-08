<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Yves\SelfServicePortal\Service\Checker;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeature\Yves\SelfServicePortal\Service\Checker\ShipmentTypeChecker;
use SprykerFeatureTest\Yves\SelfServicePortal\SelfServicePortalYvesTester;

/**
 * @group SprykerFeatureTest
 * @group Yves
 * @group SelfServicePortal
 * @group Service
 * @group Checker
 * @group ShipmentTypeCheckerTest
 */
class ShipmentTypeCheckerTest extends Unit
{
    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_DELIVERY = 'delivery';

    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_ON_SITE_SERVICE = 'on-site-service';

    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_PICKUP = 'pickup';

    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_IN_CENTER_SERVICE = 'in-center-service';

    /**
     * @var \SprykerFeatureTest\Yves\SelfServicePortal\SelfServicePortalYvesTester
     */
    protected SelfServicePortalYvesTester $tester;

    public function testHasOnlyDeliveryLikeShipmentTypeReturnsTrueForDelivery(): void
    {
        // Arrange
        $shipmentTypeChecker = $this->createShipmentTypeCheckerWithDeliveryTypes([
            static::SHIPMENT_TYPE_DELIVERY,
            static::SHIPMENT_TYPE_ON_SITE_SERVICE,
        ]);
        $shipmentTypes = [
            $this->createShipmentTypeStorageTransfer(static::SHIPMENT_TYPE_DELIVERY),
        ];

        // Act
        $result = $shipmentTypeChecker->hasOnlyDeliveryLikeShipmentType($shipmentTypes);

        // Assert
        $this->assertTrue($result);
    }

    public function testHasOnlyDeliveryLikeShipmentTypeReturnsTrueForOnSiteService(): void
    {
        // Arrange
        $shipmentTypeChecker = $this->createShipmentTypeCheckerWithDeliveryTypes([
            static::SHIPMENT_TYPE_DELIVERY,
            static::SHIPMENT_TYPE_ON_SITE_SERVICE,
        ]);
        $shipmentTypes = [
            $this->createShipmentTypeStorageTransfer(static::SHIPMENT_TYPE_ON_SITE_SERVICE),
        ];

        // Act
        $result = $shipmentTypeChecker->hasOnlyDeliveryLikeShipmentType($shipmentTypes);

        // Assert
        $this->assertTrue($result);
    }

    public function testHasOnlyDeliveryLikeShipmentTypeReturnsFalseForPickup(): void
    {
        // Arrange
        $shipmentTypeChecker = $this->createShipmentTypeCheckerWithDeliveryTypes([
            static::SHIPMENT_TYPE_DELIVERY,
            static::SHIPMENT_TYPE_ON_SITE_SERVICE,
        ]);
        $shipmentTypes = [
            $this->createShipmentTypeStorageTransfer(static::SHIPMENT_TYPE_PICKUP),
        ];

        // Act
        $result = $shipmentTypeChecker->hasOnlyDeliveryLikeShipmentType($shipmentTypes);

        // Assert
        $this->assertFalse($result);
    }

    public function testHasOnlyDeliveryLikeShipmentTypeReturnsFalseForInCenterService(): void
    {
        // Arrange
        $shipmentTypeChecker = $this->createShipmentTypeCheckerWithDeliveryTypes([
            static::SHIPMENT_TYPE_DELIVERY,
            static::SHIPMENT_TYPE_ON_SITE_SERVICE,
        ]);
        $shipmentTypes = [
            $this->createShipmentTypeStorageTransfer(static::SHIPMENT_TYPE_IN_CENTER_SERVICE),
        ];

        // Act
        $result = $shipmentTypeChecker->hasOnlyDeliveryLikeShipmentType($shipmentTypes);

        // Assert
        $this->assertFalse($result);
    }

    public function testHasOnlyDeliveryLikeShipmentTypeReturnsFalseForMultipleShipmentTypes(): void
    {
        // Arrange
        $shipmentTypeChecker = $this->createShipmentTypeCheckerWithDeliveryTypes([
            static::SHIPMENT_TYPE_DELIVERY,
            static::SHIPMENT_TYPE_ON_SITE_SERVICE,
        ]);
        $shipmentTypes = [
            $this->createShipmentTypeStorageTransfer(static::SHIPMENT_TYPE_DELIVERY),
            $this->createShipmentTypeStorageTransfer(static::SHIPMENT_TYPE_ON_SITE_SERVICE),
        ];

        // Act
        $result = $shipmentTypeChecker->hasOnlyDeliveryLikeShipmentType($shipmentTypes);

        // Assert
        $this->assertFalse($result);
    }

    public function testHasOnlyDeliveryLikeShipmentTypeReturnsFalseForEmptyArray(): void
    {
        // Arrange
        $shipmentTypeChecker = $this->createShipmentTypeChecker();
        $shipmentTypes = [];

        // Act
        $result = $shipmentTypeChecker->hasOnlyDeliveryLikeShipmentType($shipmentTypes);

        // Assert
        $this->assertFalse($result);
    }

    public function testCoreConfigReturnsEmptyDeliveryLikeShipmentTypes(): void
    {
        // Arrange
        $shipmentTypeChecker = $this->createShipmentTypeChecker();
        $shipmentTypes = [
            $this->createShipmentTypeStorageTransfer(static::SHIPMENT_TYPE_DELIVERY),
        ];

        // Act
        $result = $shipmentTypeChecker->hasOnlyDeliveryLikeShipmentType($shipmentTypes);

        // Assert
        $this->assertFalse($result);
    }

    public function testHasOnlyDeliveryLikeShipmentTypeUsesConfigurationMethod(): void
    {
        // Arrange
        $configMock = $this->createConfigMock(['custom-delivery-type']);
        $shipmentTypeChecker = new ShipmentTypeChecker($configMock);
        $shipmentTypes = [
            $this->createShipmentTypeStorageTransfer('custom-delivery-type'),
        ];

        // Act
        $result = $shipmentTypeChecker->hasOnlyDeliveryLikeShipmentType($shipmentTypes);

        // Assert
        $this->assertTrue($result);
    }

    public function testOnSiteServiceBehavesExactlyLikeDelivery(): void
    {
        // Arrange
        $shipmentTypeChecker = $this->createShipmentTypeCheckerWithDeliveryTypes([
            static::SHIPMENT_TYPE_DELIVERY,
            static::SHIPMENT_TYPE_ON_SITE_SERVICE,
        ]);

        $deliveryShipmentTypes = [
            $this->createShipmentTypeStorageTransfer(static::SHIPMENT_TYPE_DELIVERY),
        ];

        $onSiteServiceShipmentTypes = [
            $this->createShipmentTypeStorageTransfer(static::SHIPMENT_TYPE_ON_SITE_SERVICE),
        ];

        // Act
        $deliveryResult = $shipmentTypeChecker->hasOnlyDeliveryLikeShipmentType($deliveryShipmentTypes);
        $onSiteServiceResult = $shipmentTypeChecker->hasOnlyDeliveryLikeShipmentType($onSiteServiceShipmentTypes);

        // Assert
        $this->assertEquals($deliveryResult, $onSiteServiceResult);
        $this->assertTrue($deliveryResult);
        $this->assertTrue($onSiteServiceResult);
    }

    protected function createShipmentTypeStorageTransfer(string $key): ShipmentTypeStorageTransfer
    {
        return (new ShipmentTypeStorageTransfer())->setKey($key);
    }

    protected function createShipmentTypeChecker(): ShipmentTypeChecker
    {
        $config = new SelfServicePortalConfig();

        return new ShipmentTypeChecker($config);
    }

    /**
     * @param list<string> $deliveryLikeShipmentTypes
     *
     * @return \SprykerFeature\Yves\SelfServicePortal\Service\Checker\ShipmentTypeChecker
     */
    protected function createShipmentTypeCheckerWithDeliveryTypes(array $deliveryLikeShipmentTypes): ShipmentTypeChecker
    {
        $configMock = $this->createConfigMock($deliveryLikeShipmentTypes);

        return new ShipmentTypeChecker($configMock);
    }

    /**
     * @param list<string> $deliveryLikeShipmentTypes
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig
     */
    protected function createConfigMock(array $deliveryLikeShipmentTypes): SelfServicePortalConfig
    {
        $configMock = $this->getMockBuilder(SelfServicePortalConfig::class)
            ->onlyMethods(['getDeliveryLikeShipmentTypes'])
            ->getMock();

        $configMock->method('getDeliveryLikeShipmentTypes')
            ->willReturn($deliveryLikeShipmentTypes);

        return $configMock;
    }
}
