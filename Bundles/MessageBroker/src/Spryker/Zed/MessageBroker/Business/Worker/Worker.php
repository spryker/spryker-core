<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Worker;

use Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Zed\MessageBroker\MessageBrokerConfig;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\EventListener\StopWorkerOnFailureLimitListener;
use Symfony\Component\Messenger\EventListener\StopWorkerOnMemoryLimitListener;
use Symfony\Component\Messenger\EventListener\StopWorkerOnMessageLimitListener;
use Symfony\Component\Messenger\EventListener\StopWorkerOnTimeLimitListener;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Worker as SymfonyWorker;

class Worker implements WorkerInterface
{
    /**
     * @var list<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface>
     */
    protected array $messageReceiverPlugins;

    /**
     * @var \Symfony\Component\Messenger\MessageBusInterface
     */
    protected MessageBusInterface $bus;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected EventDispatcherInterface $eventDispatcher;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var \Spryker\Zed\MessageBroker\MessageBrokerConfig
     */
    protected MessageBrokerConfig $config;

    /**
     * @param list<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface> $messageReceiverPlugins
     * @param \Symfony\Component\Messenger\MessageBusInterface $bus
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Zed\MessageBroker\MessageBrokerConfig $config
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        array $messageReceiverPlugins,
        MessageBusInterface $bus,
        EventDispatcherInterface $eventDispatcher,
        MessageBrokerConfig $config,
        LoggerInterface $logger
    ) {
        $this->messageReceiverPlugins = $messageReceiverPlugins;
        $this->bus = $bus;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer $messageBrokerWorkerConfigTransfer
     *
     * @return void
     */
    public function runWorker(MessageBrokerWorkerConfigTransfer $messageBrokerWorkerConfigTransfer): void
    {
        if (!$this->config->isEnabled()) {
            $this->logger->error('Message broker is not enabled. No workers will be started.');

            return;
        }

        if ($messageBrokerWorkerConfigTransfer->getLimit()) {
            $this->eventDispatcher->addSubscriber(new StopWorkerOnMessageLimitListener($messageBrokerWorkerConfigTransfer->getLimit(), $this->logger));
        }

        if ($messageBrokerWorkerConfigTransfer->getFailureLimit()) {
            $this->eventDispatcher->addSubscriber(new StopWorkerOnFailureLimitListener($messageBrokerWorkerConfigTransfer->getFailureLimit(), $this->logger));
        }

        if ($messageBrokerWorkerConfigTransfer->getMemoryLimit()) {
            $this->eventDispatcher->addSubscriber(new StopWorkerOnMemoryLimitListener($messageBrokerWorkerConfigTransfer->getMemoryLimit(), $this->logger));
        }

        if ($messageBrokerWorkerConfigTransfer->getTimeLimit()) {
            $this->eventDispatcher->addSubscriber(new StopWorkerOnTimeLimitListener($messageBrokerWorkerConfigTransfer->getTimeLimit(), $this->logger));
        }

        $channels = $messageBrokerWorkerConfigTransfer->getChannels();
        if (!$channels) {
            $channels = $this->config->getDefaultWorkerChannels();
        }

        $options = [
            'queues' => $channels,
        ];

        if ($messageBrokerWorkerConfigTransfer->getSleep()) {
            $options['sleep'] = $messageBrokerWorkerConfigTransfer->getSleep();
        }

        $receivers = $this->prepareReceiverPlugins($channels);
        $this->run($options, $receivers);
    }

    /**
     * @param array<int, string> $channels
     *
     * @return array<string, \Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface>
     */
    protected function prepareReceiverPlugins(array $channels): array
    {
        $receivers = [];
        $receiverTransports = $this->getReceiverTransports($channels);

        foreach ($this->messageReceiverPlugins as $messageReceiverPlugin) {
            if ($this->isMessageReceiverPluginAllowed($messageReceiverPlugin, $receiverTransports)) {
                $receivers[$messageReceiverPlugin->getTransportName()] = $messageReceiverPlugin;
            }
        }

        return $receivers;
    }

    /**
     * @param \Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface $messageReceiverPlugin
     * @param array<int, mixed> $receiverTransports
     *
     * @return bool
     */
    protected function isMessageReceiverPluginAllowed(
        MessageReceiverPluginInterface $messageReceiverPlugin,
        array $receiverTransports
    ): bool {
        if (!$receiverTransports) {
            return true;
        }

        foreach ($receiverTransports as $receiverTransport) {
            if ($messageReceiverPlugin->getTransportName() === $receiverTransport) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<int, string> $channels
     *
     * @return array<int, mixed>
     */
    protected function getReceiverTransports(array $channels): array
    {
        $channelToReceiverTransportMap = $this->config->getChannelToReceiverTransportMap();

        $transports = [];
        foreach ($channels as $channel) {
            if (!isset($channelToReceiverTransportMap[$channel])) {
                continue;
            }

            if (is_array($channelToReceiverTransportMap[$channel])) {
                $transports = array_merge($transports, $channelToReceiverTransportMap[$channel]);

                continue;
            }

            $transports[] = $channelToReceiverTransportMap[$channel];
        }

        return array_unique($transports);
    }

    /**
     * @param array<string, mixed> $options
     * @param array<string, \Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface> $receivers
     *
     * @return void
     */
    protected function run(array $options, array $receivers): void
    {
        $worker = new SymfonyWorker($receivers, $this->bus, $this->eventDispatcher, $this->logger);
        $worker->run($options);
    }
}
