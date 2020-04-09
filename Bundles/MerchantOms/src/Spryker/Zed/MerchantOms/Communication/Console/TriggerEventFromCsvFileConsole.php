<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Communication\Console;

use Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer;
use Generated\Shared\Transfer\MerchantOmsTriggerResponseTransfer;
use Spryker\Service\UtilDataReader\Model\Reader\Csv\CsvReaderInterface;
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

    /**
     * @var \Symfony\Component\Console\Output\ConsoleOutputInterface
     */
    public $output;

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
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->addArgument(
                static::ARGUMENT_FILE_PATH,
                InputArgument::REQUIRED,
                'Absolute path to the file.'
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
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $csvReader = $this->loadCsvFile();

        if (!$csvReader) {
            return static::CODE_ERROR;
        }

        $merchantOmsEventTrigger = $this->getFactory()->createMerchantOmsEventTrigger();
        $this->prepareOutputTable();

        $totalRowsCount = $csvReader->getTotal() - 1;
        $successfullyProcessedRowsCount = 0;
        $processedCount = $this->getStartFromOption();

        try {
            while ($processedCount < $totalRowsCount) {
                $rowData = $csvReader->read();
                $processedCount++;

                $merchantOmsTriggerRequestTransfer = (new MerchantOmsTriggerRequestTransfer())
                    ->setMerchantOrderItemReference($rowData[static::TABLE_HEADER_COLUMN_MERCHANT_ORDER_ITEM_REFERENCE])
                    ->setMerchantOmsEventName($rowData[static::TABLE_HEADER_COLUMN_MERCHANT_ORDER_ITEM_OMS_EVENT_STATE]);

                $merchantOmsTriggerResponseTransfer = $merchantOmsEventTrigger->triggerMerchantOmsEvent($merchantOmsTriggerRequestTransfer);

                if ($merchantOmsTriggerResponseTransfer->getIsSuccessful()) {
                    $successfullyProcessedRowsCount++;
                }

                $this->logOutput(
                    $processedCount,
                    $successfullyProcessedRowsCount,
                    $totalRowsCount,
                    $merchantOmsTriggerRequestTransfer,
                    $merchantOmsTriggerResponseTransfer
                );

                if (!$merchantOmsTriggerResponseTransfer->getIsSuccessful() && !$input->getOption(static::OPTION_IGNORE_ERRORS)) {
                    return static::CODE_ERROR;
                }
            }
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }

    /**
     * @return \Spryker\Service\UtilDataReader\Model\Reader\Csv\CsvReaderInterface|null
     */
    protected function loadCsvFile(): ?CsvReaderInterface
    {
        $fileName = $this->input->getArgument(static::ARGUMENT_FILE_PATH);

        if (!is_file($fileName) || !is_readable($fileName)) {
            $this->error(sprintf('Could not read file from path "%s".', $fileName));

            return null;
        }

        $csvReader = $this->getFactory()->getUtilDataReaderService()->getCsvReader();
        $csvReader->load($fileName);

        if (!$csvReader->valid()) {
            $this->error('Csv file is invalid.');

            return null;
        }

        if (!$this->validateHeaderColumns($csvReader)) {
            return null;
        }

        $csvReader->rewind();
        $csvReader->getFile()->seek($this->getStartFromOption());

        return $csvReader;
    }

    /**
     * @return int
     */
    protected function getStartFromOption(): int
    {
        return max((int)$this->input->getOption(static::OPTION_START_FROM) - 1, 0);
    }

    /**
     * @param \Spryker\Service\UtilDataReader\Model\Reader\Csv\CsvReaderInterface $csvReader
     *
     * @return bool
     */
    protected function validateHeaderColumns(CsvReaderInterface $csvReader): bool
    {
        $mandatoryColumns = $this->getMandatoryColumns();
        if (array_diff($mandatoryColumns, $csvReader->getColumns())) {
            $this->error(
                sprintf(
                    'Csv file does not contain mandatory fields: %s.',
                    implode(', ', $mandatoryColumns)
                )
            );

            return false;
        }

        return true;
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
        ]);
        $table->render();

        $this->outputTable = $table;
    }

    /**
     * @param int $rowNumber
     * @param int $successfullyProcessedRowsCount
     * @param int $totalRowsCount
     * @param \Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer
     * @param \Generated\Shared\Transfer\MerchantOmsTriggerResponseTransfer $merchantOmsTriggerResponseTransfer
     *
     * @return void
     */
    protected function logOutput(
        int $rowNumber,
        int $successfullyProcessedRowsCount,
        int $totalRowsCount,
        MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer,
        MerchantOmsTriggerResponseTransfer $merchantOmsTriggerResponseTransfer
    ): void {
        if ($this->output->isVerbose()) {
            $this->outputTable->appendRow([
                $rowNumber,
                $merchantOmsTriggerRequestTransfer->getMerchantOrderItemReference(),
                $merchantOmsTriggerRequestTransfer->getMerchantOmsEventName(),
                $merchantOmsTriggerResponseTransfer->getMessage(),
            ]);

            return;
        }

        if ($merchantOmsTriggerResponseTransfer->getIsSuccessful()) {
            $this->info(sprintf('Rows processed: %s/%s', $successfullyProcessedRowsCount, $totalRowsCount));

            return;
        }

        $this->error($merchantOmsTriggerResponseTransfer->getMessage());
    }
}
