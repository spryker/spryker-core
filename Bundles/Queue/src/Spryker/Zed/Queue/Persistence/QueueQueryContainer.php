<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Persistence;

use Orm\Zed\Queue\Persistence\Map\SpyQueueProcessTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Queue\Persistence\QueuePersistenceFactory getFactory()
 */
class QueueQueryContainer extends AbstractQueryContainer implements QueueQueryContainerInterface
{
    /**
     * @api
     *
     * @param string $serverId
     * @param string $queueName
     *
     * @return \Orm\Zed\Queue\Persistence\SpyQueueProcessQuery
     */
    public function queryProcessesByServerIdAndQueueName($serverId, $queueName)
    {
        return $this->getFactory()
            ->createSpyQueueProcessQuery()
            ->filterByServerId($serverId)
            ->filterByQueueName($queueName)
            ->select(SpyQueueProcessTableMap::COL_PROCESS_PID);
    }

    /**
     * @api
     *
     * @param array $processIds
     *
     * @return \Orm\Zed\Queue\Persistence\SpyQueueProcessQuery
     */
    public function queryProcessesByProcessIds(array $processIds)
    {
        return $this->getFactory()
            ->createSpyQueueProcessQuery()
            ->filterByProcessPid($processIds, Criteria::IN);
    }

    /**
     * @api
     *
     * @param string $serverId
     *
     * @return \Orm\Zed\Queue\Persistence\SpyQueueProcessQuery
     */
    public function queryProcessesByServerId($serverId)
    {
        return $this->getFactory()
            ->createSpyQueueProcessQuery()
            ->filterByServerId($serverId)
            ->select(SpyQueueProcessTableMap::COL_PROCESS_PID);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Queue\Persistence\SpyQueueProcessQuery
     */
    public function queryProcesses()
    {
        return $this->getFactory()->createSpyQueueProcessQuery();
    }
}
