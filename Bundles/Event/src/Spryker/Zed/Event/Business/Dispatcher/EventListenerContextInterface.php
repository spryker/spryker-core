<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Business\Dispatcher;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface;

interface EventListenerContextInterface extends EventHandlerInterface, EventBulkHandlerInterface
{
    /**
     * @return bool
     */
    public function isHandledInQueue();

    /**
     * @return string
     */
    public function getListenerName();

    /**
     * @return string|null
     */
    public function getQueuePoolName();
}
