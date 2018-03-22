<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Process;

interface ProcessManagerInterface
{
    /**
     * @param string $command
     * @param string $queue
     *
     * @return \Symfony\Component\Process\Process
     */
    public function triggerQueueProcess($command, $queue);

    /**
     * @param string $queueName
     *
     * @return int
     */
    public function getBusyProcessNumber($queueName);

    /**
     * @return void
     */
    public function flushIdleProcesses();

    /**
     * @param int $processId
     *
     * @return bool
     */
    public function isProcessRunning($processId);
}
