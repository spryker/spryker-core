<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PublishAndSynchronizeHealthCheckStorage\Communication\Plugin\HealthCheck;

use Codeception\Test\Unit;
use DateInterval;
use DateTime;
use Spryker\Shared\PublishAndSynchronizeHealthCheckStorage\PublishAndSynchronizeHealthCheckStorageConfig;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Communication\Plugin\HealthCheck\PublishAndSynchronizeHealthCheckStoragePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PublishAndSynchronizeHealthCheckStorage
 * @group Communication
 * @group Plugin
 * @group HealthCheck
 * @group PublishAndSynchronizeHealthCheckStoragePluginTest
 * Add your own group annotations below this line
 */
class PublishAndSynchronizeHealthCheckStoragePluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PublishAndSynchronizeHealthCheckStorage\PublishAndSynchronizeHealthCheckStorageCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetNameReturnsTheNameOfTheHealthCheckPlugin(): void
    {
        $publishAndSynchronizeReadHealthCheckStoragePlugin = new PublishAndSynchronizeHealthCheckStoragePlugin();
        $this->assertEquals('publish-and-synchronize-storage', $publishAndSynchronizeReadHealthCheckStoragePlugin->getName());
    }

    /**
     * @return void
     */
    public function testCheckReturnsFailedResponseWhenDataByKeyNotFound(): void
    {
        // Arrange
        $publishAndSynchronizeHealthCheckStoragePlugin = new PublishAndSynchronizeHealthCheckStoragePlugin();

        // Act
        $healthCheckServiceResponseTransfer = $publishAndSynchronizeHealthCheckStoragePlugin->check();

        // Assert
        $this->assertFalse($healthCheckServiceResponseTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testCheckReturnsFailedResponseWhenDataIsOlderThanTheExpectedThreshold(): void
    {
        // Arrange
        $expiredThresholdDate = new DateTime();
        $dateInterval = DateInterval::createFromDateString('5 days');
        $expiredThresholdDate->sub($dateInterval);

        $publishAndSynchronizeHealthCheckStoragePlugin = new PublishAndSynchronizeHealthCheckStoragePlugin();
        $publishAndSynchronizeHealthCheckData = [
            'updated_at' => $expiredThresholdDate->format('c'),
        ];

        $this->tester->mockStorageData(
            PublishAndSynchronizeHealthCheckStorageConfig::PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_STORAGE_KEY,
            json_encode($publishAndSynchronizeHealthCheckData)
        );

        // Act
        $healthCheckServiceResponseTransfer = $publishAndSynchronizeHealthCheckStoragePlugin->check();

        // Assert
        $this->assertFalse($healthCheckServiceResponseTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testCheckReturnsSuccessfulResponseWhenDataIsNotOlderThanTheExpectedThreshold(): void
    {
        // Arrange
        $expiredThresholdDate = new DateTime();
        $dateInterval = DateInterval::createFromDateString('5 days');
        $expiredThresholdDate->add($dateInterval);

        $publishAndSynchronizeHealthCheckStoragePlugin = new PublishAndSynchronizeHealthCheckStoragePlugin();
        $publishAndSynchronizeHealthCheckData = [
            'updated_at' => $expiredThresholdDate->format('c'),
        ];

        $this->tester->mockStorageData(
            PublishAndSynchronizeHealthCheckStorageConfig::PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_STORAGE_KEY,
            json_encode($publishAndSynchronizeHealthCheckData)
        );

        // Act
        $healthCheckServiceResponseTransfer = $publishAndSynchronizeHealthCheckStoragePlugin->check();

        // Assert
        $this->assertTrue($healthCheckServiceResponseTransfer->getStatus());
    }
}
