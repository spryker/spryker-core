<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Sender;

use Spryker\Zed\MessageBrokerAws\Business\Config\ConfigFormatterInterface;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Locator\SenderClientLocatorInterface;
use Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig;
use Symfony\Component\Messenger\Envelope;

class Sender implements SenderInterface
{
    /**
     * @var \Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig
     */
    protected MessageBrokerAwsConfig $config;

    /**
     * @var \Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Locator\SenderClientLocatorInterface
     */
    protected SenderClientLocatorInterface $senderClientResolver;

    /**
     * @var \Spryker\Zed\MessageBrokerAws\Business\Config\ConfigFormatterInterface
     */
    protected ConfigFormatterInterface $configFormatter;

    /**
     * @param \Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig $config
     * @param \Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Locator\SenderClientLocatorInterface $senderClientResolver
     * @param \Spryker\Zed\MessageBrokerAws\Business\Config\ConfigFormatterInterface $configFormatter
     */
    public function __construct(MessageBrokerAwsConfig $config, SenderClientLocatorInterface $senderClientResolver, ConfigFormatterInterface $configFormatter)
    {
        $this->config = $config;
        $this->senderClientResolver = $senderClientResolver;
        $this->configFormatter = $configFormatter;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function send(Envelope $envelope): Envelope
    {
        $channelName = $this->getSenderChannelNameForMessage($envelope);

        if (!$channelName) {
            return $envelope;
        }

        return $this->senderClientResolver
            ->getSenderClientByChannelName($channelName)
            ->send($envelope);
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return string|null
     */
    public function getSenderChannelNameForMessage(Envelope $envelope): ?string
    {
        $messageToChannelMap = $this->config->getMessageToChannelMap();

        if (is_string($messageToChannelMap)) {
            $messageToChannelMap = $this->configFormatter->format($messageToChannelMap);
        }

        return $messageToChannelMap[get_class($envelope->getMessage())] ?? null;
    }
}
