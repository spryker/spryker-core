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
 * @deprecated Use Spryker\Zed\Setup\Communication\Console\EmptyGeneratedDirectoryConsole instead.
 *
 * @method \Spryker\Zed\Setup\Business\SetupFacadeInterface getFacade()
 */
class RemoveGeneratedDirectoryConsole extends Console
{
    public const COMMAND_NAME = 'setup:remove-generated-directory';
    public const DESCRIPTION = 'Remove the directory where generated files are stored';

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
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getFacade()->removeGeneratedDirectory();
    }
}
