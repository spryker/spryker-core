<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Model\Installer;

use Psr\Log\LoggerInterface;
use Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\PathFinderInterface;
use Spryker\Zed\SetupFrontend\SetupFrontendConfig;
use Symfony\Component\Process\Process;

class DependencyInstaller implements DependencyInstallerInterface
{
    /**
     * @var \Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\PathFinderInterface
     */
    protected $installPathFinder;

    /**
     * @var string
     */
    protected $installCommand;

    /**
     * @var int|float|null
     */
    protected $processTimeout;

    /**
     * @param \Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder\PathFinderInterface $installPathFinder
     * @param string $installCommand
     * @param int|float|null $processTimeout
     */
    public function __construct(PathFinderInterface $installPathFinder, $installCommand, ?$processTimeout = SetupFrontendConfig::DEFAULT_PROCESS_TIMEOUT)
    {
        $this->installPathFinder = $installPathFinder;
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
        $isSuccess = true;
        foreach ($this->installPathFinder->find() as $file) {
            $path = $file->getPath();
            $logger->info(sprintf('Install dependencies in "%s"', $path));

            $process = new Process($this->installCommand, $path, null, null, $this->processTimeout);
            $process->setTimeout(null);
            $process->run(function ($type, $buffer) use ($logger) {
                $logger->info($buffer);
            });

            if (!$process->isSuccessful()) {
                $isSuccess = false;
            }
        }

        return $isSuccess;
    }
}
