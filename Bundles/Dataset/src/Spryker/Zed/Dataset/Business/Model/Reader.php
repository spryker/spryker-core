<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use ArrayObject;
use Exception;
use Generated\Shared\Transfer\DatasetColumnTransfer;
use Generated\Shared\Transfer\DatasetFilePathTransfer;
use Generated\Shared\Transfer\DatasetRowColumnValueTransfer;
use Generated\Shared\Transfer\DatasetRowTransfer;
use League\Csv\Reader as CsvReader;
use Spryker\Zed\Dataset\Business\Exception\DatasetParseException;
use Spryker\Zed\Dataset\Business\Exception\DatasetParseFormatException;
use Spryker\Zed\Dataset\Dependency\Service\DatasetToCsvBridgeInterface;

class Reader implements ReaderInterface
{
    const OPEN_MODE = 'r';
    const HEADER_OFFSET = 0;
    const FIRST_HEADER_KEY = 0;
    const MIN_COLUMNS = 0;
    const UTF_16 = 'utf-16';
    const UTF_8 = 'utf-8';

    /**
     * @var \Spryker\Zed\Dataset\Dependency\Service\DatasetToCsvBridgeInterface
     */
    protected $datasetToCsvBridge;

    /**
     * Writer constructor.
     *
     * @param \Spryker\Zed\Dataset\Dependency\Service\DatasetToCsvBridgeInterface $datasetToCsvBridge
     */
    public function __construct(DatasetToCsvBridgeInterface $datasetToCsvBridge)
    {
        $this->datasetToCsvBridge = $datasetToCsvBridge;
    }

    /**
     * @param \Generated\Shared\Transfer\DatasetFilePathTransfer $filePathTransfer
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetParseException
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\DatasetRowColumnValueTransfer[]
     */
    public function parseFileToDataTransfers(DatasetFilePathTransfer $filePathTransfer)
    {
        $reader = $this->getReader($filePathTransfer);
        $datasetRowColumnValueTransfers = new ArrayObject();
        $datasetColumnValueTransfers = $this->getDatasetColumnTransfers($reader);

        foreach ($reader as $row) {
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
     * @return \League\Csv\Reader
     */
    protected function getReader(DatasetFilePathTransfer $filePathTransfer)
    {
        try {
            $csv = $this->datasetToCsvBridge->createCsvReader($filePathTransfer->getFilePath(), static::OPEN_MODE);
            $csv->setHeaderOffset(static::HEADER_OFFSET);
        } catch (Exception $e) {
            throw new DatasetParseException('Not valid CSV in text file.');
        }

        return $csv;
    }

    /**
     * @param \League\Csv\Reader $reader
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetParseFormatException
     *
     * @return \Generated\Shared\Transfer\DatasetColumnTransfer[]
     */
    protected function getDatasetColumnTransfers(CsvReader $reader)
    {
        $columns = $reader->getHeader();
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
    protected function getDatasetColumnTransfer($column)
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
    protected function getDatasetRowTransfer($row)
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
        $value
    ) {
        $datasetRowColumnValueTransfer = new DatasetRowColumnValueTransfer();
        $datasetRowColumnValueTransfer->setDatasetColumn($datasetColumnValueTransfer);
        $datasetRowColumnValueTransfer->setDatasetRow($datasetRowValueTransfer);
        $datasetRowColumnValueTransfer->setValue($value);

        return $datasetRowColumnValueTransfer;
    }
}
