<?php

namespace SprykerFeature\Zed\Distributor\Business\Writer;

use Propel\Runtime\Exception\PropelException;

interface ItemTypeWriterInterface
{

    /**
     * @param string $typeKey
     *
     * @throws PropelException
     *
     * @return int
     */
    public function create($typeKey);

    /**
     * @param string $typeKey
     * @param string $timestamp
     *
     * @throws PropelException
     *
     * @return int
     */
    public function update($typeKey, $timestamp);

}
