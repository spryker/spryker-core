<?php

namespace Spryker\Zed\Synchronization\Business\QueueMessageManager;

use Spryker\Zed\Synchronization\Business\QueueMessageProcessor\QueueMessageProcessorInterface;
use Spryker\Zed\Synchronization\Business\Synchronization\SynchronizationInterface;
use Spryker\Zed\Synchronization\SynchronizationConfig;

class QueueMessageManager implements QueueMessageManagerInterface
{
    /**
     * @var \Spryker\Zed\Synchronization\SynchronizationConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\Synchronization\Business\QueueMessageProcessor\QueueMessageProcessorInterface
     */
    protected $plainMessageProcessor;

    /**
     * @var \Spryker\Zed\Synchronization\Business\QueueMessageProcessor\QueueMessageProcessorInterface
     */
    protected $bulkMessageProcessor;

    /**
     * @var \Spryker\Zed\Synchronization\Business\Synchronization\SynchronizationInterface
     */
    protected $synchronization;

    /**
     * @param \Spryker\Zed\Synchronization\SynchronizationConfig $config
     * @param \Spryker\Zed\Synchronization\Business\QueueMessageProcessor\QueueMessageProcessorInterface $plainMessageProcessor
     * @param \Spryker\Zed\Synchronization\Business\QueueMessageProcessor\QueueMessageProcessorInterface $bulkMessageProcessor
     * @param \Spryker\Zed\Synchronization\Business\Synchronization\SynchronizationInterface $synchronization
     */
    public function __construct(
        SynchronizationConfig $config,
        QueueMessageProcessorInterface $plainMessageProcessor,
        QueueMessageProcessorInterface $bulkMessageProcessor,
        SynchronizationInterface $synchronization
    ) {
        $this->config = $config;
        $this->plainMessageProcessor = $plainMessageProcessor;
        $this->bulkMessageProcessor = $bulkMessageProcessor;
        $this->synchronization = $synchronization;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    public function processMessages(array $queueMessageTransfers): array
    {
        if ($this->config->isBulkModeEnabled()) {
            return $this->runBulkSynchronization($queueMessageTransfers);
        }

        return $this->runPlainSynchronization($queueMessageTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    protected function runBulkSynchronization(array $queueMessageTransfers): array
    {
        $this->bulkMessageProcessor->setSynchronization($this->synchronization);

        return $this->bulkMessageProcessor->processMessages($queueMessageTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    protected function runPlainSynchronization(array $queueMessageTransfers): array
    {
        $this->plainMessageProcessor->setSynchronization($this->synchronization);

        return $this->plainMessageProcessor->processMessages($queueMessageTransfers);
    }
}
