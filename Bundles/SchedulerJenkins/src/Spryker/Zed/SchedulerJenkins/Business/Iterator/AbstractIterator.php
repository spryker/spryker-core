<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Iterator;

use Generated\Shared\Transfer\SchedulerJobTransfer;
use Generated\Shared\Transfer\SchedulerResponseTransfer;
use GuzzleHttp\Exception\BadResponseException;
use Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface;

abstract class AbstractIterator implements IteratorInterface
{
    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface $executor
     * @param string $idScheduler
     * @param \Generated\Shared\Transfer\SchedulerJobTransfer $jobTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    protected function execute(ExecutorInterface $executor, string $idScheduler, SchedulerJobTransfer $jobTransfer): SchedulerResponseTransfer
    {
        try {
            $response = $executor->execute($idScheduler, $jobTransfer);
        } catch (BadResponseException $badResponseException) {
            $exceptionMessage = $badResponseException->getResponse()->getBody()->getContents();

            return (new SchedulerResponseTransfer())
                ->setMessage($exceptionMessage)
                ->setStatus(false);
        }

        return $response;
    }
}
