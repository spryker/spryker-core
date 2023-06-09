<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Communication\Plugin\MessageBroker;

use Codeception\Test\Unit;
use SprykerTest\Zed\MessageBroker\MessageBrokerCommunicationTester;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBroker
 * @group Communication
 * @group Plugin
 * @group MessageBroker
 * @group ValidationMiddlewarePluginTest
 * Add your own group annotations below this line
 */
class ValidationMiddlewarePluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MessageBroker\MessageBrokerCommunicationTester
     */
    protected MessageBrokerCommunicationTester $tester;

    /**
     * @return void
     */
    public function testValidationMiddlewarePluginCantHandleReceivedMessageWillThrowException(): void
    {
        // Arrange
        $envelope = $this->tester->haveEnvelopeWithReceivedStamp();

        $validationMiddlewarePlugin = $this->tester->createValidationMiddlewarePluginThatCanNotHandleAMessage();

        $stackMock = $this->tester->createStackMockWithNeverCalledNextMethod();

        // Expectation
        $this->expectException(UnrecoverableMessageHandlingException::class);

        // Act
        $validationMiddlewarePlugin->handle($envelope, $stackMock);
    }

    /**
     * @return void
     */
    public function testValidationMiddlewarePluginCantHandleSentMessageWillReturnUnhandledEnvelope(): void
    {
        // Arrange
        $envelope = $this->tester->haveEnvelope();

        $validationMiddlewarePlugin = $this->tester->createValidationMiddlewarePluginThatCanNotHandleAMessage();

        $stackMock = $this->tester->createStackMockWithNeverCalledNextMethod();

        // Act
        $handledEnvelope = $validationMiddlewarePlugin->handle($envelope, $stackMock);

        // Assert
        $this->assertSame($envelope, $handledEnvelope);
        $this->assertCount(0, $handledEnvelope->all());
    }

    /**
     * @return void
     */
    public function testValidationMiddlewarePluginCanHandleSentMessageWillReturnEnvelope(): void
    {
        // Arrange
        $envelope = $this->tester->haveEnvelope();

        $validationMiddlewarePlugin = $this->tester->createValidationMiddlewarePluginThatCanHandleAMessage();

        $stackMock = $this->tester->createStackMockWithOnceCalledNextMethod($envelope);

        // Act
        $handledEnvelope = $validationMiddlewarePlugin->handle($envelope, $stackMock);

        // Assert
        $this->assertSame($envelope, $handledEnvelope);
        $this->assertCount(0, $handledEnvelope->all());
    }

    /**
     * @return void
     */
    public function testValidationMiddlewarePluginCanHandleReceivedMessageWillReturnEnvelope(): void
    {
        // Arrange
        $envelope = $this->tester->haveEnvelopeWithReceivedStamp();

        $validationMiddlewarePlugin = $this->tester->createValidationMiddlewarePluginThatCanHandleAMessage(true);

        $stackMock = $this->tester->createStackMockWithOnceCalledNextMethod($envelope);

        // Act
        $handledEnvelope = $validationMiddlewarePlugin->handle($envelope, $stackMock);

        // Assert
        $this->assertSame($envelope, $handledEnvelope);
        $this->assertCount(1, $handledEnvelope->all(ReceivedStamp::class));
    }
}
