<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\MessageSender;

use Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface;
use Spryker\Zed\MessageBroker\Business\Exception\MissingMessageSenderException;
use Spryker\Zed\MessageBroker\Business\MessageChannelProvider\MessageChannelProviderInterface;
use Spryker\Zed\MessageBroker\Business\Receiver\Stamp\ChannelNameStamp;
use Spryker\Zed\MessageBroker\MessageBrokerConfig;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginAcceptClientInterface;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface;
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
     * @var \Spryker\Zed\MessageBroker\Business\MessageChannelProvider\MessageChannelProviderInterface
     */
    protected MessageChannelProviderInterface $messageChannelProvider;

    /**
     * @param \Spryker\Zed\MessageBroker\MessageBrokerConfig $config
     * @param \Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface $configFormatter
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface> $messageSenderPlugins
     * @param \Spryker\Zed\MessageBroker\Business\MessageChannelProvider\MessageChannelProviderInterface $messageChannelProvider
     */
    public function __construct(
        MessageBrokerConfig $config,
        ConfigFormatterInterface $configFormatter,
        array $messageSenderPlugins,
        MessageChannelProviderInterface $messageChannelProvider
    ) {
        $this->config = $config;
        $this->configFormatter = $configFormatter;
        $this->messageSenderPlugins = $messageSenderPlugins;
        $this->messageChannelProvider = $messageChannelProvider;
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
     * @throws \Spryker\Zed\MessageBroker\Business\Exception\MissingMessageSenderException
     *
     * @return array<string, \Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface>
     */
    protected function getMessageSenderPlugins(Envelope $envelope): iterable
    {
        $clientNames = $this->getSenderClientNamesForMessage($envelope);

        if (!$clientNames) {
            /** @var \Spryker\Zed\MessageBroker\Business\Receiver\Stamp\ChannelNameStamp $channelNameStamp */
            $channelNameStamp = $envelope->last(ChannelNameStamp::class);

            throw new MissingMessageSenderException(sprintf(
                'Message sender for channel "%s" is missing',
                $channelNameStamp->getChannelName(),
            ));
        }

        $clientMessageSenderPlugins = [];
        foreach ($this->messageSenderPlugins as $messageSenderPlugin) {
            if ($this->isMessageSenderPluginAllowed($messageSenderPlugin, $clientNames)) {
                $clientMessageSenderPlugins[$messageSenderPlugin->getTransportName()] = $messageSenderPlugin;
            }
        }

        return $clientMessageSenderPlugins;
    }

    /**
     * @param \Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface $messageSenderPlugin
     * @param iterable<int, string> $clientNames
     *
     * @return bool
     */
    protected function isMessageSenderPluginAllowed(
        MessageSenderPluginInterface $messageSenderPlugin,
        iterable $clientNames
    ): bool {
        foreach ($clientNames as $clientName) {
            if (
                $clientName === $messageSenderPlugin->getTransportName()
                || ($messageSenderPlugin instanceof MessageSenderPluginAcceptClientInterface && $messageSenderPlugin->acceptClient($clientName))
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return array<int, string>
     */
    protected function getSenderClientNamesForMessage(Envelope $envelope): iterable
    {
        $channel = $this->messageChannelProvider->findChannelForMessage($envelope);

        if (!$channel) {
            return [];
        }

        $channelToSenderClientMap = $this->config->getChannelToTransportMap();

        if (is_string($channelToSenderClientMap)) {
            $channelToSenderClientMap = $this->configFormatter->format($channelToSenderClientMap);
        }

        $channelToSenderTransportMap = $this->config->getChannelToSenderTransportMap();
        $channelToSenderClientMap = array_merge_recursive($channelToSenderClientMap, $channelToSenderTransportMap);

        if (isset($channelToSenderClientMap[$channel])) {
            if (is_array($channelToSenderClientMap[$channel])) {
                return $channelToSenderClientMap[$channel];
            }

            return [$channelToSenderClientMap[$channel]];
        }

        return [];
    }
}
