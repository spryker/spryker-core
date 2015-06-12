<?php

namespace SprykerFeature\Zed\QueueDistributor\Business\Writer;

use Propel\Runtime\Exception\PropelException;

interface TypeWriterInterface
{
    /**
     * @param $typeKey
     *
     * @return int
     * @throws PropelException
     */
    public function create($typeKey);

    /**
     * @param $typeKey
     * @param $timestamp
     *
     * @return int
     * @throws PropelException
     */
    public function update($typeKey, $timestamp);
}
