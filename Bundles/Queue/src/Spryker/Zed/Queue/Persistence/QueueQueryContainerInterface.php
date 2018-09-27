<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface QueueQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param string $serverId
     * @param string $queueName
     *
     * @return \Orm\Zed\Queue\Persistence\SpyQueueProcessQuery
     */
    public function queryProcessesByServerIdAndQueueName($serverId, $queueName);

    /**
     * @api
     *
     * @param string $serverId
     *
     * @return \Orm\Zed\Queue\Persistence\SpyQueueProcessQuery
     */
    public function queryProcessesByServerId($serverId);

    /**
     * @api
     *
     * @param array $processIds
     *
     * @return \Orm\Zed\Queue\Persistence\SpyQueueProcessQuery
     */
    public function queryProcessesByProcessIds(array $processIds);

    /**
     * @api
     *
     * @return \Orm\Zed\Queue\Persistence\SpyQueueProcessQuery
     */
    public function queryProcesses();
}
