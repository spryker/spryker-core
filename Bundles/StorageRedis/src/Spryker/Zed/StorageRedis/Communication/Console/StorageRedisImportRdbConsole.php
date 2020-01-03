<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StorageRedis\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\StorageRedis\Communication\StorageRedisCommunicationFactory getFactory()
 */
class StorageRedisImportRdbConsole extends Console
{
    public const COMMAND_NAME = 'storage:redis:import-rdb';
    public const DESCRIPTION = 'This command will import Redis rdb file.';

    public const ARGUMENT_SOURCE = 'source';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);

        $this->addArgument(static::ARGUMENT_SOURCE, InputArgument::REQUIRED, 'Path of the rdb file.');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $source = $input->getArgument(static::ARGUMENT_SOURCE);
        $storageRedisImporter = $this->getFactory()->createStorageRedisImporter();

        if ($storageRedisImporter->import($source)) {
            $this->info(sprintf('Imported rdb file "%s"', $source));

            return static::CODE_SUCCESS;
        }

        $this->error(sprintf('Could not import rdb file.'));

        return static::CODE_ERROR;
    }
}
