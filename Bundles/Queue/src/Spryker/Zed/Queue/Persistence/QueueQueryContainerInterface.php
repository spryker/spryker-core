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
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $serverId
     * @param string $queueName
     *
     * @return \Orm\Zed\Queue\Persistence\SpyQueueProcessQuery
     */
    public function queryProcessesByServerIdAndQueueName($serverId, $queueName);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $serverId
     *
     * @return \Orm\Zed\Queue\Persistence\SpyQueueProcessQuery
     */
    public function queryProcessesByServerId($serverId);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $processIds
     *
     * @return \Orm\Zed\Queue\Persistence\SpyQueueProcessQuery
     */
    public function queryProcessesByProcessIds(array $processIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Queue\Persistence\SpyQueueProcessQuery
     */
    public function queryProcesses();
}
