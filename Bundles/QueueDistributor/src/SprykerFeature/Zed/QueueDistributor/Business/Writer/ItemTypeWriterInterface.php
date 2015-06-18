<?php

namespace SprykerFeature\Zed\QueueDistributor\Business\Writer;

use Propel\Runtime\Exception\PropelException;

interface ItemTypeWriterInterface
{
    /**
     * @param string $typeKey
     *
     * @return int
     * @throws PropelException
     */
    public function create($typeKey);

    /**
     * @param string $typeKey
     * @param string $timestamp
     *
     * @return int
     * @throws PropelException
     */
    public function update($typeKey, $timestamp);
}
