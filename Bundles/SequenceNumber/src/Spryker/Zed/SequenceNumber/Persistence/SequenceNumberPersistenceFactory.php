<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
