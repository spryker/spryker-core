<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use ArrayObject;
use Exception;
use Generated\Shared\Transfer\DatasetFilePathTransfer;
use Generated\Shared\Transfer\SpyDatasetColumnEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetRowColumnValueEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetRowEntityTransfer;
use League\Csv\Reader as CsvReader;
use Spryker\Zed\Dataset\Business\Exception\DatasetParseException;
use Spryker\Zed\Dataset\Business\Exception\DatasetParseFormatException;

class Reader implements ReaderInterface
{
    const OPEN_MODE = 'r';
    const HEADER_OFFSET = 0;
    const FIRST_HEADER_KEY = 0;
    const MIN_COLUMNS = 0;
    const UTF_16 = 'utf-16';
    const UTF_8 = 'utf-8';

    /**
     * @param \Generated\Shared\Transfer\DatasetFilePathTransfer $filePathTransfer
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetParseException
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\SpyDatasetRowColumnValueEntityTransfer[]
     */
    public function convertFileToDataTransfers(DatasetFilePathTransfer $filePathTransfer)
    {
        $reader = $this->getReader($filePathTransfer);
        $datasetRowColumnValueTransfers = new ArrayObject();
        $datasetColumnValueTransfers = $this->getDatasetColumnTransfers($reader);

        foreach ($reader as $row) {
            $rowTitle = array_shift($row);
            $values = array_values($row);
            $datasetRowValueTransfer = $this->getDatasetRowEntityTransfer($rowTitle);

            foreach ($values as $key => $value) {
                if ($value === null) {
                    throw new DatasetParseException("Values can't be empty.");
                }
                $datasetRowColumnValueTransfers->append($this->getDatasetRowColumnValueEntityTransfer(
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
     * @return \League\Csv\Reader
     */
    protected function getReader(DatasetFilePathTransfer $filePathTransfer)
    {
        try {
            /** @var \League\Csv\Reader $csv */
            $csv = $this->createCsvReader($filePathTransfer);
            $csv->setHeaderOffset(static::HEADER_OFFSET);
        } catch (Exception $e) {
            throw new DatasetParseException('Not valid csv file');
        }

        return $csv;
    }

    /**
     * @param \Generated\Shared\Transfer\DatasetFilePathTransfer $filePathTransfer
     *
     * @return \League\Csv\Reader
     */
    protected function createCsvReader(DatasetFilePathTransfer $filePathTransfer)
    {
        return CsvReader::createFromPath($filePathTransfer->getFilePath(), static::OPEN_MODE);
    }

    /**
     * @param \League\Csv\Reader $reader
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetParseFormatException
     *
     * @return \Generated\Shared\Transfer\SpyDatasetColumnEntityTransfer[]
     */
    protected function getDatasetColumnTransfers(CsvReader $reader)
    {
        $columns = $reader->getHeader();
        if (count($columns) <= static::MIN_COLUMNS || !empty($columns[static::FIRST_HEADER_KEY])) {
            throw new DatasetParseFormatException("First title column must be empty and document can't be empty");
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
     * @return \Generated\Shared\Transfer\SpyDatasetColumnEntityTransfer
     */
    protected function getDatasetColumnTransfer($column)
    {
        $datasetColumnTransfer = new SpyDatasetColumnEntityTransfer();
        $datasetColumnTransfer->setTitle($column);

        return $datasetColumnTransfer;
    }

    /**
     * @param string $row
     *
     * @return \Generated\Shared\Transfer\SpyDatasetRowEntityTransfer
     */
    protected function getDatasetRowEntityTransfer($row)
    {
        $datasetRowTransfer = new SpyDatasetRowEntityTransfer();
        $datasetRowTransfer->setTitle($row);

        return $datasetRowTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyDatasetColumnEntityTransfer $datasetColumnValueTransfer
     * @param \Generated\Shared\Transfer\SpyDatasetRowEntityTransfer $datasetRowValueTransfer
     * @param string $value
     *
     * @return \Generated\Shared\Transfer\SpyDatasetRowColumnValueEntityTransfer
     */
    protected function getDatasetRowColumnValueEntityTransfer(
        SpyDatasetColumnEntityTransfer $datasetColumnValueTransfer,
        SpyDatasetRowEntityTransfer $datasetRowValueTransfer,
        $value
    ) {
        $datasetRowColumnValueEntityTransfer = new SpyDatasetRowColumnValueEntityTransfer();
        $datasetRowColumnValueEntityTransfer->setSpyDatasetColumn($datasetColumnValueTransfer);
        $datasetRowColumnValueEntityTransfer->setSpyDatasetRow($datasetRowValueTransfer);
        $datasetRowColumnValueEntityTransfer->setValue($value);

        return $datasetRowColumnValueEntityTransfer;
    }
}
