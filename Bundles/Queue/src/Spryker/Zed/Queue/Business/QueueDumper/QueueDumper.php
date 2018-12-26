<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\QueueDumper;

use Generated\Shared\Transfer\QueueDumpRequestTransfer;
use Generated\Shared\Transfer\QueueDumpResponseTransfer;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Zed\Queue\Business\Exception\MissingQueuePluginException;
use Spryker\Zed\Queue\Dependency\Service\QueueToUtilEncodingServiceInterface;
use Spryker\Zed\Queue\QueueConfig;

class QueueDumper implements QueueDumperInterface
{
    /**
     * @var \Spryker\Client\Queue\QueueClientInterface
     */
    protected $queueClient;

    /**
     * @var \Spryker\Zed\Queue\QueueConfig
     */
    protected $queueConfig;

    /**
     * @var \Spryker\Zed\Queue\Dependency\Service\QueueToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\Queue\Dependency\Plugin\QueueMessageProcessorPluginInterface[]
     */
    protected $messageProcessorPlugins;

    /**
     * @param \Spryker\Client\Queue\QueueClientInterface $queueClient
     * @param \Spryker\Zed\Queue\QueueConfig $queueConfig
     * @param \Spryker\Zed\Queue\Dependency\Service\QueueToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\Queue\Dependency\Plugin\QueueMessageProcessorPluginInterface[] $messageProcessorPlugins
     */
    public function __construct(
        QueueClientInterface $queueClient,
        QueueConfig $queueConfig,
        QueueToUtilEncodingServiceInterface $utilEncodingService,
        array $messageProcessorPlugins
    ) {
        $this->queueClient = $queueClient;
        $this->queueConfig = $queueConfig;
        $this->utilEncodingService = $utilEncodingService;
        $this->messageProcessorPlugins = $messageProcessorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueDumpRequestTransfer $queueDumpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QueueDumpResponseTransfer
     */
    public function dumpQueue(QueueDumpRequestTransfer $queueDumpRequestTransfer): QueueDumpResponseTransfer
    {
        $queueDumpResponseTransfer = $this->createQueueDumpResponseTransfer();
        $queueName = $queueDumpRequestTransfer->getQueueName();
        $limit = $queueDumpRequestTransfer->getLimit();
        $format = $queueDumpRequestTransfer->getFormat();
        $acknowledge = $queueDumpRequestTransfer->getAcknowledge();

        $this->checkQueuePluginProcessor($queueName);

        $queueReceiveMessageTransfers = $this->receiveQueueMessages($queueName, $limit);

        if ($queueReceiveMessageTransfers === []) {
            return $queueDumpResponseTransfer;
        }

        $data = $this->transformQueueReceiveMessageTransfersToArray($queueReceiveMessageTransfers);

        $queueDumpResponseTransfer->setMessage(
            $this->utilEncodingService->encodeToFormat($data, $format)
        );

        $this->postProcessMessages($queueReceiveMessageTransfers, $acknowledge);

        return $queueDumpResponseTransfer;
    }

    /**
     * @param string $queueName
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    protected function receiveQueueMessages(string $queueName, int $limit): array
    {
        $queueOptions = $this->queueConfig->getQueueReceiverOption($queueName);

        return $this->queueClient->receiveMessages($queueName, $limit, $queueOptions);
    }

    /**
     * @return \Generated\Shared\Transfer\QueueDumpResponseTransfer
     */
    protected function createQueueDumpResponseTransfer(): QueueDumpResponseTransfer
    {
        return new QueueDumpResponseTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $messages
     *
     * @return array
     */
    protected function transformQueueReceiveMessageTransfersToArray(array $messages): array
    {
        $data = [];

        foreach ($messages as $message) {
            $data[] = $message->toArray();
        }

        return $data;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueReceiveMessageTransfers
     * @param bool $acknowledge
     *
     * @return void
     */
    protected function postProcessMessages(array $queueReceiveMessageTransfers, bool $acknowledge)
    {
        if (!$acknowledge) {
            return;
        }

        foreach ($queueReceiveMessageTransfers as $queueReceiveMessageTransfer) {
            $this->queueClient->acknowledge($queueReceiveMessageTransfer);
        }
    }

    /**
     * @param string $queueName
     *
     * @throws \Spryker\Zed\Queue\Business\Exception\MissingQueuePluginException
     *
     * @return void
     */
    protected function checkQueuePluginProcessor(string $queueName): void
    {
        if (!array_key_exists($queueName, $this->messageProcessorPlugins)) {
            throw new MissingQueuePluginException(
                sprintf(
                    'There is no queue registered with this queue: %s. Please check the queue name and try again.',
                    $queueName
                )
            );
        }
    }
}
