<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Dependency\QueryContainer;

class EventToQueueQueryContainerBridge implements EventToQueueQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Queue\Persistence\QueueQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Queue\Persistence\QueueQueryContainerInterface $queryContainer
     */
    public function __construct($queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @return \Orm\Zed\Queue\Persistence\Base\SpyQueueProcessQuery
     */
    public function queryProcesses()
    {
        return $this->queryContainer->queryProcesses();
    }
}
