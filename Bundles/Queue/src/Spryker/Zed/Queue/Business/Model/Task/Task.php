<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Model\Task;

use Generated\Shared\Transfer\QueueMessageTransfer;
use Generated\Shared\Transfer\QueueOptionTransfer;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Zed\Queue\Business\Exception\MissingQueueConfigException;
use Spryker\Zed\Queue\Business\Exception\MissingQueuePluginException;
use Spryker\Zed\Queue\Dependency\Plugin\QueueMessageProcessorInterface;
use Spryker\Zed\Queue\QueueConfig;

class Task implements TaskInterface
{

    const DEFAULT_CONSUMER_CONFIG_QUEUE_NAME = 'default';

    /**
     * @var QueueClientInterface
     */
    protected $client;

    /**
     * @var QueueConfig
     */
    protected $queueConfig;

    /**
     * @var QueueMessageProcessorInterface[]
     */
    protected $messageProcessorPlugins;

    /**
     * @param QueueClientInterface $client
     * @param QueueConfig $queueConfig
     * @param QueueMessageProcessorInterface[] $messageProcessorPlugins
     */
    public function __construct(QueueClientInterface $client, QueueConfig $queueConfig, array $messageProcessorPlugins)
    {
        $this->client = $client;
        $this->queueConfig = $queueConfig;
        $this->messageProcessorPlugins = $messageProcessorPlugins;
    }

    /**
     * @param string $queueName
     *
     * @return void
     */
    public function run($queueName)
    {
        $processorPlugin = $this->getQueueProcessorPlugin($queueName);
        $queueOptionTransfer = $this->getQueueReceiverConfigTransfer($queueName, $processorPlugin->getChunkSize());
        $messages = $this->receiveMessages($queueOptionTransfer);

        if ($messages !== null) {
            $processedMessages = $processorPlugin->processMessages($messages);
            $this->postProcessMessages($processedMessages);
        }
    }

    /**
     * @param string $queueName
     *
     * @throws MissingQueuePluginException
     *
     * @return QueueMessageProcessorInterface
     */
    protected function getQueueProcessorPlugin($queueName)
    {
        if (!array_key_exists($queueName, $this->messageProcessorPlugins)) {
            throw new MissingQueuePluginException(
                sprintf(
                    'There is no message processor plugin registered for this queue: %s, ' .
                    'you can fix this error by adding it in QueueDependencyProvider',
                    $queueName
                )
            );
        }

        return $this->messageProcessorPlugins[$queueName];
    }


    /**
     * @param string $queueName
     * @param int $chunkSize
     *
     * @throws MissingQueueConfigException
     *
     * @return QueueOptionTransfer
     */
    protected function getQueueReceiverConfigTransfer($queueName, $chunkSize)
    {
        $queueOptionTransfer = $this->queueConfig->getQueueReceiverConfig($queueName);
        if ($queueOptionTransfer === null) {
            throw new MissingQueueConfigException(
                sprintf(
                    'No queue configuration was found for this queue: %s, ',
                    'you can fix this error by adding it in QueueConfig',
                    $queueName
                )
            );
        }

        $queueOptionTransfer->setChunkSize($chunkSize);
        if ($queueOptionTransfer->getQueueName() === self::DEFAULT_CONSUMER_CONFIG_QUEUE_NAME) {
            $queueOptionTransfer->setQueueName($queueName);
        }

        return $queueOptionTransfer;
    }

    /**
     * @param QueueOptionTransfer $queueOptionTransfer
     *
     * @return QueueMessageTransfer[]
     */
    protected function receiveMessages(QueueOptionTransfer $queueOptionTransfer)
    {
        return $this->client->receiveMessages($queueOptionTransfer);
    }

    /**
     * @param QueueMessageTransfer[] $processedMessages
     *
     * @return void
     */
    protected function postProcessMessages(array $processedMessages)
    {
        foreach ($processedMessages as $processedMessage) {
            if ($processedMessage->getAcknowledge()) {
                $this->client->acknowledge($processedMessage);
            }

            if ($processedMessage->getReject()) {
                $this->client->reject($processedMessage);
            }

            if ($processedMessage->getHasError()) {
                $this->client->handleErrorMessage($processedMessage);
            }
        }
    }
}
