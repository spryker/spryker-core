<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilUuidGenerator\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\UtilUuidGenerator\Business\UtilUuidGeneratorFacadeInterface getFacade()
 * @method \Spryker\Zed\UtilUuidGenerator\Communication\UtilUuidGeneratorCommunicationFactory getFactory()
 */
class UuidGeneratorConsole extends Console
{
    public const COMMAND_NAME = 'uuid:generate';
    public const COMMAND_DESCRIPTION = 'Fills uuid field for records where this field is empty.';

    protected const OPTION_TABLE = 'table';
    protected const SUCCESS_MESSAGE = 'Updated record count for this table: %s.';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>');

        $this->addOption(static::OPTION_TABLE, 't', InputOption::VALUE_REQUIRED, 'DB table name, e.g. `spy_wishlist`, `spy_quote`, etc.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $updatedRecordCount = $this->getFacade()
            ->generateUuids($input->getOption(static::OPTION_TABLE));

        $this->info(sprintf(static::SUCCESS_MESSAGE, $updatedRecordCount));
    }
}
