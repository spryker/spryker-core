<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Reader;

use ArrayObject;
use Exception;
use Generated\Shared\Transfer\DatasetColumnTransfer;
use Generated\Shared\Transfer\DatasetFilePathTransfer;
use Generated\Shared\Transfer\DatasetRowColumnValueTransfer;
use Generated\Shared\Transfer\DatasetRowTransfer;
use Spryker\Zed\Dataset\Business\Exception\DatasetParseException;
use Spryker\Zed\Dataset\Business\Exception\DatasetParseFormatException;
use Spryker\Zed\Dataset\Dependency\Adapter\CsvFactoryInterface;
use Spryker\Zed\Dataset\Dependency\Adapter\CsvReaderInterface;

class Reader implements ReaderInterface
{
    public const OPEN_MODE = 'r';
    public const HEADER_OFFSET = 0;
    public const FIRST_HEADER_KEY = 0;
    public const MIN_COLUMNS = 0;
    public const UTF_16 = 'utf-16';
    public const UTF_8 = 'utf-8';

    /**
     * @var \Spryker\Zed\Dataset\Dependency\Adapter\CsvFactoryInterface
     */
    protected $csvFactory;

    /**
     * @param \Spryker\Zed\Dataset\Dependency\Adapter\CsvFactoryInterface $csvFactory
     */
    public function __construct(CsvFactoryInterface $csvFactory)
    {
        $this->csvFactory = $csvFactory;
    }

    /**
     * @param \Generated\Shared\Transfer\DatasetFilePathTransfer $filePathTransfer
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetParseException
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\DatasetRowColumnValueTransfer[]
     */
    public function parseFileToDataTransfers(DatasetFilePathTransfer $filePathTransfer): ArrayObject
    {
        $readerAdapter = $this->getReader($filePathTransfer);
        $datasetRowColumnValueTransfers = new ArrayObject();
        $datasetColumnValueTransfers = $this->getDatasetColumnTransfers($readerAdapter);

        foreach ($readerAdapter->getRecords() as $row) {
            $rowTitle = array_shift($row);
            $values = array_values($row);
            $datasetRowValueTransfer = $this->getDatasetRowTransfer($rowTitle);

            foreach ($values as $key => $value) {
                if ($value === null) {
                    throw new DatasetParseException('Values can\'t be empty.');
                }
                $datasetRowColumnValueTransfers->append($this->getDatasetRowColumnValueTransfer(
                    $datasetColumnValueTransfers[$key],
                    $datasetRowValueTransfer,
                    $value
                ));
            }
        }

        return $datasetRowColumnValueTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\DatasetFilePathTransfer $filePathTransfer
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetParseException
     *
     * @return \Spryker\Zed\Dataset\Dependency\Adapter\CsvReaderInterface
     */
    protected function getReader(DatasetFilePathTransfer $filePathTransfer): CsvReaderInterface
    {
        try {
            $readerAdapter = $this->csvFactory->createCsvReader($filePathTransfer->getFilePath(), static::OPEN_MODE);
            $readerAdapter->setHeaderOffset(static::HEADER_OFFSET);
        } catch (Exception $e) {
            throw new DatasetParseException('Not valid CSV in text file.');
        }

        return $readerAdapter;
    }

    /**
     * @param \Spryker\Zed\Dataset\Dependency\Adapter\CsvReaderInterface $readerAdapter
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetParseFormatException
     *
     * @return \Generated\Shared\Transfer\DatasetColumnTransfer[]
     */
    protected function getDatasetColumnTransfers(CsvReaderInterface $readerAdapter): array
    {
        $columns = $readerAdapter->getHeader();
        if (count($columns) <= static::MIN_COLUMNS || !empty($columns[static::FIRST_HEADER_KEY])) {
            throw new DatasetParseFormatException('First title column must be empty and document can\'t be empty');
        }
        unset($columns[static::FIRST_HEADER_KEY]);

        $datasetColumnTransfers = [];
        foreach ($columns as $column) {
            $datasetColumnTransfers[] = $this->getDatasetColumnTransfer($column);
        }

        return $datasetColumnTransfers;
    }

    /**
     * @param string $column
     *
     * @return \Generated\Shared\Transfer\DatasetColumnTransfer
     */
    protected function getDatasetColumnTransfer(string $column): DatasetColumnTransfer
    {
        $datasetColumnTransfer = new DatasetColumnTransfer();
        $datasetColumnTransfer->setTitle($column);

        return $datasetColumnTransfer;
    }

    /**
     * @param string $row
     *
     * @return \Generated\Shared\Transfer\DatasetRowTransfer
     */
    protected function getDatasetRowTransfer(string $row): DatasetRowTransfer
    {
        $datasetRowTransfer = new DatasetRowTransfer();
        $datasetRowTransfer->setTitle($row);

        return $datasetRowTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DatasetColumnTransfer $datasetColumnValueTransfer
     * @param \Generated\Shared\Transfer\DatasetRowTransfer $datasetRowValueTransfer
     * @param string $value
     *
     * @return \Generated\Shared\Transfer\DatasetRowColumnValueTransfer
     */
    protected function getDatasetRowColumnValueTransfer(
        DatasetColumnTransfer $datasetColumnValueTransfer,
        DatasetRowTransfer $datasetRowValueTransfer,
        string $value
    ): DatasetRowColumnValueTransfer {
        $datasetRowColumnValueTransfer = new DatasetRowColumnValueTransfer();
        $datasetRowColumnValueTransfer->setDatasetColumn($datasetColumnValueTransfer);
        $datasetRowColumnValueTransfer->setDatasetRow($datasetRowValueTransfer);
        $datasetRowColumnValueTransfer->setValue($value);

        return $datasetRowColumnValueTransfer;
    }
}
