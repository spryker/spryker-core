<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Setup\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Setup\Business\SetupFacadeInterface getFacade()
 */
class EmptyGeneratedDirectoryConsole extends Console
{
    public const COMMAND_NAME = 'setup:empty-generated-directory';

    /**
     * @return void
     */
    public function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Empty the directory where generated files are stored');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getFacade()->emptyGeneratedDirectory();
    }
}
