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
    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function install(LoggerInterface $logger)
    {
        $logger->info('Check node.js version');

        $process = new Process('node -v');
        $process->run();

        $version = trim(preg_replace('/\s+/', ' ', $process->getOutput()));
        $logger->info(sprintf('Node.js Version "%s"', $version));

        if (preg_match('/^v[0-7]/', $version)) {
            $logger->info('Download node source');
            $process = new Process('curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash -');
            $process->run(function ($type, $buffer) use ($logger) {
                $logger->info($buffer);
            });

            $logger->info('Install node.js');
            $process = new Process('sudo -i apt-get install -y nodejs');
            $process->run(function ($type, $buffer) use ($logger) {
                $logger->info($buffer);
            });

            return $process->isSuccessful();
        }

        return true;
    }
}
