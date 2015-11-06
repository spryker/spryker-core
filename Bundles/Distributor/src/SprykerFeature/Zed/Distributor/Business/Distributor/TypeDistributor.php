<?php

namespace SprykerFeature\Zed\Distributor\Business\Distributor;

use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerFeature\Zed\Distributor\Business\Exception\QueryExpanderNotFoundException;
use SprykerEngine\Zed\Propel\Business\Formatter\PropelArraySetFormatter;
use SprykerFeature\Zed\Distributor\Business\Marker\LastDistributionMarkerInterface;
use SprykerFeature\Zed\Distributor\Dependency\Plugin\DistributorQueryExpanderPluginInterface;
use SprykerFeature\Zed\Distributor\Persistence\DistributorQueryContainerInterface;

class TypeDistributor
{

    /**
     * @var DistributorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var ItemDistributorInterface
     */
    protected $itemDistributor;

    /**
     * @var LastDistributionMarkerInterface
     */
    protected $distributionMarker;

    /**
     * @var array
     */
    protected $queryPipeline = [];

    /**
     * @param DistributorQueryContainerInterface $queryContainer
     * @param LastDistributionMarkerInterface $distributionMarker
     * @param ItemDistributorInterface $itemDistributor
     */
    public function __construct(
        DistributorQueryContainerInterface $queryContainer,
        LastDistributionMarkerInterface $distributionMarker,
        ItemDistributorInterface $itemDistributor
    ) {
        $this->queryContainer = $queryContainer;
        $this->distributionMarker = $distributionMarker;
        $this->itemDistributor = $itemDistributor;
    }

    /**
     * @param MessengerInterface $messenger
     * @param array $itemTypes
     */
    public function distributeData(MessengerInterface $messenger = null, $itemTypes = [])
    {
        if (empty($itemTypes)) {
            $itemTypes = $this->queryContainer->queryItemTypes()->find();
        }

        foreach ($itemTypes as $itemType) {
            $currentTimestamp = $this->getCurrentTimestamp();
            $lastTimestamp = $this->distributionMarker->getLastDistributionTimestampByType($itemType);
            $batchIterator = $this->getBatchIterator($itemType, $lastTimestamp);
            $batchCount = $batchIterator->count();

            $this->itemDistributor->distributeByType($itemType, $batchIterator);

            if ($messenger !== null) {
                $messenger->info(
                    sprintf(
                        '%s Items of type %s successfully distributed',
                        $batchCount,
                        $itemType
                    )
                );
            }

            $this->distributionMarker->setLastDistributionTimestampByType($itemType, $currentTimestamp);
        }
    }

    /**
     * @param DistributorQueryExpanderPluginInterface $queryExpander
     */
    public function addQueryExpander(DistributorQueryExpanderPluginInterface $queryExpander)
    {
        $this->queryPipeline[$queryExpander->getType()][] = $queryExpander;
    }

    /**
     * @return string
     */
    protected function getCurrentTimestamp()
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
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

        $query = $this->queryContainer->queryTouchedItemsByTypeKey($type, $lastTimestamp);
        $query->setFormatter(new PropelArraySetFormatter());

        foreach ($this->getQueryPipelineByType($type) as $queryExpander) {
            $query = $queryExpander->expandQuery($query);
        }

        return new BatchIterator($query, $chunkSize);
    }

    /**
     * @param string $type
     *
     * @throws \Exception
     *
     * @return DistributorQueryExpanderPluginInterface[]
     */
    protected function getQueryPipelineByType($type)
    {
        if (!array_key_exists($type, $this->queryPipeline)) {
            throw new QueryExpanderNotFoundException(
                sprintf(
                    'No QueryExpander found for "%s"',
                    $type
                )
            );
        }

        return $this->queryPipeline[$type];
    }

}
