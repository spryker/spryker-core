<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Dependency\QueryContainer;

interface EventToQueueQueryContainerInterface
{
    /**
     * @return \Orm\Zed\Queue\Persistence\Base\SpyQueueProcessQuery
     */
    public function queryProcesses();
}
