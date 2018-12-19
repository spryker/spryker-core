<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilUuidGenerator\Communication\Console;

use Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\UtilUuidGenerator\Business\UtilUuidGeneratorFacadeInterface getFacade()
 * @method \Spryker\Zed\UtilUuidGenerator\Communication\UtilUuidGeneratorCommunicationFactory getFactory()
 * @method \Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorRepositoryInterface getRepository()
 */
class UuidGeneratorConsole extends Console
{
    public const COMMAND_NAME = 'uuid:generate';
    public const COMMAND_DESCRIPTION = 'Fills uuid field for records where this field is null.';

    protected const ARGUMENT_MODULE = 'module';
    protected const ARGUMENT_TABLE = 'table';
    protected const SUCCESS_MESSAGE = 'Updated record count for this table: %s.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();

        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>');

        $this->addArgument(static::ARGUMENT_MODULE, InputArgument::REQUIRED, 'Module name, e.g. `Wishlist`, `Tax`, etc.');
        $this->addArgument(static::ARGUMENT_TABLE, InputArgument::REQUIRED, 'Database table name, e.g. `spy_wishlist`, `spy_tax_set`, etc.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $updatedRecordCount = $this->getFacade()->generateUuids(
            (new UuidGeneratorConfigurationTransfer())->fromArray($input->getArguments(), true)
        );

        $this->info(sprintf(static::SUCCESS_MESSAGE, $updatedRecordCount));
    }
}
