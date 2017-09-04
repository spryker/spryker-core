<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Worker;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class WorkerProgressBar implements WorkerProgressBarInterface
{

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @var \Symfony\Component\Console\Helper\ProgressBar
     */
    protected $progressBar;

    /**
     * @var bool
     */
    protected $firstRun = false;

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @param int $steps
     * @param int $round
     *
     * @return void
     */
    public function start($steps, $round)
    {
        if ($this->output->getVerbosity() === OutputInterface::VERBOSITY_NORMAL) {
            return;
        }

        $this->progressBar = $this->createProgressBar($steps);
        $this->progressBar->setFormatDefinition('queue', '%message% %current%/%max% sec [%bar%] %percent:3s%%');
        $this->progressBar->setFormat('queue');
        $this->progressBar->setMessage(sprintf('Main Queue Process <execution round #%d>:', $round));
    }

    /**
     * @return void
     */
    public function advance()
    {
        if ($this->progressBar) {
            $this->progressBar->advance();
        }
    }

    /**
     * @return void
     */
    public function finish()
    {
        if ($this->progressBar) {
            $this->progressBar->finish();
        }
    }

    /**
     * @param int $lines
     *
     * @return void
     */
    public function refreshOutput($lines)
    {
        if (!$this->progressBar) {
            return;
        }

        if (!$this->firstRun) {
            $this->firstRun = true;

            return;
        }

        $this->output->write("\x0D");
        $this->output->write(str_repeat("\x1B[1A\x1B[2K", $lines));
    }

    /**
     * @param int $rowId
     * @param string $queueName
     * @param int $busyProcessNumber
     * @param int $newProcessNumber
     *
     * @return void
     */
    public function writeConsoleMessage($rowId, $queueName, $busyProcessNumber, $newProcessNumber)
    {
        if (!$this->progressBar) {
            return;
        }

        $this->output->writeln(
            sprintf(
                '[%d] %s queue process(es): New: %d Busy: %d Last Update: %s',
                $rowId,
                $queueName,
                $newProcessNumber,
                $busyProcessNumber,
                date('H:i:s')
            )
        );
    }

    /**
     * @return void
     */
    public function reset()
    {
        unset($this->progressBar);
    }

    /**
     * @param int $steps
     *
     * @return \Symfony\Component\Console\Helper\ProgressBar
     */
    protected function createProgressBar($steps)
    {
        return new ProgressBar($this->output, $steps);
    }

}
