<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Model\Installer;

use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;

class ProjectInstaller implements DependencyInstallerInterface
{
    /**
     * @var string
     */
    protected $installCommand;

    /**
     * @param string $installCommand
     */
    public function __construct($installCommand)
    {
        $this->installCommand = $installCommand;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function install(LoggerInterface $logger)
    {
        $process = new Process($this->installCommand, APPLICATION_ROOT_DIR, null, null, null);
        $process->run(function ($type, $buffer) use ($logger) {
            $logger->info($buffer);
        });

        return $process->isSuccessful();
    }
}
