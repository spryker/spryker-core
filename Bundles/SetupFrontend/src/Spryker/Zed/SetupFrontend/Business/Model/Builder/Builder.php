<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Model\Builder;

use Psr\Log\LoggerInterface;
use Spryker\Zed\SetupFrontend\SetupFrontendConfig;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class Builder implements BuilderInterface
{
    /**
     * @var string
     */
    protected $buildCommand;

    /**
     * @var int|float|null
     */
    protected $processTimeout;

    /**
     * @param string $buildCommand
     * @param int|float|null $processTimeout
     */
    public function __construct($buildCommand, ?$processTimeout = SetupFrontendConfig::DEFAULT_PROCESS_TIMEOUT)
    {
        $this->buildCommand = $buildCommand;
        $this->processTimeout = $processTimeout;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function build(LoggerInterface $logger)
    {
        $process = new Process($this->buildCommand, APPLICATION_ROOT_DIR, null, null, $this->processTimeout);

        $process->run(function ($type, $buffer) use ($logger) {
            $this->handleOutput($buffer, $logger);
        });

        return $process->isSuccessful();
    }

    /**
     * @param string $buffer
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    protected function handleOutput($buffer, LoggerInterface $logger)
    {
        if ($logger instanceof OutputInterface && !$logger->isVeryVerbose()) {
            return;
        }

        echo $buffer;
    }
}
