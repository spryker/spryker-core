<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Model\Installer;

use Psr\Log\LoggerInterface;
use Spryker\Zed\SetupFrontend\SetupFrontendConfig;
use Symfony\Component\Process\Process;

class ProjectInstaller implements DependencyInstallerInterface
{
    /**
     * @var string
     */
    protected $installCommand;

    /**
     * @var int|float|null
     */
    protected $processTimeout;

    /**
     * @param string $installCommand
     * @param int|float|null $processTimeout
     */
    public function __construct($installCommand, ?$processTimeout = SetupFrontendConfig::DEFAULT_PROCESS_TIMEOUT)
    {
        $this->installCommand = $installCommand;
        $this->processTimeout = $processTimeout;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function install(LoggerInterface $logger)
    {
        $process = new Process($this->installCommand, APPLICATION_ROOT_DIR, null, null, $this->processTimeout);
        $process->run(function ($type, $buffer) use ($logger) {
            $logger->info($buffer);
        });

        return $process->isSuccessful();
    }
}
