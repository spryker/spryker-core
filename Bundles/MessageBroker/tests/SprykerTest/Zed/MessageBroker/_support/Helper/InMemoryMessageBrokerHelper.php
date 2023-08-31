<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\MessageBroker\MessageBrokerConstants;
use Spryker\Zed\MessageBroker\MessageBrokerDependencyProvider;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Zed\MessageBroker\Helper\Plugin\InMemoryMessageTransportPlugin;
use SprykerTest\Zed\MessageBroker\MessageBrokerBusinessTester;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\DependencyProviderHelperTrait;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\InMemoryTransport;
use Symfony\Component\Messenger\Transport\Serialization\PhpSerializer;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class InMemoryMessageBrokerHelper extends Module
{
    use BusinessHelperTrait;
    use DependencyProviderHelperTrait;
    use ConfigHelperTrait;

    /**
     * @var \Symfony\Component\Messenger\Transport\InMemoryTransport|null
     */
    protected ?InMemoryTransport $transport = null;

    /**
     * @var \SprykerTest\Zed\MessageBroker\Helper\Plugin\InMemoryMessageTransportPlugin|null
     */
    protected ?InMemoryMessageTransportPlugin $transportPlugin = null;

    /**
     * @var \SprykerTest\Zed\MessageBroker\MessageBrokerBusinessTester
     */
    protected MessageBrokerBusinessTester $tester;

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->getConfigHelper()->setConfig(MessageBrokerConstants::IS_ENABLED, true);

        parent::_before($test);

        $this->getDependencyProviderHelper()->setDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_SENDER, [$this->getInMemoryMessageTransportPlugin()]);
        $this->getDependencyProviderHelper()->setDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_RECEIVER, [$this->getInMemoryMessageTransportPlugin()]);
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        if ($this->transport) {
            $this->transport->reset();
        }

        parent::_after($test);
    }

    /**
     * Setup the MessageBroker to use the InMemory Transport for a specific message and the channel name the message will use.
     *
     * @param string $messageClassName The transfer class name
     * @param string $channelName The channel name we will use for processing
     *
     * @return void
     */
    public function setupMessageBroker(string $messageClassName, string $channelName): void
    {
        putenv(sprintf('SPRYKER_MESSAGE_TO_CHANNEL_MAP={"%s": "%s"}', str_replace('\\', '\\\\', $messageClassName), $channelName));
        putenv(sprintf('SPRYKER_CHANNEL_TO_TRANSPORT_MAP={"%s": "in-memory"}', $channelName));
        $this->setConfig(MessageBrokerConstants::CHANNEL_TO_RECEIVER_TRANSPORT_MAP, [
            $messageClassName => $channelName,
        ]);
        $this->setConfig(MessageBrokerConstants::CHANNEL_TO_SENDER_TRANSPORT_MAP, [
            $messageClassName => $channelName,
        ]);
    }

    /**
     * @param string $messageName
     *
     * @return void
     */
    public function assertMessageWasSent(string $messageName): void
    {
        $envelope = $this->getMessageByName($messageName);

        $this->assertNotNull($envelope, sprintf(
            'Expected to have a message with class name "%s" sent, but it was not sent. The following messages have been sent: "%s"',
            $messageName,
            implode(', ', $this->getAllSentMessageTransferNames()),
        ));
    }

    /**
     * @param string $messageName
     * @param array $requiredHeader
     *
     * @return void
     */
    public function assertMessageWasSentWithRequiredHeader(string $messageName, array $requiredHeader): void
    {
        $this->assertMessageWasSent($messageName);
        $envelope = $this->getMessageByName($messageName);

        /** @var \Spryker\Shared\Kernel\Transfer\AbstractTransfer $messageTransfer */
        $messageTransfer = $envelope->getMessage();

        if (!method_exists($messageTransfer, 'getMessageAttributes')) {
            $this->assertTrue(method_exists($messageTransfer, 'getMessageAttributes'), sprintf('Expeceted that the message "%s" has a MessageAttributesTransfer but that was not found. Either your message was not sent properly or something is wrong with your code.', $messageName));
        }
        $this->assertInstanceOf(MessageAttributesTransfer::class, $messageTransfer->getMessageAttributes(), sprintf('Expeceted that the message "%s" has a MessageAttributesTransfer but that was not found. Either your message was not sent properly or something is wrong with your code.', $messageName));

        /** @var \Spryker\Shared\Kernel\Transfer\AbstractTransfer $messageAttributesTransfer */
        $messageAttributesTransfer = $messageTransfer->getMessageAttributes();
        $messageAttributes = $messageAttributesTransfer->modifiedToArray();

        $missingHeader = array_diff($requiredHeader, $messageAttributes);

        $this->assertCount(0, $missingHeader, sprintf('Expected to have the following header "%s" in your message "%s" but these are missing "%s".', implode(', ', $requiredHeader), $messageName, implode(', ', $missingHeader)));
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $expectedMessageTransfer
     * @param array<string, string|int|array<string, string|int>> $requiredFields
     *
     * @return void
     */
    public function assertMessageWasSentWithRequiredFields(AbstractTransfer $expectedMessageTransfer, array $requiredFields): void
    {
        $this->assertMessageWasSent($expectedMessageTransfer::class);
        $envelope = $this->getMessageByName($expectedMessageTransfer::class);

        /** @var \Spryker\Shared\Kernel\Transfer\AbstractTransfer $messageTransfer */
        $messageTransfer = $envelope->getMessage();
        $propertyAccessor = new PropertyAccessor();

        $missingProperties = [];

        foreach ($requiredFields as $requiredField) {
            $propertyPath = sprintf('[%s]', implode('][', explode('.', $requiredField)));

            if (!$propertyAccessor->getValue($messageTransfer, $propertyPath)) {
                $missingProperties[] = $requiredField;
            }
        }

        $this->assertCount(0, $missingProperties, sprintf('Expected to have the following properties "%s" in your message "%s" but these are missing "%s".', implode(', ', $requiredFields), $expectedMessageTransfer::class, implode(', ', $missingProperties)));
    }

    /**
     * @param string $messageName
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getMessageTransferByMessageName(string $messageName): AbstractTransfer
    {
        $envelope = $this->getMessageByName($messageName);

        /** @var \Spryker\Shared\Kernel\Transfer\AbstractTransfer $messageTransfer */
        $messageTransfer = $envelope->getMessage();

        return $messageTransfer;
    }

    /**
     * @param string $messageName
     *
     * @return void
     */
    public function assertMessageWasNotSent(string $messageName): void
    {
        $envelope = $this->getMessageByName($messageName);

        $this->assertNull($envelope, sprintf('Expected not to have a message with class name "%s" sent, but it was sent.', $messageName));
    }

    /**
     * @return \SprykerTest\Zed\MessageBroker\Helper\Plugin\InMemoryMessageTransportPlugin
     */
    protected function getInMemoryMessageTransportPlugin(): InMemoryMessageTransportPlugin
    {
        if (!$this->transportPlugin) {
            $this->transport = new InMemoryTransport(new PhpSerializer());
            $this->transportPlugin = new InMemoryMessageTransportPlugin($this->transport);
        }

        return $this->transportPlugin;
    }

    /**
     * @param callable $callback
     * @param string $messageName
     *
     * @return void
     */
    public function assertMessagesByCallbackForMessageName(callable $callback, string $messageName): void
    {
        $messages = $this->getMessagesByName($messageName);

        $callback($messages);
    }

    /**
     * @return void
     */
    public function resetInMemoryMessages(): void
    {
        $this->transport->reset();
    }

    /**
     * @param string $messageName
     *
     * @return array<\Symfony\Component\Messenger\Envelope>
     */
    protected function getMessagesByName(string $messageName): array
    {
        $messages = [];

        if (!method_exists($this->transport, 'getSent')) {
            codecept_debug(sprintf('"%s" can only be used when the "%s" plugin is used.', __METHOD__, InMemoryMessageTransportPlugin::class));

            return $messages;
        }

        foreach ($this->transport->getSent() as $message) {
            $innerMessage = $message->getMessage();

            if ($innerMessage instanceof $messageName) {
                $messages[] = $message;
            }
        }

        return $messages;
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

        foreach ($this->transport->getSent() as $message) {
            $innerMessage = $message->getMessage();
            if ($innerMessage instanceof $messageName) {
                return $message;
            }
        }

        return null;
    }

    /**
     * @return array
     */
    protected function getAllSentMessageTransferNames(): array
    {
        $sentMessageTransferNames = [];

        if (!method_exists($this->transport, 'getSent')) {
            codecept_debug(sprintf('"%s" can only be used when the "%s" plugin is used.', __METHOD__, InMemoryMessageTransportPlugin::class));

            return $sentMessageTransferNames;
        }

        foreach ($this->transport->getSent() as $message) {
            $sentMessageTransferNames[] = get_class($message->getMessage());
        }

        return $sentMessageTransferNames;
    }

    /**
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface> $messageHandlerPlugins
     *
     * @return void
     */
    public function setMessageHandlerPlugins(array $messageHandlerPlugins): void
    {
        $this->getDependencyProviderHelper()->cleanUp();
        $this->getDependencyProviderHelper()->setDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_HANDLER, $messageHandlerPlugins);
    }
}
