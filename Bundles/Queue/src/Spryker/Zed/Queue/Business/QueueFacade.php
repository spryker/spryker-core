<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business;

use Generated\Shared\Transfer\QueueDumpRequestTransfer;
use Generated\Shared\Transfer\QueueDumpResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Queue\Business\QueueBusinessFactory getFactory()
 */
class QueueFacade extends AbstractFacade implements QueueFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $queueName
     * @param array $options
     *
     * @return void
     */
    public function startTask($queueName, array $options = [])
    {
        $this->getFactory()
            ->createTask()
            ->run($queueName, $options);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $command
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array $options
     *
     * @return void
     */
    public function startWorker($command, OutputInterface $output, array $options = [])
    {
        $this->getFactory()
            ->createWorker($output)
            ->start($command, $options);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueDumpRequestTransfer $queueNameRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QueueDumpResponseTransfer
     */
    public function queueDump(QueueDumpRequestTransfer $queueNameRequestTransfer): QueueDumpResponseTransfer
    {
        return $this->getFactory()
            ->createQueueDumper()
            ->dumpQueue($queueNameRequestTransfer);
    }
}
