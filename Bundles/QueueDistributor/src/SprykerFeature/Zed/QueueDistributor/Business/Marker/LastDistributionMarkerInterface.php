<?php

namespace SprykerFeature\Zed\QueueDistributor\Business\Marker;

interface LastDistributionMarkerInterface
{
    /**
     * @param string $typeKey
     *
     * @return \DateTime|string
     * @throws \Exception
     */
    public function getLastDistributionTimestampByType($typeKey);

    /**
     * @param $type
     * @param $timestamp
     */
    public function setLastDistributionTimestampByType($type, $timestamp);
}
