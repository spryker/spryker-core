<?php
/**
 * Created by PhpStorm.
 * User: kalinin
 * Date: 2019-05-29
 * Time: 15:37
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Iterator\Strategy;

use Generated\Shared\Transfer\SchedulerJobTransfer;
use Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface;

interface ExecutionStrategyInterface
{
    /**
     * @param \Generated\Shared\Transfer\SchedulerJobTransfer $jobTransfer
     *
     * @return \Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface
     */
    public function getExecutor(SchedulerJobTransfer $jobTransfer): ExecutorInterface;
}
