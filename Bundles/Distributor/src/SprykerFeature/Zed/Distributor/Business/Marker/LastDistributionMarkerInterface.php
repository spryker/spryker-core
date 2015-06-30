<?php

namespace SprykerFeature\Zed\Distributor\Business\Marker;

use SprykerFeature\Zed\Distributor\Business\Exception\ItemTypeDoesNotExistException;

interface LastDistributionMarkerInterface
{
    /**
     * @param string $typeKey
     *
     * @return \DateTime|string
     * @throws ItemTypeDoesNotExistException
     */
    public function getLastDistributionTimestampByType($typeKey);

    /**
     * @param string $type
     * @param string $timestamp
     */
    public function setLastDistributionTimestampByType($type, $timestamp);
}
