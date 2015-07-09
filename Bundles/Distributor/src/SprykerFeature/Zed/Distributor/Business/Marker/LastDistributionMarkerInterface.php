<?php

namespace SprykerFeature\Zed\Distributor\Business\Marker;

use SprykerFeature\Zed\Distributor\Business\Exception\ItemTypeDoesNotExistException;

interface LastDistributionMarkerInterface
{

    /**
     * @param string $typeKey
     *
     * @throws ItemTypeDoesNotExistException
     *
     * @return \DateTime|string
     */
    public function getLastDistributionTimestampByType($typeKey);

    /**
     * @param string $type
     * @param string $timestamp
     */
    public function setLastDistributionTimestampByType($type, $timestamp);

}
