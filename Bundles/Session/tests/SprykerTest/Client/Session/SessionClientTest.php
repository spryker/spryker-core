<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Session;

use Codeception\Test\Unit;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Session
 * @group SessionClientTest
 * Add your own group annotations below this line
 */
class SessionClientTest extends Unit
{
    /**
     * @var \SprykerTest\Client\Session\SessionClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsStartedSessionWhenContainerIsEmpty(): void
    {
        // Arrange
        $sessionClient = $this->tester->getClient();
        $reflectionClass = new ReflectionClass($sessionClient);
        $reflectionClass->setStaticPropertyValue('container', null);

        // Act
        $isSessionStarted = $sessionClient->isStarted();

        // Assert
        $this->assertFalse($isSessionStarted);
    }

    /**
     * @return void
     */
    public function testIsStartedWhenContainerIsNotEmptyAndSessionNotStarted(): void
    {
        // Arrange
        $this->tester->getClient()->setContainer(
            $this->getSessionMockWithIsStarted(false),
        );

        // Act
        $isSessionStarted = $this->tester->getClient()->isStarted();

        // Assert
        $this->assertFalse($isSessionStarted);
    }

    /**
     * @return void
     */
    public function testIsStartedWhenContainerIsNotEmptyAndSessionIsStarted(): void
    {
        // Arrange
        $this->tester->getClient()->setContainer(
            $this->getSessionMockWithIsStarted(true),
        );

        // Act
        $isSessionStarted = $this->tester->getClient()->isStarted();

        // Assert
        $this->assertTrue($isSessionStarted);
    }

    /**
     * @param bool $isSessionStarted
     *
     * @return \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected function getSessionMockWithIsStarted(bool $isSessionStarted): SessionInterface
    {
        $sessionMock = $this->getMockBuilder(SessionInterface::class)
            ->getMock();

        $sessionMock->method('isStarted')
            ->willReturn($isSessionStarted);

        return $sessionMock;
    }
}
