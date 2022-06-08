<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Spryker\Zed\MessageBroker\MessageBrokerDependencyProvider;
use SprykerTest\Zed\MessageBroker\Helper\Plugin\InMemoryMessageTransportPlugin;
use SprykerTest\Zed\MessageBroker\MessageBrokerBusinessTester;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\DependencyProviderHelperTrait;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\InMemoryTransport;
use Symfony\Component\Messenger\Transport\Serialization\PhpSerializer;

class InMemoryMessageBrokerHelper extends Module
{
    use BusinessHelperTrait;
    use DependencyProviderHelperTrait;

    /**
     * @var \Symfony\Component\Messenger\Transport\InMemoryTransport|null
     */
    protected ?InMemoryTransport $transport;

    /**
     * @var \SprykerTest\Zed\MessageBroker\Helper\Plugin\InMemoryMessageTransportPlugin|null
     */
    protected ?InMemoryMessageTransportPlugin $transportPlugin;

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
        parent::_before($test);

        $this->transport = null;
        $this->transportPlugin = null;

        putenv('SPRYKER_MESSAGE_TO_CHANNEL_MAP');
        putenv('SPRYKER_CHANNEL_TO_TRANSPORT_MAP');

        $this->getDependencyProviderHelper()->setDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_SENDER, [$this->getInMemoryMessageTransportPlugin()]);
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
    }

    /**
     * @param string $messageName
     *
     * @return void
     */
    public function assertMessageWasSent(string $messageName): void
    {
        $envelope = $this->getMessageByName($messageName);

        $this->assertNotNull($envelope, sprintf('Expected to have a message with class name "%s" sent, but it was not sent.', $messageName));
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
}
