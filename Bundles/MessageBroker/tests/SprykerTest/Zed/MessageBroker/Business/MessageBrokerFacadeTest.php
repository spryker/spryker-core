<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageBrokerTestMessageTransfer;
use Spryker\Zed\MessageBroker\Business\Exception\CouldNotMapMessageToChannelNameException;
use Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker\CorrelationIdMessageAttributeProviderPlugin;
use Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker\TimestampMessageAttributeProviderPlugin;
use SprykerTest\Zed\MessageBroker\Plugin\SomethingHappenedMessageHandlerPlugin;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBroker
 * @group Business
 * @group Facade
 * @group MessageBrokerFacadeTest
 * Add your own group annotations below this line
 */
class MessageBrokerFacadeTest extends Unit
{
    /**
     * @var string
     */
    public const CHANNEL_NAME = 'channel';

    /**
     * @var \SprykerTest\Zed\MessageBroker\MessageBrokerBusinessTester
     */
    protected $tester;

    /**
     * @var string|null
     */
    protected ?string $correlationId = null;

    /**
     * @return void
     */
    public function testSendMessageWithoutConfiguredHandlerThrowsAnException(): void
    {
        // Arrange
        $this->tester->setMessageToSenderChannelNameMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);

        $messageBrokerTestMessageTransfer = new MessageBrokerTestMessageTransfer();
        $messageBrokerTestMessageTransfer->setKey('value');

        // Expect
        $this->expectException(NoHandlerForMessageException::class);

        // Act
        $this->tester->getFacade()->sendMessage($messageBrokerTestMessageTransfer);
    }

    /**
     * @return void
     */
    public function testSendMessageWithoutConfiguredMessageToChannelMapThrowsAnException(): void
    {
        // Arrange
        $this->tester->setMessageHandlerPlugins([new SomethingHappenedMessageHandlerPlugin()]);

        $messageBrokerTestMessageTransfer = new MessageBrokerTestMessageTransfer();
        $messageBrokerTestMessageTransfer->setKey('value');

        // Expect
        $this->expectException(CouldNotMapMessageToChannelNameException::class);

        // Act
        $this->tester->getFacade()->sendMessage($messageBrokerTestMessageTransfer);
    }

    /**
     * @return void
     */
    public function testSendMessageAddsMessageAttributesToMessage(): void
    {
        // Arrange
        $this->tester->setMessageToSenderChannelNameMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);

        $this->tester->setMessageSenderPlugins([$this->tester->getInMemoryMessageTransportPlugin()]);
        $this->tester->setMessageReceiverPlugins([$this->tester->getInMemoryMessageTransportPlugin()]);

        $this->tester->setMessageHandlerPlugins([new SomethingHappenedMessageHandlerPlugin()]);
        $this->tester->setMessageDecoratorPlugins([
            new CorrelationIdMessageAttributeProviderPlugin(),
            new TimestampMessageAttributeProviderPlugin(),
        ]);

        $messageBrokerTestMessageTransfer = new MessageBrokerTestMessageTransfer();
        $messageBrokerTestMessageTransfer->setKey('value');

        // Act
        $messageResponseTransfer = $this->tester->getFacade()->sendMessage($messageBrokerTestMessageTransfer);

        //Assert
        /** @var \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer */
        $messageAttributesTransfer = $messageResponseTransfer->getBody()->getMessage()->getMessageAttributes();
        $this->assertIsString($messageAttributesTransfer->getCorrelationId());
        $this->assertIsString($messageAttributesTransfer->getTimestamp());
    }

    /**
     * @return void
     */
    public function testSendMessageSendsMessageWithSpecifiedClient(): void
    {
        // Arrange
        $this->tester->setChannelToTransportMap(static::CHANNEL_NAME, 'in-memory');
        $this->tester->setMessageToSenderChannelNameMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);

        $this->tester->setMessageSenderPlugins([
            $this->tester->createSnsSenderPlugin(), // First sender should not be used.
            $this->tester->getInMemoryMessageTransportPlugin(),
        ]);

        $this->tester->setMessageHandlerPlugins([new SomethingHappenedMessageHandlerPlugin()]);

        $messageBrokerTestMessageTransfer = new MessageBrokerTestMessageTransfer();
        $messageBrokerTestMessageTransfer->setKey('value');

        // Act
        $messageResponseTransfer = $this->tester->getFacade()->sendMessage($messageBrokerTestMessageTransfer);

        //Assert
        $this->tester->assertMessageWasSentWithSender($messageResponseTransfer->getBody(), 'in-memory');
    }
}
