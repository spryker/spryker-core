<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PublishAndSynchronizeHealthCheck\Communication\Plugin\HealthCheck;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer;
use Spryker\Zed\PublishAndSynchronizeHealthCheck\Communication\Plugin\HealthCheck\PublishAndSynchronizeWriteHealthCheckPlugin;
use Spryker\Zed\PublishAndSynchronizeHealthCheck\PublishAndSynchronizeHealthCheckConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PublishAndSynchronizeHealthCheck
 * @group Communication
 * @group Plugin
 * @group HealthCheck
 * @group PublishAndSynchronizeWriteHealthCheckPluginTest
 * Add your own group annotations below this line
 */
class PublishAndSynchronizeWriteHealthCheckPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PublishAndSynchronizeHealthCheck\PublishAndSynchronizeHealthCheckCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetNamePublishAndSynchronizeWriteHealthCheckPlugin(): void
    {
        $publishAndSynchronizeWriteHealthCheckPlugin = new PublishAndSynchronizeWriteHealthCheckPlugin();
        $this->assertNotNull($publishAndSynchronizeWriteHealthCheckPlugin->getName());
    }

    /**
     * @return void
     */
    public function testCheckReturnsSuccessfulHealthCheckResponse(): void
    {
        $publishAndSynchronizeHealthCheckTransfer = new PublishAndSynchronizeHealthCheckTransfer();
        $publishAndSynchronizeHealthCheckTransfer->setHealthCheckKey(PublishAndSynchronizeHealthCheckConfig::DEFAULT_HEALTH_CHECK_KEY);
        $publishAndSynchronizeHealthCheckTransfer->setHealthCheckData(uniqid());
        $publishAndSynchronizeHealthCheckTransfer->setUpdatedAt(date('Y-m-d H:i:s'));

        $publishAndSynchronizeFacadeMock = $this->tester->mockFacadeMethod('savePublishAndSynchronizeHealthCheckEntity', $publishAndSynchronizeHealthCheckTransfer);
        $publishAndSynchronizeWriteHealthCheckPlugin = new PublishAndSynchronizeWriteHealthCheckPlugin();
        $publishAndSynchronizeWriteHealthCheckPlugin->setFacade($publishAndSynchronizeFacadeMock);
        $healthCheckServiceResponseTransfer = $publishAndSynchronizeWriteHealthCheckPlugin->check();

        $this->assertTrue($healthCheckServiceResponseTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testCheckReturnsFailedHealthCheckResponse(): void
    {
        $publishAndSynchronizeHealthCheckTransfer = new PublishAndSynchronizeHealthCheckTransfer();
        $publishAndSynchronizeHealthCheckTransfer->setHealthCheckKey(PublishAndSynchronizeHealthCheckConfig::DEFAULT_HEALTH_CHECK_KEY);
        $publishAndSynchronizeHealthCheckTransfer->setHealthCheckData(uniqid());
        $publishAndSynchronizeHealthCheckTransfer->setUpdatedAt(null);

        $publishAndSynchronizeFacadeMock = $this->tester->mockFacadeMethod('savePublishAndSynchronizeHealthCheckEntity', $publishAndSynchronizeHealthCheckTransfer);
        $publishAndSynchronizeWriteHealthCheckPlugin = new PublishAndSynchronizeWriteHealthCheckPlugin();
        $publishAndSynchronizeWriteHealthCheckPlugin->setFacade($publishAndSynchronizeFacadeMock);
        $healthCheckServiceResponseTransfer = $publishAndSynchronizeWriteHealthCheckPlugin->check();

        $this->assertFalse($healthCheckServiceResponseTransfer->getStatus());
        $this->assertNotNull($healthCheckServiceResponseTransfer->getMessage());
    }
}
