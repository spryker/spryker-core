<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Communication\Console;

use Generated\Shared\Transfer\SchedulerResponseCollectionTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Scheduler\Business\SchedulerFacadeInterface getFacade()
 * @method \Spryker\Zed\Scheduler\Communication\SchedulerCommunicationFactory getFactory()
 */
class AbstractSchedulerConsole extends Console
{
    /**
     * @param \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer $schedulerResponseCollectionTransfer
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function outputCommandResponse(
        SchedulerResponseCollectionTransfer $schedulerResponseCollectionTransfer,
        OutputInterface $output
    ): void {
        foreach ($schedulerResponseCollectionTransfer->getResponses() as $schedulerResponseTransfer) {
            $output->writeln(sprintf("<fg=green;options=bold>Scheduler Name: %s</>", $schedulerResponseTransfer->getIdScheduler()));
            foreach ($schedulerResponseTransfer->getSchedulerJobResponses() as $schedulerJobResponseTransfer) {
                $output->writeln(sprintf('<fg=green>%s: %s</>', $schedulerJobResponseTransfer->getName(), $schedulerJobResponseTransfer->getStatus()));
            }
        }
    }
}
