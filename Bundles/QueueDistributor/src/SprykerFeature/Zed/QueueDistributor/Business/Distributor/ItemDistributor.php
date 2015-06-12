<?php

namespace SprykerFeature\Zed\StoreDistributor\Business\Distributor;

use Generated\Shared\Transfer\QueueMessageTransfer;
use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\BatchIterator;
use SprykerFeature\Zed\Library\Propel\Formatter\PropelArraySetFormatter;
use SprykerFeature\Zed\QueueDistributor\Business\KeyBuilder\ChannelKeyBuilderInterface;
use SprykerFeature\Zed\QueueDistributor\Business\Marker\LastDistributionMarkerInterface;
use SprykerFeature\Zed\QueueDistributor\Business\Router\QueueApiRouterInterface;
use SprykerFeature\Zed\QueueDistributor\Dependency\Plugin\ProcessorPluginInterface;
use SprykerFeature\Zed\QueueDistributor\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerFeature\Zed\QueueDistributor\Dependency\Plugin\TypeReceiverPluginInterface;
use SprykerFeature\Zed\QueueDistributor\Persistence\QueueDistributorQueryContainerInterface;

class ItemDistributor implements ItemDistributorInterface
{

    /**
     * @var LastDistributionMarkerInterface
     */
    protected $distributionMarker;

    /**
     * @var QueueDistributorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var QueryExpanderPluginInterface[]
     */
    protected $queryPipeline = [];

    /**
     * @var ProcessorPluginInterface[]
     */
    protected $processorPipeline = [];

    /**
     * @var array
     */
    protected $typeReceivers = [];

    /**
     * @var QueueApiRouterInterface
     */
    protected $queueApiRouter;

    /**
     * @param LastDistributionMarkerInterface $distributionMarker
     * @param ChannelKeyBuilderInterface $channelKeyBuilder
     * @param QueueDistributorQueryContainerInterface $queryContainer
     * @param QueueApiRouterInterface $queueApiRouter
     */
    public function __construct(
        LastDistributionMarkerInterface $distributionMarker,
        ChannelKeyBuilderInterface $channelKeyBuilder,
        QueueDistributorQueryContainerInterface $queryContainer,
        QueueApiRouterInterface $queueApiRouter
    ) {
        $this->distributionMarker = $distributionMarker;
        $this->channelKeyBuilder = $channelKeyBuilder;
        $this->queryContainer = $queryContainer;
        $this->queueApiRouter = $queueApiRouter;
    }

    /**
     * @param string $type
     * @param MessengerInterface $messenger
     *
     * @throws \Exception
     */
    public function distributeByType($type, MessengerInterface $messenger)
    {
        $currentTimestamp = $this->getCurrentTimestamp();
        $lastTimestamp = $this->distributionMarker->getLastDistributionTimestampByType($type);
        $batchIterator = $this->getBatchIterator($type, $lastTimestamp);

        foreach ($batchIterator as $itemBatch) {
            $this->distributeItemBatch($type, $itemBatch);
        }

        $messenger->info(
            sprintf(
                '%s Items of type %s successfully distributed',
                $batchIterator->count(),
                $type
            )
        );

        $this->distributionMarker->setLastDistributionTimestampByType($type, $currentTimestamp);
    }

    /**
     * @param ProcessorPluginInterface $processor
     */
    public function addProcessor(ProcessorPluginInterface $processor)
    {
        $this->processorPipeline[$processor->getProcessableType()][] = $processor;
    }

    /**
     * @param QueryExpanderPluginInterface $queryExpander
     */
    public function addQueryExpander(QueryExpanderPluginInterface $queryExpander)
    {
        $this->queryPipeline[$queryExpander->getType()][] = $queryExpander;
    }

    /**
     * @param TypeReceiverPluginInterface $typeReceiver
     * @throws \Exception
     */
    public function addTypeReceiver(TypeReceiverPluginInterface $typeReceiver)
    {
        $type = $typeReceiver->getType();
        if (array_key_exists($type, $this->typeReceivers)) {
            throw new \Exception;
        }
        $this->typeReceivers[$type][] = $typeReceiver;
    }

    /**
     * @param $type
     * @param $itemBatch
     */
    protected function distributeItemBatch($type, $itemBatch)
    {
        $channel = $this->channelKeyBuilder->buildChannelKey($type);
        $messageReceiver = $this->getMessageReceiver($type);

        foreach ($itemBatch as $rawItem) {
            $processedItem = $this->processItem($type, $rawItem);
            $receiverList = $messageReceiver->getReceiverList($rawItem);

            $messageTransfer = new QueueMessageTransfer();
            $messageTransfer->setType($type);
            $messageTransfer->setChannel($channel);
            $messageTransfer->setPayload($processedItem);

            $this->queueApiRouter->routeMessage($messageTransfer, $receiverList);
        }
    }

    /**
     * @return string
     */
    protected function getCurrentTimestamp()
    {
        return microtime();
    }

    /**
     * @param string $type
     * @param string $lastTimestamp
     *
     * @return BatchIterator
     */
    protected function getBatchIterator($type, $lastTimestamp)
    {
        $chunkSize = 100;

        $query = $this->queryContainer->queryTouchedItemsByType($type, $lastTimestamp);
        $query->setFormatter(new PropelArraySetFormatter());

        foreach ($this->getQueryPipelineByType($type) as $queryExpander) {
            $query = $queryExpander->expandQuery($query);
        }

        return new BatchIterator($query, $chunkSize);
    }

    /**
     * @param string $type
     * @param array $processableItem
     *
     * @return array
     */
    protected function processItem($type, $processableItem)
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
     * @return ProcessorPluginInterface[]
     * @throws \Exception
     */
    protected function getProcessorPipelineByType($type)
    {
        if (array_key_exists($type, $this->typeReceivers)) {
            throw new \Exception;
        }

        return $this->processorPipeline[$type];
    }

    /**
     * @param string $type
     *
     * @return QueryExpanderPluginInterface[]
     * @throws \Exception
     */
    protected function getQueryPipelineByType($type)
    {
        if (array_key_exists($type, $this->typeReceivers)) {
            throw new \Exception;
        }

        return $this->queryPipeline[$type];
    }

    /**
     * @param string $type
     *
     * @throws \Exception
     * @return TypeReceiverPluginInterface
     */
    protected function getMessageReceiver($type)
    {
        if (array_key_exists($type, $this->typeReceivers)) {
            throw new \Exception;
        }

        return $this->typeReceivers[$type];
    }
}
