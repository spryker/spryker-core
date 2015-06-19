<?php

namespace SprykerFeature\Zed\Distributor\Business\Distributor;

use Generated\Shared\Distributor\QueueMessageInterface;
use Generated\Shared\Transfer\QueueMessageTransfer;
use SprykerFeature\Zed\Distributor\Business\Provider\ItemQueueProviderInterface;
use SprykerFeature\Zed\Distributor\Business\Router\MessageRouterInterface;
use SprykerFeature\Zed\Distributor\Dependency\Plugin\ItemProcessorPluginInterface;

class ItemDistributor implements ItemDistributorInterface
{
    const QUEUE_NAMES = 'queue_names';

    /**
     * @var ItemProcessorPluginInterface[]
     */
    protected $processorPipeline = [];

    /**
     * @var MessageRouterInterface
     */
    protected $messageRouter;

    /**
     * @var ItemQueueProviderInterface
     */
    protected $itemQueueProvider;

    /**
     * @param MessageRouterInterface $messageRouter
     * @param ItemQueueProviderInterface $itemQueueProvider
     */
    public function __construct(
        MessageRouterInterface $messageRouter,
        ItemQueueProviderInterface $itemQueueProvider
    ) {
        $this->messageRouter = $messageRouter;
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

            $this->messageRouter->routeMessage($messageTransfer, $queueNames);
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
