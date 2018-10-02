<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\SetupFrontend\Business\SetupFrontendFacadeInterface getFacade()
 */
class ZedAssetsCleanUpConsole extends Console
{
    public const COMMAND_NAME = 'frontend:zed:cleanup-assets';
    public const DESCRIPTION = 'This command will remove all Zed assets.';

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
        $this->info('Cleanup Zed assets');

        if ($this->getFacade()->removeZedAssets()) {
            return static::CODE_SUCCESS;
        }

        return static::CODE_ERROR;
    }
}
