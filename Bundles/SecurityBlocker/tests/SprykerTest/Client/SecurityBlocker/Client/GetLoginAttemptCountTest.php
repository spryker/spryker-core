<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SecurityBlocker\Client;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\SecurityCheckAuthContextBuilder;
use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use Spryker\Client\SecurityBlocker\Dependency\Client\SecurityBlockerToRedisClientInterface;
use Spryker\Client\SecurityBlocker\SecurityBlockerConfig;
use Spryker\Client\SecurityBlocker\SecurityBlockerDependencyProvider;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SecurityBlocker
 * @group Client
 * @group GetLoginAttemptCountTest
 * Add your own group annotations below this line
 */
class GetLoginAttemptCountTest extends Test
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
    public function testGetLoginAttemptCountWillRequireType(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = (new SecurityCheckAuthContextTransfer());
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getLocator()->securityBlocker()->client()
            ->getLoginAttemptCount($securityCheckAuthContextTransfer);
    }

    /**
     * @return void
     */
    public function testGetLoginAttemptCountWillSucceed(): void
    {
        // Arrange
        $securityCheckAuthContextTransfer = (new SecurityCheckAuthContextBuilder())->build();
        $securityBlockerConfig = new SecurityBlockerConfig();
        $expectedRedisKey = sprintf(
            'kv:%s:%s:%s',
            $securityCheckAuthContextTransfer->getType(),
            $securityCheckAuthContextTransfer->getIp(),
            $securityCheckAuthContextTransfer->getAccount()
        );

        $expectedNumberOfAttempts = '1';
        $this->redisClientMock
            ->expects($this->once())
            ->method('get')
            ->with(
                $securityBlockerConfig->getRedisConnectionKey(),
                $expectedRedisKey
            )->willReturn($expectedNumberOfAttempts);

        // Act
        $actualSecurityCheckAuthResponseTransfer = $this->tester->getLocator()->securityBlocker()->client()
            ->getLoginAttemptCount($securityCheckAuthContextTransfer);

        // Assert
        $this->assertSame($securityCheckAuthContextTransfer, $actualSecurityCheckAuthResponseTransfer->getSecurityCheckAuthContext());
        $this->assertSame((int)$expectedNumberOfAttempts, $actualSecurityCheckAuthResponseTransfer->getNumberOfAttempts());
        $this->assertTrue($actualSecurityCheckAuthResponseTransfer->getIsSuccessful());
    }
}
