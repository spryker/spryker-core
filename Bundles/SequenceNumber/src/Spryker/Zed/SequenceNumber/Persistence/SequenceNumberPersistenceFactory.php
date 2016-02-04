<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\SequenceNumber\Persistence;

use Orm\Zed\SequenceNumber\Persistence\SpySequenceNumberQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\SequenceNumber\SequenceNumberConfig getConfig()
 * @method \Spryker\Zed\SequenceNumber\Persistence\SequenceNumberQueryContainer getQueryContainer()
 */
class SequenceNumberPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\SequenceNumber\Persistence\SpySequenceNumberQuery
     */
    public function createSequenceNumberQuery()
    {
        return SpySequenceNumberQuery::create();
    }

}
