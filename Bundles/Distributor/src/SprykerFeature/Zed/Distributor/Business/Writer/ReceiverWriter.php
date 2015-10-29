<?php

namespace SprykerFeature\Zed\Distributor\Business\Writer;

use Propel\Runtime\Exception\PropelException;
use Orm\Zed\Distributor\Persistence\SpyDistributorReceiver;

class ReceiverWriter implements ReceiverWriterInterface
{

    /**
     * @param string $receiverKey
     *
     * @throws PropelException
     *
     * @return int
     */
    public function create($receiverKey)
    {
        $distribution = new SpyDistributorReceiver();
        $distribution->setReceiverKey($receiverKey);
        $distribution->save();

        return $distribution->getIdDistributorReceiver();
    }

}
