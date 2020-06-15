<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Communication\Console;

use Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

/**
 * @method \Spryker\Zed\MerchantOms\Business\MerchantOmsFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantOms\Communication\MerchantOmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface getRepository()
 */
class TriggerEventFromCsvFileConsole extends Console
{
    protected const COMMAND_NAME = 'merchant-oms:trigger-event:from-csv-file';
    protected const COMMAND_DESCRIPTION = 'Triggers event for merchant order items from given file.';
    protected const ARGUMENT_FILE_PATH = 'file-path';
    protected const OPTION_IGNORE_ERRORS = 'ignore-errors';
    protected const OPTION_START_FROM = 'start-from';

    protected const TABLE_HEADER_COLUMN_ROW_NUMBER = 'row_number';
    protected const TABLE_HEADER_COLUMN_MERCHANT_ORDER_ITEM_REFERENCE = 'merchant_order_item_reference';
    protected const TABLE_HEADER_COLUMN_MERCHANT_ORDER_ITEM_OMS_EVENT_STATE = 'merchant_order_item_oms_event_state';
    protected const TABLE_HEADER_COLUMN_RESULT = 'result';
    protected const TABLE_HEADER_COLUMN_MESSAGE = 'message';

    /**
     * @var \Symfony\Component\Console\Output\ConsoleOutputInterface
     */
    protected $output;

    /**
     * @var \Symfony\Component\Console\Helper\Table
     */
    protected $outputTable;

    /**
     * @return string[]
     */
    protected function getMandatoryColumns(): array
    {
        return [
            static::TABLE_HEADER_COLUMN_MERCHANT_ORDER_ITEM_REFERENCE,
            static::TABLE_HEADER_COLUMN_MERCHANT_ORDER_ITEM_OMS_EVENT_STATE,
        ];
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->addArgument(
                static::ARGUMENT_FILE_PATH,
                InputArgument::REQUIRED,
                'Path to the file. It can be absolute or relative to application root directory.'
            )
            ->addOption(
                static::OPTION_IGNORE_ERRORS,
                null,
                InputOption::VALUE_NONE,
                'Suppress errors if an input file row was not processed.'
            )
            ->addOption(
                static::OPTION_START_FROM,
                null,
                InputOption::VALUE_REQUIRED,
                'Start file processing from the defined row number.'
            );

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
        $filePath = $this->resolveFilePath();

        if (!$filePath) {
            return static::CODE_ERROR;
        }

        $csvReader = $this->getFactory()
            ->getUtilDataReaderService()
            ->getCsvReader();
        $csvReader->load($filePath);

        if (
            !$csvReader->valid()
            || !$this->getFactory()->createHeaderValidator()->validate($this->getMandatoryColumns(), $csvReader)->getIsSuccessful()
        ) {
            $this->error('CSV file is invalid.');

            return static::CODE_ERROR;
        }

        $this->prepareOutputTable();

        $csvReader->rewind();
        $csvReader->getFile()->seek($this->getStartFromOption());

        $totalRowsCount = $csvReader->getTotal() - 1;
        $successfullyProcessedRowsCount = 0;
        $rowNumber = $this->getStartFromOption();

        while ($rowNumber < $totalRowsCount) {
                $rowData = $csvReader->read();
                $rowNumber++;

                $merchantOmsTriggerRequestTransfer = (new MerchantOmsTriggerRequestTransfer())
                    ->setMerchantOrderItemReference($rowData[static::TABLE_HEADER_COLUMN_MERCHANT_ORDER_ITEM_REFERENCE])
                    ->setMerchantOmsEventName($rowData[static::TABLE_HEADER_COLUMN_MERCHANT_ORDER_ITEM_OMS_EVENT_STATE]);
            try {
                $merchantOmsTriggerResponseTransfer = $this->getFacade()->triggerEventForMerchantOrderItem($merchantOmsTriggerRequestTransfer);

                if ($merchantOmsTriggerResponseTransfer->getIsSuccessful()) {
                    $successfullyProcessedRowsCount++;
                }
                $this->logOutput(
                    $rowNumber,
                    $merchantOmsTriggerRequestTransfer,
                    $merchantOmsTriggerResponseTransfer->getIsSuccessful()
                );
            } catch (Throwable $exception) {
                $this->logOutput(
                    $rowNumber,
                    $merchantOmsTriggerRequestTransfer,
                    false,
                    $exception->getMessage()
                );
            }
        }

        $this->info(sprintf('Rows processed: %s/%s', $successfullyProcessedRowsCount, $totalRowsCount));

        return static::CODE_SUCCESS;
    }

    /**
     * @return string|null
     */
    protected function resolveFilePath(): ?string
    {
        $filePathResolverResponseTransfer = $this->getFactory()
            ->createFilePathResolver()
            ->resolveFilePath($this->input->getArgument(static::ARGUMENT_FILE_PATH));

        if (!$filePathResolverResponseTransfer->getIsSuccessful()) {
            $this->error($filePathResolverResponseTransfer->getMessage()->getMessage());

            return null;
        }

        return $filePathResolverResponseTransfer->getFilePath();
    }

    /**
     * @return int
     */
    protected function getStartFromOption(): int
    {
        return max((int)$this->input->getOption(static::OPTION_START_FROM) - 1, 0);
    }

    /**
     * @return void
     */
    protected function prepareOutputTable(): void
    {
        if (!$this->output->isVerbose()) {
            return;
        }

        $table = (new Table($this->output->section()))->setHeaders([
            static::TABLE_HEADER_COLUMN_ROW_NUMBER,
            static::TABLE_HEADER_COLUMN_MERCHANT_ORDER_ITEM_REFERENCE,
            static::TABLE_HEADER_COLUMN_MERCHANT_ORDER_ITEM_OMS_EVENT_STATE,
            static::TABLE_HEADER_COLUMN_RESULT,
            static::TABLE_HEADER_COLUMN_MESSAGE,
        ]);

        $table->render();

        $this->outputTable = $table;
    }

    /**
     * @param int $rowNumber
     * @param \Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer
     * @param bool $result
     * @param string|null $message
     *
     * @return void
     */
    protected function logOutput(
        int $rowNumber,
        MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer,
        bool $result,
        ?string $message = null
    ): void {
        if ($this->output->isVerbose()) {
            $this->outputTable->appendRow([
                $rowNumber,
                $merchantOmsTriggerRequestTransfer->getMerchantOrderItemReference(),
                $merchantOmsTriggerRequestTransfer->getMerchantOmsEventName(),
                $result ? 'success' : 'fail',
                $message,
            ]);

            return;
        }
    }
}
