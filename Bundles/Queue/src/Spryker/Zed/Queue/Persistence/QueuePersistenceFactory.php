<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Persistence;

use Orm\Zed\Queue\Persistence\Base\SpyQueueProcessQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

class QueuePersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\Queue\Persistence\SpyQueueProcessQuery
     */
    public function createSpyQueueProcessQuery()
    {
        return SpyQueueProcessQuery::create();
    }
}
