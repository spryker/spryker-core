<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Communication\Plugin\ConfigurationReader;

use Generated\Shared\Transfer\SchedulerTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerReaderPluginInterface;

/**
 * @method \Spryker\Zed\Scheduler\Business\SchedulerFacadeInterface getFacade()
 * @method \Spryker\Zed\Scheduler\SchedulerConfig getConfig()
 */
class PhpSchedulerReaderPlugin extends AbstractPlugin implements SchedulerReaderPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerTransfer
     */
    public function readSchedule(SchedulerTransfer $schedulerTransfer): SchedulerTransfer
    {
        return $this->getFacade()->getPhpCronJobsConfiguration($schedulerTransfer);
    }
}
