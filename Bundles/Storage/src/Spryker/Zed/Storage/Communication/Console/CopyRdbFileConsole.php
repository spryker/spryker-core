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
use Symfony\Component\Filesystem\Filesystem;

/**
 * @method \Spryker\Zed\Storage\Business\StorageFacade getFacade()
 */
class CopyRdbFileConsole extends Console
{

    const COMMAND_NAME = 'storage:copy-rdb-file';
    const DESCRIPTION = 'This command will copy the rdb file.';

    const ARGUMENT_SOURCE = 'source';
    const ARGUMENT_DESTINATION = 'destination';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        $this->addArgument(static::ARGUMENT_SOURCE, InputArgument::REQUIRED, 'Path to source.');
        $this->addArgument(static::ARGUMENT_DESTINATION, InputArgument::REQUIRED, 'Path to destination.');

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
        $source = $input->getArgument(static::ARGUMENT_SOURCE);
        $destination = $input->getArgument(static::ARGUMENT_DESTINATION);

        if (!file_exists($source)) {
            $this->error(sprintf('Source file "%s" does not exist!', $source));

            return;
        }

        $this->info(sprintf('Copy rdb file from "%s" to "%s"', $source, $destination));

        $filesystem = new Filesystem();
        $filesystem->copy($source, $destination);
    }

}
