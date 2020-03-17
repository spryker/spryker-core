<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\MerchantOms\Business\MerchantOmsFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantOms\Communication\MerchantOmsCommunicationFactory getFactory()()
 */
class TriggerEventFromFileConsole extends Console
{
    protected const COMMAND_NAME = 'merchant-oms:trigger-event:from-file';
    protected const COMMAND_DESCRIPTION = 'Triggers event for merchant order items from given file.';
    protected const ARGUMENT_FILE_PATH = 'file-path';
    protected const OPTION_IGNORE_ERRORS = 'ignore-errors';
    protected const OPTION_START_FROM = 'start-from';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription(self::COMMAND_DESCRIPTION)
            ->addArgument(
                static::ARGUMENT_FILE_PATH,
                InputArgument::REQUIRED,
                'Absolute path of the csv file.'
            )
            ->addOption(
                static::OPTION_IGNORE_ERRORS,
                null,
                InputOption::VALUE_NONE,
                'Suppress errors if a csv row was not processed.'
            )
            ->addOption(
                static::OPTION_START_FROM,
                null,
                InputOption::VALUE_REQUIRED,
                'Start processing file from the defined row number.'
            );

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
        $csvFilePath = $input->getArgument(static::ARGUMENT_FILE_PATH);
        $csvReader = $this->getFactory()->getUtilDataReaderService()->getCsvReader();
        $csvReader->load($csvFilePath);

        if (!$csvReader->valid()) {
            return static::CODE_ERROR;
        }

        $columns = $csvReader->getColumns();
        $csvReader->rewind();
        $csvReader->read();

        return static::CODE_SUCCESS;
    }
}
