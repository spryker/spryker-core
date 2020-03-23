<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Communication\Console;

use Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer;
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
class TriggerEventFromFileConsole extends Console
{
    protected const COMMAND_NAME = 'merchant-oms:trigger-event:from-file';
    protected const COMMAND_DESCRIPTION = 'Triggers event for merchant order items from given file.';
    protected const ARGUMENT_FILE_PATH = 'file-path';
    protected const OPTION_IGNORE_ERRORS = 'ignore-errors';
    protected const OPTION_START_FROM = 'start-from';

    protected const COLUMN_ROW_NUMBER = 'row_number';
    protected const COLUMN_MERCHANT_ORDER_ITEM_REFERENCE = 'merchant_order_item_reference';
    protected const COLUMN_MERCHANT_ORDER_ITEM_OMS_EVENT_STATE = 'merchant_order_item_oms_event_state';
    protected const COLUMN_RESULT = 'result';
    protected const MANDATORY_COLUMNS = [
        self::COLUMN_MERCHANT_ORDER_ITEM_REFERENCE,
        self::COLUMN_MERCHANT_ORDER_ITEM_OMS_EVENT_STATE,
    ];

    protected const RESULT_DATA_KEY_IS_SUCCESS = 'is_success';
    protected const RESULT_DATA_KEY_MESSAGE = 'message';

    /**
     * @var \Symfony\Component\Console\Output\ConsoleOutputInterface
     */
    public $output;

    /**
     * @var \Symfony\Component\Console\Helper\Table
     */
    protected $outputTable;

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
        $csvReader = $this->loadCsvFile();

        if (!$csvReader) {
            return static::CODE_ERROR;
        }

        $this->prepareOutputTable();
        $totalRowsCount = $csvReader->getTotal();
        $processedRowsCount = 0;

        try {
            while ($totalRowsCount > $csvReader->getFile()->key() + 1) {
                $rowData = $csvReader->read();
                $resultData = $this->triggerMerchantOmsEvent($rowData);

                if ($resultData[static::RESULT_DATA_KEY_IS_SUCCESS]) {
                    $processedRowsCount++;
                }

                $this->appendOutputTableRow(
                    $csvReader->getFile()->key() + 1,
                    $rowData,
                    $resultData[static::RESULT_DATA_KEY_MESSAGE]
                );

                $this->writeProcessingOutput($rowData, $resultData, $processedRowsCount, $totalRowsCount);

                if (!$resultData[static::RESULT_DATA_KEY_IS_SUCCESS] && !$input->getOption(static::OPTION_IGNORE_ERRORS)) {
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

        if (array_intersect(static::MANDATORY_COLUMNS, $csvReader->getColumns()) === static::MANDATORY_COLUMNS) {
            $this->error(
                sprintf(
                    'Csv file does not contain mandatory fields: %s.',
                    implode(', ', static::MANDATORY_COLUMNS)
                )
            );

            return null;
        }

        $csvReader->rewind();

        if ($this->input->getOption(static::OPTION_START_FROM)) {
            $csvReader->getFile()->seek((int)$this->input->getOption(static::OPTION_START_FROM) - 2);
        }

        return $csvReader;
    }

    /**
     * @param string[] $rowData
     *
     * @return array
     */
    protected function triggerMerchantOmsEvent(array $rowData): array
    {
        $merchantOrderItemCriteriaTransfer = (new MerchantOrderItemCriteriaTransfer())
            ->setMerchantOrderItemReference($rowData[static::COLUMN_MERCHANT_ORDER_ITEM_REFERENCE]);

        $merchantOrderItemTransfer = $this->getFactory()
            ->getMerchantSalesOrderFacade()
            ->findMerchantOrderItem($merchantOrderItemCriteriaTransfer);

        if (!$merchantOrderItemTransfer) {
            return [
                static::RESULT_DATA_KEY_IS_SUCCESS => false,
                static::RESULT_DATA_KEY_MESSAGE => sprintf(
                    'Failed! Merchant order item with reference "%s" was not found.',
                    $rowData[static::COLUMN_MERCHANT_ORDER_ITEM_REFERENCE]
                ),
            ];
        }

        $merchantOmsTriggerRequestTransfer = (new MerchantOmsTriggerRequestTransfer())
            ->setMerchantOmsEventName($rowData[static::COLUMN_MERCHANT_ORDER_ITEM_OMS_EVENT_STATE])
            ->addMerchantOrderItem($merchantOrderItemTransfer);

        $transitionedItemsCount = $this->getFacade()->triggerEventForMerchantOrderItems($merchantOmsTriggerRequestTransfer);

        if (!$transitionedItemsCount) {
            return [
                static::RESULT_DATA_KEY_IS_SUCCESS => false,
                static::RESULT_DATA_KEY_MESSAGE => sprintf(
                    'Failed! Event "%s" was not successfully triggered for merchant order item with reference "%s".',
                    $rowData[static::COLUMN_MERCHANT_ORDER_ITEM_OMS_EVENT_STATE],
                    $rowData[static::COLUMN_MERCHANT_ORDER_ITEM_REFERENCE]
                ),
            ];
        }

        return [
            static::RESULT_DATA_KEY_IS_SUCCESS => true,
            static::RESULT_DATA_KEY_MESSAGE => 'Success.',
        ];
    }

    /**
     * @return void
     */
    protected function prepareOutputTable(): void
    {
        if (!$this->output->isVerbose()) {
            return;
        }

        $table = (new Table($this->output->section()))
            ->setHeaders([
                static::COLUMN_ROW_NUMBER,
                static::COLUMN_MERCHANT_ORDER_ITEM_REFERENCE,
                static::COLUMN_MERCHANT_ORDER_ITEM_OMS_EVENT_STATE,
                static::COLUMN_RESULT,
            ]);
        $table->render();

        $this->outputTable = $table;
    }

    /**
     * @param int $rowNumber
     * @param string[] $rowData
     * @param string $resultMessage
     *
     * @return void
     */
    protected function appendOutputTableRow(int $rowNumber, array $rowData, string $resultMessage): void
    {
        if (!$this->output->isVerbose()) {
            return;
        }

        $this->outputTable->appendRow([
            $rowNumber,
            $rowData[static::COLUMN_MERCHANT_ORDER_ITEM_REFERENCE],
            $rowData[static::COLUMN_MERCHANT_ORDER_ITEM_OMS_EVENT_STATE],
            $resultMessage,
        ]);
    }

    /**
     * @param string[] $rowData
     * @param string[] $resultData
     * @param int $processedRowsCount
     * @param int $totalRowsCount
     *
     * @return void
     */
    protected function writeProcessingOutput(
        array $rowData,
        array $resultData,
        int $processedRowsCount,
        int $totalRowsCount
    ): void {
        if ($this->output->isVerbose()) {
            return;
        }

        if ($resultData[static::RESULT_DATA_KEY_IS_SUCCESS]) {
            $this->info(sprintf('Rows processed: %s/%s', $processedRowsCount, $totalRowsCount));

            return;
        }

        $this->error($resultData[static::RESULT_DATA_KEY_MESSAGE]);
    }
}
