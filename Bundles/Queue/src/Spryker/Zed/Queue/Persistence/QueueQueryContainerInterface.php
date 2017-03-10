<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Persistence;

use Orm\Zed\Queue\Persistence\Base\SpyQueueProcessQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface QueueQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @param string $serverId
     * @param string $queueName
     *
     * @return SpyQueueProcessQuery
     */
    public function queryProcessesByServerIdAndQueueName($serverId, $queueName);

    /**
     * @param string $serverId
     *
     * @return SpyQueueProcessQuery
     */
    public function queryProcessesByServerId($serverId);

    /**
     * @param array $processIds
     *
     * @return SpyQueueProcessQuery
     */
    public function queryProcessesByProcessIds(array $processIds);

    /**
     * @return SpyQueueProcessQuery
     */
    public function queryProcesses();
}
