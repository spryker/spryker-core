<?php

namespace SprykerFeature\Zed\Distributor\Business\Marker;

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
     * @param string $type
     * @param string $timestamp
     */
    public function setLastDistributionTimestampByType($type, $timestamp);
}
