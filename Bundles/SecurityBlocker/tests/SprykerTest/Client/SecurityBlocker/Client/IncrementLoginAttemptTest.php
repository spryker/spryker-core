<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SecurityBlocker\Client;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\AuthContextBuilder;
use Generated\Shared\Transfer\AuthContextTransfer;
use Spryker\Client\SecurityBlocker\Dependency\Client\SecurityBlockerToRedisClientInterface;
use Spryker\Client\SecurityBlocker\Exception\SecurityBlockerException;
use Spryker\Client\SecurityBlocker\SecurityBlockerConfig;
use Spryker\Client\SecurityBlocker\SecurityBlockerDependencyProvider;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SecurityBlocker
 * @group Client
 * @group IncrementLoginAttemptTest
 * Add your own group annotations below this line
 */
class IncrementLoginAttemptTest extends Test
{
    /**
     * @var \SprykerTest\Client\SecurityBlocker\SecurityBlockerClientTester
     */
    protected $tester;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\SecurityBlocker\Dependency\Client\SecurityBlockerToRedisClientInterface
     */
    protected $redisClientMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->redisClientMock = $this->getMockBuilder(SecurityBlockerToRedisClientInterface::class)
            ->getMock();
        $this->tester->setDependency(SecurityBlockerDependencyProvider::CLIENT_REDIS, $this->redisClientMock);
    }

    /**
     * @return void
     */
    public function testIncrementLoginAttemptWillRequireType(): void
    {
        // Arrange
        $authContextTransfer = (new AuthContextTransfer());
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getLocator()->securityBlocker()->client()->incrementLoginAttempt($authContextTransfer);
    }

    /**
     * @return void
     */
    public function testIncrementLoginAttemptWillSucceed(): void
    {
        // Arrange
        $authContextTransfer = (new AuthContextBuilder())->build();
        $securityBlockerConfig = new SecurityBlockerConfig();
        $expectedRedisKey = sprintf(
            'kv:%s:%s:%s',
            $authContextTransfer->getType(),
            $authContextTransfer->getIp(),
            $authContextTransfer->getAccount()
        );

        $this->redisClientMock
            ->expects($this->once())
            ->method('set')
            ->with(
                $securityBlockerConfig->getRedisConnectionKey(),
                $expectedRedisKey,
                '1',
                'EX',
                $securityBlockerConfig->getSecurityConfigurationSettings()['default']['ttl']
            )
            ->willReturn(true);

        // Act
        $this->tester->getLocator()->securityBlocker()->client()->incrementLoginAttempt($authContextTransfer);
    }

    /**
     * @return void
     */
    public function testIncrementLoginAttemptWillFailWithException(): void
    {
        // Arrange
        $authContextTransfer = (new AuthContextBuilder())->build();

        $this->redisClientMock
            ->expects($this->once())
            ->method('set')
            ->willReturn(false);

        $this->expectException(SecurityBlockerException::class);

        // Act
        $this->tester->getLocator()->securityBlocker()->client()->incrementLoginAttempt($authContextTransfer);
    }
}
