<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageBrokerTestMessageTransfer;
use Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBroker\Business\Exception\CouldNotMapMessageToChannelNameException;
use Spryker\Zed\MessageBroker\Business\Exception\MissingMessageSenderException;
use Spryker\Zed\MessageBroker\Business\MessageAttributeProvider\MessageAttributeProviderInterface;
use Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker\CorrelationIdMessageAttributeProviderPlugin;
use Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker\TimestampMessageAttributeProviderPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface;
use SprykerTest\Shared\Propel\Helper\InstancePoolingHelperTrait;
use SprykerTest\Zed\MessageBroker\Helper\Plugin\SomethingHappenedMessageHandlerPlugin;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;

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
    use InstancePoolingHelperTrait;

    /**
     * @var string
     */
    public const CHANNEL_NAME = 'channel';

    /**
     * @var string
     */
    public const TRANSPORT = 'transport';

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
        $this->tester->setChannelToTransportMap(static::CHANNEL_NAME, static::TRANSPORT);

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
    public function testSendMessageWithoutConfiguredSenderThrowsAnException(): void
    {
        // Arrange
        $this->tester->setMessageToSenderChannelNameMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);

        $messageBrokerTestMessageTransfer = new MessageBrokerTestMessageTransfer();
        $messageBrokerTestMessageTransfer->setKey('value');

        // Expect
        $this->expectException(MissingMessageSenderException::class);

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
        $inMemoryMessageTransportPlugin = $this->tester->getInMemoryMessageTransportPlugin();

        $this->tester->setMessageToSenderChannelNameMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);
        $this->tester->setChannelToTransportMap(static::CHANNEL_NAME, $inMemoryMessageTransportPlugin->getTransportName());

        $this->tester->setMessageSenderPlugins([$inMemoryMessageTransportPlugin]);
        $this->tester->setMessageReceiverPlugins([$inMemoryMessageTransportPlugin]);

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

    /**
     * @return void
     */
    public function testSendMessageCanBeDisabledByConfiguration(): void
    {
        // Arrange
        $this->tester->disableMessageBroker();

        $messageBusMock = $this->createMock(MessageBusInterface::class);
        $messageBusMock->expects($this->never())->method('dispatch');
        $this->tester->mockFactoryMethod('createMessageBus', $messageBusMock);

        $mockMessageDecorator = $this->createMock(MessageAttributeProviderInterface::class);
        $mockMessageDecorator->expects($this->never())->method('provideMessageAttributes');
        $this->tester->mockFactoryMethod('createMessageDecorator', $mockMessageDecorator);

        $messageBrokerTestMessageTransfer = new MessageBrokerTestMessageTransfer();

        // Act
        $messageResponseTransfer = $this->tester->getFacade()->sendMessage($messageBrokerTestMessageTransfer);

        // Assert
        $this->assertNull($messageResponseTransfer->getBody(), 'Message body must be null to indicate nothing was sent');
    }

    /**
     * @return void
     */
    public function testStartWorkerDoesNothingWhenMessageBrokerIsDisabled(): void
    {
        // Arrange
        $this->tester->disableMessageBroker();

        // Expect
        $messageReceiverPlugin = $this->createMock(MessageReceiverPluginInterface::class);
        $messageReceiverPlugin
            ->expects($this->never())
            ->method('get');

        $this->tester->setMessageReceiverPlugins([
            $messageReceiverPlugin,
        ]);

        // Act
        $this->tester->getFacade()->startWorker(new MessageBrokerWorkerConfigTransfer());
    }

    /**
     * @return void
     */
    public function testMessageConsumeHandlingMessagesUsesDisabledPropelInstancePooling(): void
    {
        $this->enableInstancePooling();

        $inMemoryMessageTransportMock = $this->tester->getInMemoryMessageTransportPlugin();

        $this->tester->setMessageToSenderChannelNameMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);
        $this->tester->setChannelToTransportMap(static::CHANNEL_NAME, $inMemoryMessageTransportMock->getTransportName());

        $this->tester->setMessageSenderPlugins([$inMemoryMessageTransportMock]);
        $this->tester->setMessageReceiverPlugins([$inMemoryMessageTransportMock]);

        $this->tester->setMessageHandlerPlugins([
            new class ($this) extends AbstractPlugin implements MessageHandlerPluginInterface {
                /**
                 * @var \SprykerTest\Zed\MessageBroker\Business\MessageBrokerFacadeTest $tester
                 */
                protected MessageBrokerFacadeTest $tester;

                /**
                 * @param \SprykerTest\Zed\MessageBroker\Business\MessageBrokerFacadeTest $tester
                 */
                public function __construct(MessageBrokerFacadeTest $tester)
                {
                    $this->tester = $tester;
                }

                /**
                 * @return iterable
                 */
                public function handles(): iterable
                {
                    yield MessageBrokerTestMessageTransfer::class => function (): void {
                        $this->tester->assertFalse(
                            $this->tester->isInstancePoolingEnabled(),
                        );

                        // due to inability to assert on instance pooling being disabled in the worker using Mock
                        // because Envelope is a final class and cannot be mocked (cloned)
                        // we assert on the number of assertions made in the worker
                        // if assert fails it throws an exception and assertion count is not increased
                        $this->tester->addToAssertionCount(1);
                    };
                }
            },
        ]);

        $messageBrokerTestMessageTransfer = new MessageBrokerTestMessageTransfer();

        $messageBrokerWorkerConfigTransfer = (new MessageBrokerWorkerConfigTransfer())
            ->setLimit(1);

        // Act
        $this->tester->getFacade()->sendMessage($messageBrokerTestMessageTransfer);
        $this->tester->getFacade()->startWorker($messageBrokerWorkerConfigTransfer);

        $this->tester->assertTrue($this->isInstancePoolingEnabled(), 'Instance pooling should be enabled');
    }
}
