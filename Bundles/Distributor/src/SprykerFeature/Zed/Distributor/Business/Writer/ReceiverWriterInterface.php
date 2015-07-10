<?php

namespace SprykerFeature\Zed\Distributor\Business\Writer;

use Propel\Runtime\Exception\PropelException;

interface ReceiverWriterInterface
{

    /**
     * @param string $receiverKey
     *
     * @throws PropelException
     *
     * @return int
     */
    public function create($receiverKey);

}
