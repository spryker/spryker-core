<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Uuid\Communication\Console;

use Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer;
use Generated\Shared\Transfer\UuidGeneratorReportTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Uuid\Business\UuidFacadeInterface getFacade()
 * @method \Spryker\Zed\Uuid\Communication\UuidCommunicationFactory getFactory()
 * @method \Spryker\Zed\Uuid\Persistence\UuidRepositoryInterface getRepository()
 */
class UuidGeneratorConsole extends Console
{
    public const COMMAND_NAME = 'uuid:generate';
    public const COMMAND_DESCRIPTION = 'Generates missing uuids for specified database table.';

    protected const ARGUMENT_MODULE = 'module';
    protected const ARGUMENT_TABLE = 'table';
    protected const SUCCESS_MESSAGE = 'Uuid was generated for %d records in %s table.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();

        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>');

        $this->addArgument(static::ARGUMENT_MODULE, InputArgument::REQUIRED, 'Name of the module that defines the database table, e.g. "Quote".');
        $this->addArgument(static::ARGUMENT_TABLE, InputArgument::REQUIRED, 'Database table name, e.g. "spy_quote".');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $uuidGeneratorReportTransfer = $this->getFacade()->generateUuids(
            (new UuidGeneratorConfigurationTransfer())->fromArray($input->getArguments(), true)
        );

        $this->info($this->getSuccessMessage($uuidGeneratorReportTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\UuidGeneratorReportTransfer $uuidGeneratorReportTransfer
     *
     * @return string
     */
    protected function getSuccessMessage(UuidGeneratorReportTransfer $uuidGeneratorReportTransfer): string
    {
        return sprintf(
            static::SUCCESS_MESSAGE,
            $uuidGeneratorReportTransfer->getCount(),
            $uuidGeneratorReportTransfer->getTable()
        );
    }
}
