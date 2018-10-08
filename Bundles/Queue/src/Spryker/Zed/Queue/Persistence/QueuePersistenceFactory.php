<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Persistence;

use Orm\Zed\Queue\Persistence\Base\SpyQueueProcessQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Queue\QueueConfig getConfig()
 * @method \Spryker\Zed\Queue\Persistence\QueueQueryContainerInterface getQueryContainer()
 */
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
