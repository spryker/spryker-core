<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ZedRequest\Client;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Client\ZedRequest\Client\Response;
use Spryker\Shared\ZedRequest\Logger\ZedRequestLoggerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ZedRequest
 * @group Client
 * @group LoggableZedClientTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Client\ZedRequest\ZedRequestClientTester $tester
 */
class LoggableZedClientTest extends Unit
{
    public const MESSAGE_TYPE_SUCCESS = 'success';
    public const MESSAGE_TYPE_ERROR = 'error';
    public const MESSAGE_TYPE_INFO = 'info';

    /**
     * @return void
     */
    public function testCanMakeZedCall(): void
    {
        // Arrange
        $uri = 'localhost';
        $payload = (new MessageTransfer())->setValue('payload');
        $expectedResult = (new MessageTransfer())->setValue('result');
        $zedRequestLoggerMock = $this->createMock(ZedRequestLoggerInterface::class);
        $zedRequestLoggerMock->expects($this->once())->method('log')->with(
            $uri,
            $payload->toArray(),
            $expectedResult->toArray()
        );
        $this->tester->mockFactoryMethod('createZedRequestLogger', $zedRequestLoggerMock);
        $this->tester->mockCreateZedClient(['call' => $expectedResult]);

        // Act
        $actualResult = $this->tester->createLoggableZedClient()->call($uri, $payload);

        // Assert
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testHasLastResponse(): void
    {
        // Arrange
        $expectedResult = true;
        $this->tester->mockCreateZedClient(['hasLastResponse' => $expectedResult]);

        // Act
        $actualResult = $this->tester->createLoggableZedClient()->hasLastResponse();

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testCanGetLastResponse(): void
    {
        $expectedResult = new Response();
        $this->tester->mockCreateZedClient(['getLastResponse' => $expectedResult]);

        $actualResult = $this->tester->createLoggableZedClient()->getLastResponse();

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testCanGetInfoStatusMessages(): void
    {
        // Arrange
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setType(static::MESSAGE_TYPE_INFO);
        $expectedResult = [$messageTransfer];
        $this->tester->mockCreateZedClient(['getInfoStatusMessages' => [$messageTransfer]]);

        // Act
        $actualResult = $this->tester->createLoggableZedClient()->getInfoStatusMessages();

        // Assert
        $this->tester->assertMessageAreSame($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testCanGetErrorStatusMessages(): void
    {
        // Arrange
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setType(static::MESSAGE_TYPE_ERROR);
        $expectedResult = [$messageTransfer];
        $this->tester->mockCreateZedClient(['getErrorStatusMessages' => [$messageTransfer]]);

        // Act
        $actualResult = $this->tester->createLoggableZedClient()->getErrorStatusMessages();

        // Assert
        $this->tester->assertMessageAreSame($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testCanGetSuccessStatusMessages(): void
    {
        // Arrange
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setType(static::MESSAGE_TYPE_SUCCESS);
        $expectedResult = [$messageTransfer];
        $this->tester->mockCreateZedClient(['getSuccessStatusMessages' => $expectedResult]);

        // Act
        $actualResult = $this->tester->createLoggableZedClient()->getSuccessStatusMessages();

        // Assert
        $this->tester->assertMessageAreSame($expectedResult, $actualResult);
    }
}
