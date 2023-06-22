<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Worker;

use Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Zed\MessageBroker\MessageBrokerConfig;
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
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected EventDispatcherInterface $eventDispatcher;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var \Symfony\Component\Messenger\Worker
     */
    protected SymfonyWorker $worker;

    /**
     * @var \Spryker\Zed\MessageBroker\MessageBrokerConfig
     */
    protected MessageBrokerConfig $config;

    /**
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface> $messageReceiverPlugins
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
        $receivers = [];

        foreach ($messageReceiverPlugins as $messageReceiverPlugin) {
            $receivers[$messageReceiverPlugin->getTransportName()] = $messageReceiverPlugin;
        }

        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;

        $this->worker = new SymfonyWorker($receivers, $bus, $eventDispatcher, $logger);
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

        $this->run($options);
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return void
     */
    protected function run(array $options): void
    {
        $this->worker->run($options);
    }
}
