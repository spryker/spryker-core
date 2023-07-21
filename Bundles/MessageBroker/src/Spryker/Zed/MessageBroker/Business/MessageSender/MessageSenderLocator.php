<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\MessageSender;

use Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface;
use Spryker\Zed\MessageBroker\Business\Exception\CouldNotMapMessageToChannelNameException;
use Spryker\Zed\MessageBroker\MessageBrokerConfig;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginAcceptClientInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface;

class MessageSenderLocator implements SendersLocatorInterface
{
    /**
     * @var \Spryker\Zed\MessageBroker\MessageBrokerConfig
     */
    protected MessageBrokerConfig $config;

    /**
     * @var \Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface
     */
    protected ConfigFormatterInterface $configFormatter;

    /**
     * @var array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface>
     */
    protected array $messageSenderPlugins = [];

    /**
     * @param \Spryker\Zed\MessageBroker\MessageBrokerConfig $config
     * @param \Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface $configFormatter
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface> $messageSenderPlugins
     */
    public function __construct(MessageBrokerConfig $config, ConfigFormatterInterface $configFormatter, array $messageSenderPlugins)
    {
        $this->config = $config;
        $this->configFormatter = $configFormatter;
        $this->messageSenderPlugins = $messageSenderPlugins;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return array<string, \Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface>
     */
    public function getSenders(Envelope $envelope): iterable
    {
        return $this->getMessageSenderPlugins($envelope);
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return array<string, \Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface>
     */
    protected function getMessageSenderPlugins(Envelope $envelope): iterable
    {
        $clientName = $this->getSenderClientNameForMessage($envelope);

        $clientMessageSenderPlugins = [];

        foreach ($this->messageSenderPlugins as $messageSenderPlugin) {
            if ($clientName === null || $clientName === $messageSenderPlugin->getTransportName() || ($messageSenderPlugin instanceof MessageSenderPluginAcceptClientInterface && $messageSenderPlugin->acceptClient($clientName))) {
                $clientMessageSenderPlugins[$messageSenderPlugin->getTransportName()] = $messageSenderPlugin;
            }
        }

        return $clientMessageSenderPlugins;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return string|null
     */
    protected function getSenderClientNameForMessage(Envelope $envelope): ?string
    {
        $channel = $this->getChannelForMessageClass($envelope);

        $channelToSenderClientMap = $this->config->getChannelToTransportMap();

        if (is_string($channelToSenderClientMap)) {
            $channelToSenderClientMap = $this->configFormatter->format($channelToSenderClientMap);
        }

        if (isset($channelToSenderClientMap[$channel])) {
            return $channelToSenderClientMap[$channel];
        }

        return null;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @throws \Spryker\Zed\MessageBroker\Business\Exception\CouldNotMapMessageToChannelNameException
     *
     * @return string
     */
    protected function getChannelForMessageClass(Envelope $envelope): string
    {
        $messageToChannelMap = $this->config->getMessageToChannelMap();

        if (is_string($messageToChannelMap)) {
            $messageToChannelMap = $this->configFormatter->format($messageToChannelMap);
        }

        $messageClass = get_class($envelope->getMessage());

        if (isset($messageToChannelMap[$messageClass])) {
            return $messageToChannelMap[$messageClass];
        }

        throw new CouldNotMapMessageToChannelNameException(sprintf(
            'Could not map "%s" message class to a channel',
            $messageClass,
        ));
    }
}
