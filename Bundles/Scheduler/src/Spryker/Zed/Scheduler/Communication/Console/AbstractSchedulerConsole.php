<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Communication\Console;

use Generated\Shared\Transfer\SchedulerFilterTransfer;
use Generated\Shared\Transfer\SchedulerResponseCollectionTransfer;
use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Scheduler\Business\SchedulerFacadeInterface getFacade()
 * @method \Spryker\Zed\Scheduler\Communication\SchedulerCommunicationFactory getFactory()
 */
class AbstractSchedulerConsole extends Console
{
    /**
     * @var string
     */
    protected const OUTPUT_SUCCESS_COLOR = 'green';

    /**
     * @var string
     */
    protected const OUTPUT_ERROR_COLOR = 'red';

    /**
     * @var string
     */
    protected const OUTPUT_SUCCESS_MESSAGE = 'OK';

    /**
     * @var string
     */
    protected const OUTPUT_ERROR_MESSAGE = 'ERROR';

    /**
     * @param \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer $responseCollectionTransfer
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function outputCommandResponseStatus(
        SchedulerResponseCollectionTransfer $responseCollectionTransfer,
        OutputInterface $output
    ): int {
        $isSuccess = true;
        foreach ($responseCollectionTransfer->getResponses() as $responseTransfer) {
            $status = $responseTransfer->getStatus();
            $outputColor = $status ? static::OUTPUT_SUCCESS_COLOR : static::OUTPUT_ERROR_COLOR;
            if ($responseTransfer->getSchedule()) {
                $output->writeln(sprintf(
                    "Scheduler Name: <fg=$outputColor;options=bold>%s</>",
                    $responseTransfer->getSchedule()->getIdScheduler(),
                ));
            }
            $output->writeln(sprintf(
                "Scheduler Status: <fg=$outputColor;options=bold>%s</>",
                $status ? static::OUTPUT_SUCCESS_MESSAGE : static::OUTPUT_ERROR_MESSAGE,
            ));
            if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                $this->outputJobResponse($responseTransfer, $output);
            }
            if ($status === false) {
                $output->writeln(sprintf(
                    "Scheduler Error: <fg=$outputColor;options=bold>%s</>",
                    $responseTransfer->getMessage(),
                ));
                $isSuccess = false;
            }
        }

        return $isSuccess ? static::CODE_SUCCESS : static::CODE_ERROR;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $responseTransfer
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function outputJobResponse(SchedulerResponseTransfer $responseTransfer, OutputInterface $output): void
    {
        if (!$responseTransfer->getSchedule() || count($responseTransfer->getSchedule()->getJobs()) === 0) {
            return;
        }

        foreach ($responseTransfer->getSchedule()->getJobs() as $jobTransfer) {
            $output->writeln(' - ' . $jobTransfer->getName());
        }
    }

    /**
     * @param array<string> $roles
     * @param array<string> $schedulers
     * @param array<string> $jobNames
     *
     * @return \Generated\Shared\Transfer\SchedulerFilterTransfer
     */
    protected function createSchedulerFilterTransfer(array $roles, array $schedulers, array $jobNames = []): SchedulerFilterTransfer
    {
        return (new SchedulerFilterTransfer())
            ->setRoles($roles)
            ->setSchedulers($schedulers)
            ->setJobs($jobNames)
            ->setStore(APPLICATION_STORE);
    }
}
