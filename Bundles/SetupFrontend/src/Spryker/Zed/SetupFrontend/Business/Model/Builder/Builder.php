<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Model\Builder;

use Generated\Shared\Transfer\SetupFrontendConfigurationTransfer;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class Builder implements BuilderInterface
{
    /**
     * @var string
     */
    protected $buildCommand;

    /**
     * @param string $buildCommand
     */
    public function __construct($buildCommand)
    {
        $this->buildCommand = $buildCommand;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Generated\Shared\Transfer\SetupFrontendConfigurationTransfer $setupFrontendConfigurationTransfer
     *
     * @return bool
     */
    public function build(LoggerInterface $logger, SetupFrontendConfigurationTransfer $setupFrontendConfigurationTransfer)
    {
        $command = $this->buildCommand;
        if ($setupFrontendConfigurationTransfer->getEnvironment()) {
            $command .= sprintf(':%s', $setupFrontendConfigurationTransfer->getEnvironment());
        }

        $process = new Process(explode(' ', $command), APPLICATION_ROOT_DIR, null, null, null);

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
