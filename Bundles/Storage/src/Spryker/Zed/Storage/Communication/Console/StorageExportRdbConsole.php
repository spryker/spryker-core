<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Storage\Business\StorageFacadeInterface getFacade()
 */
class StorageExportRdbConsole extends Console
{
    public const COMMAND_NAME = 'storage:redis:export';
    public const DESCRIPTION = 'This command will export the rdb file.';

    public const ARGUMENT_DESTINATION = 'destination';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);

        $this->addArgument(static::ARGUMENT_DESTINATION, InputArgument::REQUIRED, 'Path to destination.');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $destination = $input->getArgument(static::ARGUMENT_DESTINATION);

        if ($this->getFacade()->export($destination)) {
            $this->info(sprintf('Exported rdb file to "%s"', $destination));

            return static::CODE_SUCCESS;
        }

        $this->error(sprintf('Could not export rdb file.'));

        return static::CODE_ERROR;
    }
}
