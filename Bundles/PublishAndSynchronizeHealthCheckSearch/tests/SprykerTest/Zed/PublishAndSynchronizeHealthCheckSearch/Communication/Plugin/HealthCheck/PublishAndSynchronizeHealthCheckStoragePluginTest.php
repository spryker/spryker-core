<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PublishAndSynchronizeHealthCheckSearch\Communication\Plugin\HealthCheck;

use Codeception\Test\Unit;
use DateInterval;
use DateTime;
use Spryker\Shared\PublishAndSynchronizeHealthCheckSearch\PublishAndSynchronizeHealthCheckSearchConfig as SharedPublishAndSynchronizeHealthCheckSearchConfig;
use Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Communication\Plugin\HealthCheck\PublishAndSynchronizeHealthCheckSearchPlugin;
use Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\PublishAndSynchronizeHealthCheckSearchConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PublishAndSynchronizeHealthCheckSearch
 * @group Communication
 * @group Plugin
 * @group HealthCheck
 * @group PublishAndSynchronizeHealthCheckStoragePluginTest
 * Add your own group annotations below this line
 */
class PublishAndSynchronizeHealthCheckStoragePluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PublishAndSynchronizeHealthCheckSearch\PublishAndSynchronizeHealthCheckSearchCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetNameReturnsTheNameOfTheHealthCheckPlugin(): void
    {
        $publishAndSynchronizeReadHealthCheckSearchPlugin = new PublishAndSynchronizeHealthCheckSearchPlugin();
        $this->assertEquals('publish-and-synchronize-search', $publishAndSynchronizeReadHealthCheckSearchPlugin->getName());
    }

    /**
     * @return void
     */
    public function testCheckReturnsFailedResponseWhenDataByKeyNotFound(): void
    {
        // Arrange
        $publishAndSynchronizeHealthCheckSearchPlugin = new PublishAndSynchronizeHealthCheckSearchPlugin();

        // Act
        $healthCheckServiceResponseTransfer = $publishAndSynchronizeHealthCheckSearchPlugin->check();

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

        $publishAndSynchronizeHealthCheckSearchPlugin = new PublishAndSynchronizeHealthCheckSearchPlugin();
        $publishAndSynchronizeHealthCheckData = [
            'updated_at' => $expiredThresholdDate->format('c'),
        ];

        $this->tester->mockSearchData(
            SharedPublishAndSynchronizeHealthCheckSearchConfig::PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_SEARCH_ID,
            $publishAndSynchronizeHealthCheckData,
            PublishAndSynchronizeHealthCheckSearchConfig::SOURCE_IDENTIFIER
        );

        // Act
        $healthCheckServiceResponseTransfer = $publishAndSynchronizeHealthCheckSearchPlugin->check();

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

        $publishAndSynchronizeHealthCheckStoragePlugin = new PublishAndSynchronizeHealthCheckSearchPlugin();
        $publishAndSynchronizeHealthCheckData = [
            'updated_at' => $expiredThresholdDate->format('c'),
        ];

        $this->tester->mockSearchData(
            SharedPublishAndSynchronizeHealthCheckSearchConfig::PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_SEARCH_ID,
            $publishAndSynchronizeHealthCheckData,
            PublishAndSynchronizeHealthCheckSearchConfig::SOURCE_IDENTIFIER
        );

        // Act
        $healthCheckServiceResponseTransfer = $publishAndSynchronizeHealthCheckStoragePlugin->check();

        // Assert
        $this->assertTrue($healthCheckServiceResponseTransfer->getStatus());
    }
}
