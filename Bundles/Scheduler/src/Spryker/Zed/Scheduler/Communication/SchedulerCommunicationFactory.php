<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Scheduler\Communication\Builder\SchedulerFilterBuilder;
use Spryker\Zed\Scheduler\Communication\Builder\SchedulerFilterBuilderInterface;

/**
 * @method \Spryker\Zed\Scheduler\SchedulerConfig getConfig()
 * @method \Spryker\Zed\Scheduler\Business\SchedulerFacadeInterface getFacade()
 */
class SchedulerCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Scheduler\Communication\Builder\SchedulerFilterBuilderInterface
     */
    public function createSchedulerFilterBuilder(): SchedulerFilterBuilderInterface
    {
        return new SchedulerFilterBuilder();
    }
}
