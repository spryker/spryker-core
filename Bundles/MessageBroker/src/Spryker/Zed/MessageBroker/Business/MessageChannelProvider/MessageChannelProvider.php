<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\MessageChannelProvider;

use Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer;
use Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface;
use Spryker\Zed\MessageBroker\Business\Exception\CouldNotMapMessageToChannelNameException;
use Spryker\Zed\MessageBroker\MessageBrokerConfig;
use Symfony\Component\Messenger\Envelope;

class MessageChannelProvider implements MessageChannelProviderInterface
{
    /**
     * @var array<string, mixed>
     */
    protected static array $messageToChannelMapCache = [];

    /**
     * @var \Spryker\Zed\MessageBroker\MessageBrokerConfig
     */
    protected MessageBrokerConfig $config;

    /**
     * @var \Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface
     */
    protected ConfigFormatterInterface $configFormatter;

    /**
     * @var list<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\FilterMessageChannelPluginInterface>
     */
    protected array $filterMessageChannelPlugins;

    /**
     * @param \Spryker\Zed\MessageBroker\MessageBrokerConfig $config
     * @param \Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface $configFormatter
     * @param list<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\FilterMessageChannelPluginInterface> $filterMessageChannelPlugins
     */
    public function __construct(
        MessageBrokerConfig $config,
        ConfigFormatterInterface $configFormatter,
        array $filterMessageChannelPlugins
    ) {
        $this->config = $config;
        $this->configFormatter = $configFormatter;
        $this->filterMessageChannelPlugins = $filterMessageChannelPlugins;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return string|null
     */
    public function findChannelForMessage(Envelope $envelope): ?string
    {
        $messageName = get_class($envelope->getMessage());

        return $this->findChannelByMessageName($messageName);
    }

    /**
     * @param \Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer $messageBrokerWorkerConfigTransfer
     *
     * @return list<string>
     */
    public function getChannelsForConsuming(MessageBrokerWorkerConfigTransfer $messageBrokerWorkerConfigTransfer): array
    {
        $channels = $messageBrokerWorkerConfigTransfer->getChannels() ?: $this->config->getDefaultWorkerChannels();

        foreach ($this->filterMessageChannelPlugins as $filterMessageChannelPlugin) {
            $channels = $filterMessageChannelPlugin->filter($channels);
        }

        return array_unique(array_merge(
            $this->applyFilterMessageChannelPlugins($channels),
            $this->config->getSystemWorkerChannels(),
        ));
    }

    /**
     * @param string $messageName
     *
     * @throws \Spryker\Zed\MessageBroker\Business\Exception\CouldNotMapMessageToChannelNameException
     *
     * @return string|null
     */
    public function findChannelByMessageName(string $messageName): ?string
    {
        if (isset(static::$messageToChannelMapCache[$messageName])) {
            return static::$messageToChannelMapCache[$messageName];
        }

        $messageToChannelMap = $this->getMessageToChannelMap();

        if (!isset($messageToChannelMap[$messageName])) {
            if ($this->config->isEnabled()) {
                throw new CouldNotMapMessageToChannelNameException(sprintf(
                    'Could not map "%s" message class to a channel',
                    $messageName,
                ));
            }

            return static::$messageToChannelMapCache[$messageName] = null;
        }

        $channel = $messageToChannelMap[$messageName];

        if (in_array($channel, $this->config->getSystemWorkerChannels())) {
            return static::$messageToChannelMapCache[$messageName] = $channel;
        }

        return static::$messageToChannelMapCache[$messageName] = $this->applyFilterMessageChannelPlugins([$channel])[0] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMessageToChannelMap(): array
    {
        $messageToChannelMap = $this->config->getMessageToChannelMap();

        if (is_string($messageToChannelMap)) {
            $messageToChannelMap = $this->configFormatter->format($messageToChannelMap);
        }

        return $messageToChannelMap;
    }

    /**
     * @param list<string> $channels
     *
     * @return list<string>
     */
    protected function applyFilterMessageChannelPlugins(array $channels): array
    {
        foreach ($this->filterMessageChannelPlugins as $filterMessageChannelPlugin) {
            $channels = $filterMessageChannelPlugin->filter($channels);
        }

        return array_values($channels);
    }
}
