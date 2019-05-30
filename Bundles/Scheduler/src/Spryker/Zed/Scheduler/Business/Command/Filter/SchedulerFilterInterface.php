<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\Command\Filter;

use Generated\Shared\Transfer\SchedulerFilterTransfer;

interface SchedulerFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     *
     * @return \Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerAdapterPluginInterface[]
     */
    public function getFilteredSchedulerAdapters(SchedulerFilterTransfer $filterTransfer): array;
}
