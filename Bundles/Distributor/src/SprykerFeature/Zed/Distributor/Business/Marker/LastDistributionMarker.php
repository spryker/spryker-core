<?php

namespace SprykerFeature\Zed\Distributor\Business\Marker;

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
     * @return \DateTime|string
     * @throws \Exception
     */
    public function getLastDistributionTimestampByType($typeKey)
    {
        $lastDistribution = $this->queryContainer
            ->queryTypeByKey($typeKey)
            ->findOne()
        ;

        if (empty($lastDistribution)) {
            throw new \Exception;
        }
        $lastDistributionTimestamp = $lastDistribution->getLastDistribution();
        if (!$lastDistributionTimestamp) {
            $lastDistributionTimestamp = \DateTime::createFromFormat('Y-m-d H:i:s', '2000-01-01 00:00:00');
        }

        return $lastDistributionTimestamp;
    }

    /**
     * @param $type
     * @param $timestamp
     */
    public function setLastDistributionTimestampByType($type, $timestamp)
    {
        $this->distributionWriter->update($type, $timestamp);
    }
}
