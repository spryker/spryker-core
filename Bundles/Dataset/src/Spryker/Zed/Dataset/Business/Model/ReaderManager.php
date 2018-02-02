<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use ArrayObject;
use Exception;
use Generated\Shared\Transfer\SpyDatasetColEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetRowColValueEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetRowEntityTransfer;
use League\Csv\CharsetConverter;
use League\Csv\Reader;
use Spryker\Zed\Dataset\Business\Exception\DatasetParseException;
use Spryker\Zed\Dataset\Business\Exception\DatasetParseFormatException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ReaderManager implements ReaderManagerInterface
{
    const OPEN_MODE = 'r';

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetParseException
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\SpyDatasetRowColValueEntityTransfer[]
     */
    public function convertFileToDataTransfers(UploadedFile $file)
    {
        try {
            /**
             * @var \League\Csv\Reader $result
             */
            $result = $this->getReader($file);
        } catch (Exception $e) {
            throw new DatasetParseException();
        }
        $datasetCowColValueTransfers = new ArrayObject();

        $datasetColValueTransfers = $this->getDatasetColTransfers($result);

        foreach ($result as $row) {
            $rowTitle = array_shift($row);
            $datasetRowValueTransfer = $this->getDatasetRowEntityTransfer($rowTitle);
            $values = array_values($row);

            foreach ($values as $key => $value) {
                $datasetCowColValueTransfers->append($this->getDatasetRowColValueEntityTransfer(
                    $datasetColValueTransfers[$key],
                    $datasetRowValueTransfer,
                    $value
                ));
            }
        }

        return $datasetCowColValueTransfers;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return \League\Csv\Reader
     */
    protected function getReader(UploadedFile $file)
    {
        /**
         * @var \League\Csv\Reader $result
         */
        $csv = Reader::createFromPath($file->getRealPath(), static::OPEN_MODE);
        $csv->setHeaderOffset(0);
        $inputBom = $csv->getInputBOM();
        if ($inputBom === Reader::BOM_UTF16_LE || $inputBom === Reader::BOM_UTF16_BE) {
            CharsetConverter::addTo($csv, 'utf-16', 'utf-8');
        }

        return $csv;
    }

    /**
     * @param \League\Csv\Reader $result
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetParseFormatException
     *
     * @return \Generated\Shared\Transfer\SpyDatasetColEntityTransfer[]
     */
    protected function getDatasetColTransfers(Reader $result)
    {
        $cols = $result->getHeader();
        if (count($cols) <= 1 || !empty($cols[0])) {
            throw new DatasetParseFormatException();
        }
        unset($cols[0]);

        $datasetColTransfers = [];
        foreach ($cols as $col) {
            $datasetColTransfers[] = $this->getDatasetColTransfer($col);
        }

        return $datasetColTransfers;
    }

    /**
     * @param string $col
     *
     * @return \Generated\Shared\Transfer\SpyDatasetColEntityTransfer
     */
    private function getDatasetColTransfer($col)
    {
        $datasetColTransfer = new SpyDatasetColEntityTransfer();
        $datasetColTransfer->setTitle($col);

        return $datasetColTransfer;
    }

    /**
     * @param string $row
     *
     * @return \Generated\Shared\Transfer\SpyDatasetRowEntityTransfer
     */
    private function getDatasetRowEntityTransfer($row)
    {
        $datasetColTransfer = new SpyDatasetRowEntityTransfer();
        $datasetColTransfer->setTitle($row);

        return $datasetColTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyDatasetColEntityTransfer $datasetColValueTransfer
     * @param \Generated\Shared\Transfer\SpyDatasetRowEntityTransfer $datasetRowValueTransfer
     * @param string $value
     *
     * @return \Generated\Shared\Transfer\SpyDatasetRowColValueEntityTransfer
     */
    private function getDatasetRowColValueEntityTransfer(
        $datasetColValueTransfer,
        $datasetRowValueTransfer,
        $value
    ) {
        $datasetRowColValueEntityTransfer = new SpyDatasetRowColValueEntityTransfer();
        $datasetRowColValueEntityTransfer->setSpyDatasetCol($datasetColValueTransfer);
        $datasetRowColValueEntityTransfer->setSpyDatasetRow($datasetRowValueTransfer);
        $datasetRowColValueEntityTransfer->setValue($value);

        return $datasetRowColValueEntityTransfer;
    }
}
