<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Model\PackageManager;

use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;

class NodeInstaller implements PackageManagerInstallerInterface
{
    protected const NODE_JS_MINIMUM_REQUIRED_VERSION = 'v12.0.0';

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function install(LoggerInterface $logger)
    {
        $nodeVersion = $this->getNodeJsVersion($logger);
        $nodeInstalled = true;

        if (version_compare($nodeVersion, static::NODE_JS_MINIMUM_REQUIRED_VERSION) === -1) {
            $nodeInstalled = $this->installNodeJs($logger);
        }

        $yarnVersion = $this->getYarnVersion($logger);
        $yarnInstalled = true;

        if (!$yarnVersion) {
            $yarnInstalled = $this->installYarn($logger);
        }

        return $nodeInstalled && $yarnInstalled;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return string
     */
    protected function getNodeJsVersion(LoggerInterface $logger)
    {
        $process = $this->getProcess('node -v');
        $process->run();

        $version = trim(preg_replace('/\s+/', ' ', $process->getOutput()));
        $logger->info(sprintf('Node.js Version "%s"', $version));

        return $version;
    }

    /**
     * @param string $command
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function getProcess($command)
    {
        $process = new Process(explode(' ', $command));
        $process->setTimeout(null);

        return $process;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    protected function installNodeJs(LoggerInterface $logger)
    {
        $logger->info('Download node source');
        $process = $this->getProcess($this->getDownloadCommand());
        $process->run(function ($type, $buffer) use ($logger) {
            $logger->info($buffer);
        });

        $logger->info('Install node.js');
        $process = $this->getProcess('sudo -i apt-get install -y nodejs');
        $process->run(function ($type, $buffer) use ($logger) {
            $logger->info($buffer);
        });

        return $process->isSuccessful();
    }

    /**
     * @return string
     */
    protected function getDownloadCommand()
    {
        return 'curl -sL https://deb.nodesource.com/setup_12.x | sudo -E bash -';
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    protected function installYarn(LoggerInterface $logger): bool
    {
        $logger->info('Installing Yarn');

        $process = $this->getProcess($this->getYarnInstallCommand());
        $process->run(function ($type, $buffer) use ($logger) {
            $logger->info($buffer);
        });

        return $process->isSuccessful();
    }

    /**
     * @return string
     */
    protected function getYarnInstallCommand(): string
    {
        return 'npm install -g yarn';
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return string
     */
    protected function getYarnVersion(LoggerInterface $logger): string
    {
        $process = $this->getProcess('yarn -v');
        $process->run();

        $version = trim(preg_replace('/^\s+$/', ' ', $process->getOutput()));
        $logger->info(sprintf('Yarn Version "%s"', $version));

        return $version;
    }
}
