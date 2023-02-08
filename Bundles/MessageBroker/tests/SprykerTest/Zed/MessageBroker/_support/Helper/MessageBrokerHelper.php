<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Generated\Shared\DataBuilder\MessageAttributesBuilder;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer;
use Spryker\Zed\MessageBroker\Business\MessageBrokerBusinessFactory;
use Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface;
use Spryker\Zed\MessageBroker\Business\Worker\Worker;
use Spryker\Zed\MessageBroker\MessageBrokerDependencyProvider;
use Spryker\Zed\MessageBrokerAws\Communication\Plugin\MessageBroker\Receiver\AwsSqsMessageReceiverPlugin;
use Spryker\Zed\MessageBrokerAws\Communication\Plugin\MessageBroker\Sender\AwsSnsMessageSenderPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface;
use SprykerTest\Zed\MessageBroker\Helper\Plugin\InMemoryMessageTransportPlugin;
use SprykerTest\Zed\MessageBroker\Helper\Subscriber\StopWorkerWhenMessagesAreHandledEventDispatcherSubscriberPlugin;
use SprykerTest\Zed\MessageBroker\MessageBrokerBusinessTester;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\DependencyProviderHelperTrait;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\EventListener\StopWorkerOnTimeLimitListener;
use Symfony\Component\Messenger\Stamp\SentStamp;
use Symfony\Component\Messenger\Transport\InMemoryTransport;
use Symfony\Component\Messenger\Transport\Receiver\ReceiverInterface;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;
use Symfony\Component\Messenger\Transport\Serialization\PhpSerializer;
use Symfony\Component\Messenger\Transport\TransportInterface;

class MessageBrokerHelper extends Module
{
    use BusinessHelperTrait;
    use DependencyProviderHelperTrait;

    /**
     * @var array<string, mixed>
     */
    protected $receivedOptions = [];

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected EventDispatcherInterface $eventDispatcher;

    /**
     * @var \Symfony\Component\Messenger\Transport\Sender\SenderInterface|null
     */
    protected ?SenderInterface $sender = null;

    /**
     * @var \Symfony\Component\Messenger\Transport\Receiver\ReceiverInterface|null
     */
    protected ?ReceiverInterface $receiver = null;

    /**
     * @var \Symfony\Component\Messenger\Transport\TransportInterface|\Symfony\Contracts\Service\ResetInterface|null
     */
    protected ?TransportInterface $transport = null;

    /**
     * @var \SprykerTest\Zed\MessageBroker\Helper\Plugin\InMemoryMessageTransportPlugin|null
     */
    protected ?InMemoryMessageTransportPlugin $transportPlugin = null;

    /**
     * @var \SprykerTest\Zed\MessageBroker\MessageBrokerBusinessTester
     */
    protected ?MessageBrokerBusinessTester $tester = null;

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        parent::_before($test);

        $this->transportPlugin = null;

        putenv('SPRYKER_MESSAGE_TO_CHANNEL_MAP');
        putenv('SPRYKER_CHANNEL_TO_TRANSPORT_MAP');
        putenv('AOP_MESSAGE_BROKER_SNS_SENDER');
        putenv('AOP_MESSAGE_BROKER_SQS_RECEIVER');
    }

    /**
     * @param array<\Symfony\Component\EventDispatcher\EventSubscriberInterface> $eventSubscribers
     * @param bool $mockRunMethod
     *
     * @return void
     */
    public function mockWorker(array $eventSubscribers = [], bool $mockRunMethod = true): void
    {
        $factory = $this->getBusinessFactory();
        $receiverPlugins = $factory->getMessageReceiverPlugins();
        $messageBus = $factory->createMessageBus();
        $eventDispatcher = $this->eventDispatcher = $factory->getEventDispatcher();

        foreach ($eventSubscribers as $eventSubscriber) {
            $eventDispatcher->addSubscriber($eventSubscriber);
        }

        $constructorArguments = [
            $receiverPlugins,
            $messageBus,
            $eventDispatcher,
            $factory->getConfig(),
        ];

        $mockedMethods = $mockRunMethod ? [
            'run' => function (array $options): void {
                $this->receivedOptions = $options;
            },
        ] : [];

        $workerStub = Stub::construct(
            Worker::class,
            $constructorArguments,
            $mockedMethods,
            $this,
        );

        $this->getBusinessHelper()->mockFactoryMethod('createWorker', $workerStub);
    }

    /**
     * @param string $eventName
     *
     * @return void
     */
    public function assertEventDispatcherHasListenersForEvent(string $eventName): void
    {
        $this->assertTrue($this->eventDispatcher->hasListeners($eventName), sprintf('Expected to have listeners for the "%s" event but no listener found.', $eventName));
    }

    /**
     * @param string $eventName
     *
     * @return void
     */
    public function assertEventDispatcherDoesNotHasListenersForEvent(string $eventName): void
    {
        $this->assertFalse($this->eventDispatcher->hasListeners($eventName), sprintf('Expected not to have listeners for the "%s" event but listener found.', $eventName));
    }

    /**
     * @param string $optionName
     * @param mixed $expectedValue
     *
     * @return void
     */
    public function assertReceivedOption(string $optionName, $expectedValue): void
    {
        $this->assertArrayHasKey($optionName, $this->receivedOptions, sprintf('Option "%s" was not received by worker.', $optionName));

        $receivedOption = $this->receivedOptions[$optionName];
        $receivedOption = is_array($receivedOption) ? implode(', ', $receivedOption) : $receivedOption;

        $expectedValue = is_array($expectedValue) ? implode(', ', $expectedValue) : $expectedValue;

        $this->assertSame(
            $expectedValue,
            $receivedOption,
            sprintf(
                'Expected option value "%s" for option "%s" but got "%s"',
                $expectedValue,
                $optionName,
                $receivedOption,
            ),
        );
    }

    /**
     * @return \Spryker\Zed\MessageBroker\Business\MessageBrokerBusinessFactory
     */
    protected function getBusinessFactory(): MessageBrokerBusinessFactory
    {
        /** @var \Spryker\Zed\MessageBroker\Business\MessageBrokerBusinessFactory $messageBrokerFactory */
        $messageBrokerFactory = $this->getBusinessHelper()->getFactory('MessageBroker');

        return $messageBrokerFactory;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     * @param string $senderAlias
     *
     * @return void
     */
    public function assertMessageWasSentWithSender(Envelope $envelope, string $senderAlias): void
    {
        /** @var \Symfony\Component\Messenger\Stamp\SentStamp $sentStamp */
        $sentStamp = $envelope->last(SentStamp::class);

        // Assert
        $this->assertNotNull($sentStamp, sprintf('Expected to have a "%s" stamp but it was not found.', SentStamp::class));
        $this->assertSame('in-memory', $sentStamp->getSenderAlias(), sprintf('Expected that message was sent with the "in-memory" sender but was sent with "%s".', $sentStamp->getSenderAlias() ?? ''));
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     * @param array $senderAlias
     *
     * @return void
     */
    public function assertMessageWasSentWithSenders(Envelope $envelope, array $senderAlias): void
    {
        $sentStamps = $envelope->all(SentStamp::class);

        // Assert
        $this->assertNotNull($sentStamps, sprintf('Expected to have a "%s" stamp but it was not found.', SentStamp::class));

        /** @var \Symfony\Component\Messenger\Stamp\SentStamp $sentStamp */
        foreach ($sentStamps as $sentStamp) {
            $stampSenderAlias = $sentStamp->getSenderAlias();
            $this->assertTrue(in_array($stampSenderAlias, $senderAlias), sprintf('Expected that message was sent with the "%s" but was not.', $stampSenderAlias));
        }
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     * @param string $stampClass
     *
     * @return void
     */
    public function assertMessageHasStamp(Envelope $envelope, string $stampClass): void
    {
        $stamp = $envelope->last($stampClass);

        // Assert
        $this->assertNotNull($stamp, sprintf('Expected to have a "%s" stamp but it was not found.', $stampClass));
    }

    /**
     * @param string $messageClassName
     * @param string $channelName
     *
     * @return void
     */
    public function setMessageToChannelNameMap(string $messageClassName, string $channelName): void
    {
        putenv(sprintf('SPRYKER_MESSAGE_TO_CHANNEL_MAP={"%s": "%s"}', str_replace('\\', '\\\\', $messageClassName), $channelName));
    }

    /**
     * @param string $messageClassName
     * @param string $channelName
     *
     * @return void
     */
    public function setMessageToSenderChannelNameMap(string $messageClassName, string $channelName): void
    {
        putenv(sprintf('SPRYKER_MESSAGE_TO_CHANNEL_MAP={"%s": "%s"}', str_replace('\\', '\\\\', $messageClassName), $channelName));
    }

    /**
     * @param string $channelName
     * @param string $clientName
     *
     * @return void
     */
    public function setChannelToTransportMap(string $channelName, string $clientName): void
    {
        putenv(sprintf('SPRYKER_CHANNEL_TO_TRANSPORT_MAP={"%s": "%s"}', $channelName, $clientName));
    }

    /**
     * @return \SprykerTest\Zed\MessageBroker\Helper\Plugin\InMemoryMessageTransportPlugin
     */
    public function getInMemoryMessageTransportPlugin(): InMemoryMessageTransportPlugin
    {
        if (!$this->transportPlugin) {
            $this->transport = new InMemoryTransport(new PhpSerializer());
            $this->transportPlugin = new InMemoryMessageTransportPlugin($this->transport);
        }

        return $this->transportPlugin;
    }

    /**
     * @param string $topic
     *
     * @return \Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface
     */
    public function createSnsSenderPlugin(string $topic = 'arn:aws:sns:eu-central-1:000000000000:message-broker'): MessageSenderPluginInterface
    {
        putenv(sprintf('SPRYKER_MESSAGE_BROKER_SNS_SENDER_CONFIG={"endpoint": "http://localhost.localstack.cloud:4566", "accessKeyId": "test", "accessKeySecret": "test", "region": "eu-central-1", "topic": "%s"}', $topic));

        return $this->sender = new AwsSnsMessageSenderPlugin();
    }

    /**
     * @param string $queueName
     *
     * @return \Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface
     */
    public function createAwsSqsReceiverPlugin(string $queueName = 'message-broker'): MessageReceiverPluginInterface
    {
        putenv(sprintf('SPRYKER_MESSAGE_BROKER_SQS_RECEIVER_CONFIG={"endpoint": "http://localhost.localstack.cloud:4566", "accessKeyId": "test", "accessKeySecret": "test", "region": "eu-central-1", "queue_name": "%s"}', $queueName));

        return $this->receiver = new AwsSqsMessageReceiverPlugin();
    }

    /**
     * @param string $messageName
     * @param string $headerName
     * @param callable|null $callable Use this to get content of the header in your test.
     *
     * @return void
     */
    public function assertMessageHasHeader(
        string $messageName,
        string $headerName,
        ?callable $callable = null
    ): void {
        if (!$this->transportPlugin) {
            codecept_debug(sprintf('"%s" can only be used when the "%s" plugin is used.', __METHOD__, InMemoryMessageTransportPlugin::class));

            return;
        }

        $message = $this->getMessageByName($messageName);
        $this->assertNotNull($message, sprintf('Message "%s" was not sent.', $messageName));

        $stamp = $message->last($headerName);
        $this->assertNotNull($stamp, sprintf('Message "%s" does not have the header "%s".', $messageName, $headerName));

        if ($callable) {
            $callable($stamp);
        }
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    public function getMessageAttributesTransfer(array $seedData = []): MessageAttributesTransfer
    {
        return (new MessageAttributesBuilder($seedData))->build();
    }

    /**
     * @param string $messageName
     *
     * @return \Symfony\Component\Messenger\Envelope|null
     */
    protected function getMessageByName(string $messageName): ?Envelope
    {
        if (!method_exists($this->transport, 'getSent')) {
            codecept_debug(sprintf('"%s" can only be used when the "%s" plugin is used.', __METHOD__, InMemoryMessageTransportPlugin::class));

            return null;
        }

        foreach ($this->transport->getSent() as $key => $message) {
            $innerMessage = $message->getMessage();
            if ($innerMessage instanceof $messageName) {
                return $message;
            }
        }

        return null;
    }

    /**
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface> $messageReceiverPlugins
     *
     * @return void
     */
    public function setMessageReceiverPlugins(array $messageReceiverPlugins): void
    {
        $this->getDependencyProviderHelper()->setDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_RECEIVER, $messageReceiverPlugins);
    }

    /**
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface> $messageSenderPlugins
     *
     * @return void
     */
    public function setMessageSenderPlugins(array $messageSenderPlugins): void
    {
        $this->getDependencyProviderHelper()->setDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_SENDER, $messageSenderPlugins);
    }

    /**
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface> $messageHandlerPlugins
     *
     * @return void
     */
    public function setMessageHandlerPlugins(array $messageHandlerPlugins): void
    {
        $this->getDependencyProviderHelper()->setDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_HANDLER, $messageHandlerPlugins);
    }

    /**
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageAttributeProviderPluginInterface> $messageDecoratorPlugins
     *
     * @return void
     */
    public function setMessageDecoratorPlugins(array $messageDecoratorPlugins): void
    {
        $this->getDependencyProviderHelper()->setDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_ATTRIBUTE_PROVIDER, $messageDecoratorPlugins);
    }

    /**
     * @return void
     */
    public function consumeMessages(): void
    {
        // Add Event subscriber that will stop the Worker when all messages are handled or when a time limit was reached.
        // This prevents the worker from running forever.
        $factory = $this->getBusinessFactory();
        $eventDispatcher = $factory->getEventDispatcher();

        $eventSubscribers = [
            new StopWorkerWhenMessagesAreHandledEventDispatcherSubscriberPlugin(),
            new StopWorkerOnTimeLimitListener(10),
        ];

        foreach ($eventSubscribers as $eventSubscriber) {
            $eventDispatcher->addSubscriber($eventSubscriber);
        }

        $this->getBusinessHelper()->mockFactoryMethod('getEventDispatcher', $eventDispatcher, 'MessageBroker');

        $messageBrokerWorkerConfigTransfer = new MessageBrokerWorkerConfigTransfer();
        $messageBrokerWorkerConfigTransfer->setChannels([]);

        $this->getFacade()->startWorker($messageBrokerWorkerConfigTransfer);
    }

    /**
     * @return \Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface
     */
    protected function getFacade(): MessageBrokerFacadeInterface
    {
        /** @var \Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface $messageBrokerFacade */
        $messageBrokerFacade = $this->getBusinessHelper()->getFacade('MessageBroker');

        return $messageBrokerFacade;
    }

    /**
     * @return \Spryker\Zed\MessageBroker\Business\MessageBrokerBusinessFactory
     */
    protected function getFactory(): MessageBrokerBusinessFactory
    {
        /** @var \Spryker\Zed\MessageBroker\Business\MessageBrokerBusinessFactory $messageBrokerFactory */
        $messageBrokerFactory = $this->getBusinessHelper()->getFactory('MessageBroker');

        return $messageBrokerFactory;
    }
}
