<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Model\Process;

use Symfony\Component\Process\Process;

interface ProcessManagerInterface
{

    /**
     * @param string $queue
     * @param string $command
     *
     * @return Process
     */
    public function triggerQueueProcess($command, $queue);

    /**
     * @param array $queueName
     *
     * @return int
     */
    public function getBusyProcessNumber($queueName);

    /**
     * @return void
     */
    public function flushIdleProcesses();
}
