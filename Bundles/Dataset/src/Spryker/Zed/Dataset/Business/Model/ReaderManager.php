<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use ArrayObject;
use Exception;
use Generated\Shared\Transfer\SpyDatasetColumnEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetRowColumnValueEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetRowEntityTransfer;
use League\Csv\CharsetConverter;
use League\Csv\Reader;
use Spryker\Zed\Dataset\Business\Exception\DatasetParseException;
use Spryker\Zed\Dataset\Business\Exception\DatasetParseFormatException;

class ReaderManager implements ReaderManagerInterface
{
    const OPEN_MODE = 'r';
    const HEADER_OFFSET = 0;
    const FIRST_HEADER_KEY = 0;
    const MIN_COLUMNS = 0;
    const UTF_16 = 'utf-16';
    const UTF_8 = 'utf-8';

    /**
     * @param string $filePath
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetParseException
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\SpyDatasetRowColumnValueEntityTransfer[]
     */
    public function convertFileToDataTransfers($filePath)
    {
        /** @var \League\Csv\Reader $result */
        $result = $this->getReader($filePath);
        $datasetRowColumnValueTransfers = new ArrayObject();
        $datasetColumnValueTransfers = $this->getDatasetColumnTransfers($result);

        foreach ($result as $row) {
            $rowTitle = array_shift($row);
            $values = array_values($row);
            $datasetRowValueTransfer = $this->getDatasetRowEntityTransfer($rowTitle);

            foreach ($values as $key => $value) {
                if ($value === null) {
                    throw new DatasetParseException();
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
     * @param string $filePath
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetParseException
     *
     * @return \League\Csv\Reader
     */
    protected function getReader($filePath)
    {
        try {
            /** @var \League\Csv\Reader $csv */
            $csv = Reader::createFromPath($filePath, static::OPEN_MODE);
            $csv->setHeaderOffset(static::HEADER_OFFSET);
        } catch (Exception $e) {
            throw new DatasetParseException();
        }
        $inputBom = $csv->getInputBOM();
        if ($inputBom === Reader::BOM_UTF16_LE || $inputBom === Reader::BOM_UTF16_BE) {
            CharsetConverter::addTo($csv, static::UTF_16, static::UTF_8);
        }

        return $csv;
    }

    /**
     * @param \League\Csv\Reader $result
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetParseFormatException
     *
     * @return \Generated\Shared\Transfer\SpyDatasetColumnEntityTransfer[]
     */
    protected function getDatasetColumnTransfers(Reader $result)
    {
        $columns = $result->getHeader();
        if (count($columns) <= static::MIN_COLUMNS || !empty($columns[static::FIRST_HEADER_KEY])) {
            throw new DatasetParseFormatException();
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
    private function getDatasetColumnTransfer($column)
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
    private function getDatasetRowEntityTransfer($row)
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
    private function getDatasetRowColumnValueEntityTransfer(
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
