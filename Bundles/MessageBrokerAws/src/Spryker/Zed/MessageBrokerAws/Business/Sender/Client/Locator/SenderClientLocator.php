<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Locator;

use Spryker\Zed\MessageBrokerAws\Business\Config\ConfigFormatterInterface;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\SenderClientInterface;
use Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig;

class SenderClientLocator implements SenderClientLocatorInterface
{
    /**
     * @var \Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig
     */
    protected MessageBrokerAwsConfig $config;

    /**
     * @var array<string, \Spryker\Zed\MessageBrokerAws\Business\Sender\Client\SenderClientInterface>
     */
    protected array $senderClients = [];

    /**
     * @var \Spryker\Zed\MessageBrokerAws\Business\Config\ConfigFormatterInterface
     */
    protected ConfigFormatterInterface $configFormatter;

    /**
     * @param \Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig $config
     * @param array<string, \Spryker\Zed\MessageBrokerAws\Business\Sender\Client\SenderClientInterface> $senderClients
     * @param \Spryker\Zed\MessageBrokerAws\Business\Config\ConfigFormatterInterface $configFormatter
     */
    public function __construct(MessageBrokerAwsConfig $config, array $senderClients, ConfigFormatterInterface $configFormatter)
    {
        $this->config = $config;
        $this->senderClients = $senderClients;
        $this->configFormatter = $configFormatter;
    }

    /**
     * @param string $channelName
     *
     * @return \Spryker\Zed\MessageBrokerAws\Business\Sender\Client\SenderClientInterface
     */
    public function getSenderClientByChannelName(string $channelName): SenderClientInterface
    {
        $channelToSenderClientMap = $this->config->getChannelToSenderTransportMap();

        if (is_string($channelToSenderClientMap)) {
            $channelToSenderClientMap = $this->configFormatter->format($channelToSenderClientMap);
        }

        return $this->senderClients[$channelToSenderClientMap[$channelName]];
    }
}
