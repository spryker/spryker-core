<?php

namespace SprykerFeature\Zed\QueueDistributor\Business\Distributor;

use Generated\Shared\QueueDistributor\QueueMessageInterface;
use Generated\Shared\Transfer\QueueMessageTransfer;
use SprykerFeature\Zed\QueueDistributor\Business\Provider\ItemQueueProviderInterface;
use SprykerFeature\Zed\QueueDistributor\Business\Router\QueueRouterInterface;
use SprykerFeature\Zed\QueueDistributor\Dependency\Plugin\ItemProcessorPluginInterface;

class ItemDistributor implements ItemDistributorInterface
{
    const QUEUE_NAMES = 'queue_names';

    /**
     * @var ItemProcessorPluginInterface[]
     */
    protected $processorPipeline = [];

    /**
     * @var QueueRouterInterface
     */
    protected $queueRouter;

    /**
     * @var ItemQueueProviderInterface
     */
    protected $itemQueueProvider;

    /**
     * @param QueueRouterInterface $queueApiRouter
     * @param ItemQueueProviderInterface $itemQueueProvider
     */
    public function __construct(
        QueueRouterInterface $queueApiRouter,
        ItemQueueProviderInterface $itemQueueProvider
    ) {
        $this->queueRouter = $queueApiRouter;
        $this->itemQueueProvider = $itemQueueProvider;
    }

    /**
     * @param string $itemType
     * @param BatchIteratorInterface $batchIterator
     */
    public function distributeByType($itemType, BatchIteratorInterface $batchIterator)
    {
        foreach ($batchIterator as $itemBatch) {
            $this->distributeItemBatch($itemType, $itemBatch);
        }
    }

    /**
     * @param ItemProcessorPluginInterface $processor
     */
    public function addItemProcessor(ItemProcessorPluginInterface $processor)
    {
        $this->processorPipeline[$processor->getProcessableType()][] = $processor;
    }

    /**
     * @param string $type
     * @param array $itemBatch
     */
    protected function distributeItemBatch($type, array $itemBatch)
    {
        $messageTransfer = $this->getMessageTransfer();
        $queueNames = $this->itemQueueProvider->getAllQueueForType($type);

        foreach ($itemBatch as $rawItem) {
            $processedItem = $this->processItem($type, $rawItem);

            $messageTransfer->setType($type);
            $messageTransfer->setPayload($processedItem);

            if (isset($processedItem[self::QUEUE_NAMES])) {
                $queueNames = $processedItem[self::QUEUE_NAMES];
            }

            $this->queueRouter->routeMessage($messageTransfer, $queueNames);
        }
    }

    /**
     * @param string $type
     * @param array $processableItem
     *
     * @return array
     */
    protected function processItem($type, array $processableItem)
    {
        $processedItem = [];

        foreach ($this->getProcessorPipelineByType($type) as $processor) {
            $processedItem = $processor->processItem($processableItem);
        }

        return $processedItem;
    }

    /**
     * @param string $type
     *
     * @return ItemProcessorPluginInterface[]
     * @throws \Exception
     */
    protected function getProcessorPipelineByType($type)
    {
        if (array_key_exists($type, $this->processorPipeline)) {
            throw new \Exception;
        }

        return $this->processorPipeline[$type];
    }

    /**
     * @return QueueMessageInterface
     */
    protected function getMessageTransfer()
    {
        return new QueueMessageTransfer();
    }
}
