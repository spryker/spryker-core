<?php

namespace SprykerFeature\Zed\Distributor\Business\Marker;

use SprykerFeature\Zed\Distributor\Business\Exception\ItemTypeDoesNotExistException;
use SprykerFeature\Zed\Distributor\Business\Writer\ItemTypeWriterInterface;
use SprykerFeature\Zed\Distributor\Persistence\DistributorQueryContainerInterface;

class LastDistributionMarker implements LastDistributionMarkerInterface
{

    /**
     * @var DistributorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var ItemTypeWriterInterface
     */
    protected $distributionWriter;

    /**
     * @param DistributorQueryContainerInterface $queryContainer
     * @param ItemTypeWriterInterface $distributionWriter
     */
    public function __construct(
        DistributorQueryContainerInterface $queryContainer,
        ItemTypeWriterInterface $distributionWriter
    ) {
        $this->queryContainer = $queryContainer;
        $this->distributionWriter = $distributionWriter;
    }

    /**
     * @param string $typeKey
     *
     * @throws ItemTypeDoesNotExistException
     *
     * @return \DateTime|string
     */
    public function getLastDistributionTimestampByType($typeKey)
    {
        $itemType = $this->queryContainer
            ->queryTypeByKey($typeKey)
            ->findOne()
        ;

        if (empty($itemType)) {
            throw new ItemTypeDoesNotExistException();
        }
        $lastDistributionTimestamp = $itemType->getLastDistribution();
        if (!$lastDistributionTimestamp) {
            $lastDistributionTimestamp = \DateTime::createFromFormat('Y-m-d H:i:s', '2000-01-01 00:00:00');
        }

        return $lastDistributionTimestamp;
    }

    /**
     * @param string $type
     * @param string $timestamp
     */
    public function setLastDistributionTimestampByType($type, $timestamp)
    {
        $this->distributionWriter->update($type, $timestamp);
    }

}
