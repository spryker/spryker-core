<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business;

use Generated\Shared\Transfer\QueueDumpRequestTransfer;
use Generated\Shared\Transfer\QueueDumpResponseTransfer;
use Symfony\Component\Console\Output\OutputInterface;

interface QueueFacadeInterface
{
    /**
     * Specification
     *  - Starts receiving and processing messages task for one specific queue
     *
     * @api
     *
     * @param string $queueName
     * @param array $options
     *
     * @return void
     */
    public function startTask($queueName, array $options = []);

    /**
     * Specification
     *  - Starts multiple task/processes per queue
     *
     * @api
     *
     * @param string $command
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array $options
     *
     * @return void
     */
    public function startWorker($command, OutputInterface $output, array $options = []);

    /**
     * Specification:
     * - Reads messages from the specific queue.
     * - Gets queue name, limit, acknowledge and format from the transfer object.
     * - Throws an exception if event doesn't exist.
     * - Returns transfer object with dumped amount of messages in the defined output format.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueDumpRequestTransfer $queueNameRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QueueDumpResponseTransfer
     */
    public function queueDump(QueueDumpRequestTransfer $queueNameRequestTransfer): QueueDumpResponseTransfer;
}
