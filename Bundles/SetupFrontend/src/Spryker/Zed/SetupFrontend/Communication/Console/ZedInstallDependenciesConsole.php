<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Communication\Console;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

class ZedInstallDependenciesConsole extends Console
{
    const COMMAND_NAME = 'frontend:zed-install-dependencies';
    const DESCRIPTION = 'This command will install Zed Module dependencies.';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->info('Install Zed dependencies');

        $finder = new Finder();

        $finder->files()->in(Config::get(KernelConstants::SPRYKER_ROOT) . '/*/assets/Zed')->name('package.json')->depth('< 2');

        foreach ($finder as $file) {
            $path = $file->getPath();
            $this->info(sprintf('Install dependencies in "%s"', $path));
            $process = new Process('npm install', $path);
            $process->run(function ($type, $buffer) {
                echo $buffer;
            });
        }

        return static::CODE_SUCCESS;
    }
}
