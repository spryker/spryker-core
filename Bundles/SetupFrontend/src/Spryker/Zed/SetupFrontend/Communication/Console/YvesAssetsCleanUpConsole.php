<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class YvesAssetsCleanUpConsole extends Console
{
    const COMMAND_NAME = 'frontend:cleanup-yves-assets';
    const DESCRIPTION = 'This command will remove all yves assets.';

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
        $this->info('Cleanup Yves assets');

        $frontendDependencyDirectories = [
            APPLICATION_ROOT_DIR . '/public/Yves/assets',
        ];
        $filesystem = new Filesystem();

        foreach ($frontendDependencyDirectories as $frontendDependencyDirectory) {
            if (is_dir($frontendDependencyDirectory)) {
                $filesystem->remove($frontendDependencyDirectory);
            }
        }

        return static::CODE_SUCCESS;
    }
}
