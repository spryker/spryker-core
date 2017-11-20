<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Communication\Table;

use Orm\Zed\Queue\Persistence\Map\SpyQueueProcessTableMap;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Queue\Persistence\QueueQueryContainerInterface;

class QueueProcessTable extends AbstractTable
{
    /**
     * @var \Spryker\Zed\Queue\Persistence\QueueQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Queue\Persistence\QueueQueryContainerInterface $queryContainer
     */
    public function __construct(QueueQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyQueueProcessTableMap::COL_ID_QUEUE_PROCESS => 'ID',
            SpyQueueProcessTableMap::COL_SERVER_ID => 'Server',
            SpyQueueProcessTableMap::COL_WORKER_PID => 'Worker PID',
            SpyQueueProcessTableMap::COL_PROCESS_PID => 'Queue PID',
            SpyQueueProcessTableMap::COL_QUEUE_NAME => 'Queue Name',
        ]);

        $config->setPageLength(10);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->queryContainer->queryProcesses();

        $queryResults = $this->runQuery($query, $config);
        $results = [];
        foreach ($queryResults as $queueProcess) {
            $results[] = [
                SpyQueueProcessTableMap::COL_ID_QUEUE_PROCESS => $queueProcess[SpyQueueProcessTableMap::COL_ID_QUEUE_PROCESS],
                SpyQueueProcessTableMap::COL_SERVER_ID => $queueProcess[SpyQueueProcessTableMap::COL_SERVER_ID],
                SpyQueueProcessTableMap::COL_WORKER_PID => $queueProcess[SpyQueueProcessTableMap::COL_WORKER_PID],
                SpyQueueProcessTableMap::COL_PROCESS_PID => $queueProcess[SpyQueueProcessTableMap::COL_PROCESS_PID],
                SpyQueueProcessTableMap::COL_QUEUE_NAME => $queueProcess[SpyQueueProcessTableMap::COL_QUEUE_NAME],
            ];
        }
        unset($queryResults);

        return $results;
    }
}
