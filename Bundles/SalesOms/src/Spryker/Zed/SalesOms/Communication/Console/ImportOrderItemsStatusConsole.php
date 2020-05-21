<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOms\Communication\Console;

use Exception;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

/**
 * @method \Spryker\Zed\SalesOms\Business\SalesOmsFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOms\Communication\SalesOmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesOms\Persistence\SalesOmsRepositoryInterface getRepository()
 */
class ImportOrderItemsStatusConsole extends Console
{
    protected const COMMAND_NAME = 'order-oms:status-import';
    protected const COMMAND_DESCRIPTION = 'Import order item status for order items from given file.';
    protected const ARGUMENT_FILE_PATH = 'file-path';
    protected const OPTION_IGNORE_ERRORS = 'ignore-errors';
    protected const OPTION_START_FROM = 'start-from';

    protected const TABLE_HEADER_COLUMN_ROW_NUMBER = 'row_number';
    protected const TABLE_HEADER_COLUMN_ORDER_REFERENCE = 'order_reference';
    protected const TABLE_HEADER_COLUMN_ORDER_ITEM_REFERENCE = 'order_item_reference';
    protected const TABLE_HEADER_COLUMN_ORDER_ITEM_OMS_EVENT_STATE = 'order_item_oms_event_state';
    protected const TABLE_HEADER_COLUMN_COUNT_TRANSITIONED_ITEM = 'count_transitioned_item';
    protected const TABLE_HEADER_COLUMN_RESULT = 'result';
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
            static::TABLE_HEADER_COLUMN_ORDER_ITEM_REFERENCE,
            static::TABLE_HEADER_COLUMN_ORDER_ITEM_OMS_EVENT_STATE,
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
     * @throws \Exception
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

        try {
            while ($rowNumber < $totalRowsCount) {
                $rowData = $csvReader->read();
                $rowNumber++;

                $salesOrderItemTransfer = $this->getFacade()
                    ->findSalesOrderItemTranferByOrderItemReference($rowData[static::TABLE_HEADER_COLUMN_ORDER_ITEM_REFERENCE]);
                $countTransitionedItem = 0;

                if (!$salesOrderItemTransfer) {
                    throw new Exception(sprintf('Sales oreder item not found for %s order item reference.', $rowData[static::TABLE_HEADER_COLUMN_ORDER_ITEM_REFERENCE]));
                }

                $result = $this->getFactory()->getOmsFacade()->triggerEventForOneOrderItem(
                    $rowData[static::TABLE_HEADER_COLUMN_ORDER_ITEM_OMS_EVENT_STATE],
                    $salesOrderItemTransfer->getIdSalesOrderItem()
                );

                if ($result !== null) {
                    $successfullyProcessedRowsCount++;
                }

                $this->logOutput(
                    $rowNumber,
                    $successfullyProcessedRowsCount,
                    $totalRowsCount,
                    $rowData,
                    ($result !== null)
                );
            }
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            if ($exception->getMessage() == !$input->getOption(static::OPTION_IGNORE_ERRORS)) {
                return static::CODE_ERROR;
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
            static::TABLE_HEADER_COLUMN_ORDER_REFERENCE,
            static::TABLE_HEADER_COLUMN_ORDER_ITEM_REFERENCE,
            static::TABLE_HEADER_COLUMN_ORDER_ITEM_OMS_EVENT_STATE,
            static::TABLE_HEADER_COLUMN_RESULT,
        ]);
        $table->render();

        $this->outputTable = $table;
    }

    /**
     * @param int $rowNumber
     * @param int $successfullyProcessedRowsCount
     * @param int $totalRowsCount
     * @param array $rowData
     * @param bool $result
     *
     * @return void
     */
    protected function logOutput(
        int $rowNumber,
        int $successfullyProcessedRowsCount,
        int $totalRowsCount,
        array $rowData,
        bool $result
    ): void {
        if ($this->output->isVerbose()) {
            $this->outputTable->appendRow([
                $rowNumber,
                $rowData[static::TABLE_HEADER_COLUMN_ORDER_REFERENCE],
                $rowData[static::TABLE_HEADER_COLUMN_ORDER_ITEM_REFERENCE],
                $rowData[static::TABLE_HEADER_COLUMN_ORDER_ITEM_OMS_EVENT_STATE],
                $result,
            ]);
        }
    }
}
