<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PublishAndSynchronizeHealthCheck\Business;

use Codeception\Test\Unit;
use Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\PublishAndSynchronizeHealthCheckFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PublishAndSynchronizeHealthCheck
 * @group Business
 * @group Facade
 * @group PublishAndSynchronizeHealthCheckFacadeTest
 * Add your own group annotations below this line
 */
class PublishAndSynchronizeHealthCheckFacadeTest extends Unit
{
    public const TEST_HEALTH_CHECK_KEY = 'ps:hc:valid_key';
    public const TEST_HEALTH_CHECK_INVALID_KEY = 'ps:hc:invalid_key';

    /**
     * @var \SprykerTest\Zed\PublishAndSynchronizeHealthCheck\PublishAndSynchronizeHealthCheckBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateOrUpdatePublishAndSynchronizeHealthCheckEntityCreatesEntity(): void
    {
        // Arrange
        /** @var \Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\PublishAndSynchronizeHealthCheckFacade $publishAndSynchronizeHealthCheckFacade */
        $publishAndSynchronizeHealthCheckFacade = $this->getFacade();

        // Act
        $publishAndSynchronizeHealthCheckTransfer = $publishAndSynchronizeHealthCheckFacade->savePublishAndSynchronizeHealthCheckEntity();

        // Assert
        $this->assertNotNull($publishAndSynchronizeHealthCheckTransfer->getUpdatedAt());
    }

    /**
     * @return \Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\PublishAndSynchronizeHealthCheckFacadeInterface
     */
    protected function getFacade(): PublishAndSynchronizeHealthCheckFacadeInterface
    {
        /** @var \Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\PublishAndSynchronizeHealthCheckFacadeInterface $publishAndSynchronizeHealthCheckFacade */
        $publishAndSynchronizeHealthCheckFacade = $this->tester->getFacade();

        return $publishAndSynchronizeHealthCheckFacade;
    }
}
