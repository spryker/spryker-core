<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Dependency\Store;

interface SchedulerToStoreInterface
{
    /**
     * @return string
     */
    public function getStoreName(): string;
}
