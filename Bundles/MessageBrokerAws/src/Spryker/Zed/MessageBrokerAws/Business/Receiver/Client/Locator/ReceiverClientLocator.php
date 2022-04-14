<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Receiver\Client\Locator;

use Spryker\Zed\MessageBrokerAws\Business\Config\ConfigFormatterInterface;
use Spryker\Zed\MessageBrokerAws\Business\Receiver\Client\ReceiverClientInterface;
use Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig;

class ReceiverClientLocator implements ReceiverClientLocatorInterface
{
    /**
     * @var \Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig
     */
    protected MessageBrokerAwsConfig $config;

    /**
     * @var array<string, \Spryker\Zed\MessageBrokerAws\Business\Receiver\Client\ReceiverClientInterface>
     */
    protected array $receiverClients = [];

    /**
     * @var \Spryker\Zed\MessageBrokerAws\Business\Config\ConfigFormatterInterface
     */
    protected ConfigFormatterInterface $configFormatter;

    /**
     * @param \Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig $config
     * @param array<string, \Spryker\Zed\MessageBrokerAws\Business\Receiver\Client\ReceiverClientInterface> $receiverClients
     * @param \Spryker\Zed\MessageBrokerAws\Business\Config\ConfigFormatterInterface $configFormatter
     */
    public function __construct(MessageBrokerAwsConfig $config, array $receiverClients, ConfigFormatterInterface $configFormatter)
    {
        $this->config = $config;
        $this->receiverClients = $receiverClients;
        $this->configFormatter = $configFormatter;
    }

    /**
     * @param string $channelName
     *
     * @return \Spryker\Zed\MessageBrokerAws\Business\Receiver\Client\ReceiverClientInterface
     */
    public function getReceiverClientByChannelName(string $channelName): ReceiverClientInterface
    {
        $channelToReceiverClientMap = $this->config->getChannelToReceiverTransportMap();

        if (is_string($channelToReceiverClientMap)) {
            $channelToReceiverClientMap = $this->configFormatter->format($channelToReceiverClientMap);
        }

        return $this->receiverClients[$channelToReceiverClientMap[$channelName]];
    }
}
