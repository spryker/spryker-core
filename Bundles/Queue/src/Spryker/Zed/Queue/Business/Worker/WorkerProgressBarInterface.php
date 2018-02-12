<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Worker;

interface WorkerProgressBarInterface
{
    /**
     * @param int $steps
     * @param int $round
     *
     * @return void
     */
    public function start($steps, $round);

    /**
     * @param int $step
     *
     * @return void
     */
    public function advance($step = 1);

    /**
     * @return void
     */
    public function finish();

    /**
     * @param int $lines
     *
     * @return void
     */
    public function refreshOutput($lines);

    /**
     * @param int $rowId
     * @param string $queueName
     * @param int $busyProcessNumber
     * @param int $newProcessNumber
     *
     * @return void
     */
    public function writeConsoleMessage($rowId, $queueName, $busyProcessNumber, $newProcessNumber);

    /**
     * @return void
     */
    public function reset();
}
