<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBrokerAws\Communication\Plugin\MessageBroker\Receiver;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageBrokerTestMessageTransfer;
use Spryker\Zed\MessageBrokerAws\Communication\Plugin\MessageBroker\Receiver\AwsSqsMessageReceiverPlugin;
use SprykerTest\Zed\MessageBrokerAws\MessageBrokerAwsCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBrokerAws
 * @group Communication
 * @group Plugin
 * @group MessageBroker
 * @group Receiver
 * @group AwsSqsMessageReceiverPluginTest
 * Add your own group annotations below this line
 */
class AwsSqsMessageReceiverPluginTest extends Unit
{
    /**
     * @var string
     */
    public const CHANNEL_NAME = 'channel';

    /**
     * @var \SprykerTest\Zed\MessageBrokerAws\MessageBrokerAwsCommunicationTester
     */
    protected MessageBrokerAwsCommunicationTester $tester;

    /**
     * @return void
     */
    public function testGetReturnsMessageWhenMessageExist(): void
    {
        $this->tester->haveSqsMessage();

        $this->tester->setChannelToReceiverTransportMap(static::CHANNEL_NAME, 'sqs');
        $this->tester->setSqsReceiverConfiguration();

        $awsMessageReceiverPlugin = new AwsSqsMessageReceiverPlugin();
        $awsMessageReceiverPlugin->setFacade($this->tester->getFacade());

        /** @var \Generator $result */
        $result = $awsMessageReceiverPlugin->getFromQueues([static::CHANNEL_NAME]);

        $currentMessage = $result->current();
        $this->assertNotNull($currentMessage, 'Expected to get a message but did not receive any.');
        $this->assertInstanceOf(MessageBrokerTestMessageTransfer::class, $currentMessage->getMessage());

        $awsMessageReceiverPlugin->ack($currentMessage);
    }

    /**
     * @return void
     */
    public function testRejectReceivedMessage(): void
    {
        $this->tester->haveSqsMessage();

        $this->tester->setChannelToReceiverTransportMap(static::CHANNEL_NAME, 'sqs');
        $this->tester->setSqsReceiverConfiguration();

        $awsMessageReceiverPlugin = new AwsSqsMessageReceiverPlugin();
        $awsMessageReceiverPlugin->setFacade($this->tester->getFacade());

        /** @var \Generator $result */
        $result = $awsMessageReceiverPlugin->getFromQueues([static::CHANNEL_NAME]);

        $currentMessage = $result->current();
        $this->assertInstanceOf(MessageBrokerTestMessageTransfer::class, $currentMessage->getMessage());

        $awsMessageReceiverPlugin->reject($currentMessage);
    }

    /**
     * @return void
     */
    public function testGetClientNameReturnNameOfTheSupportedClient(): void
    {
        // Arrange
        $awsMessageReceiverPlugin = new AwsSqsMessageReceiverPlugin();

        // Act
        $clientName = $awsMessageReceiverPlugin->getTransportName();

        // Assert
        $this->assertSame('sqs', $clientName, sprintf('Expected to get "sqs" as client name but got "%s"', $clientName));
    }
}
