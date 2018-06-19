<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Writer;

use Generated\Shared\Transfer\DatasetTransfer;
use SplTempFileObject;
use Spryker\Zed\Dataset\Dependency\Adapter\CsvFactoryInterface;
use Spryker\Zed\Dataset\Dependency\Adapter\CsvWriterInterface;

class Writer implements WriterInterface
{
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
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return string
     */
    public function getCsvByDataset(DatasetTransfer $datasetTransfer): string
    {
        $writerAdapter = $this->csvFactory->createCsvWriter(new SplTempFileObject());
        $this->insertDataByTransfer($writerAdapter, $datasetTransfer);

        return $writerAdapter->getContent();
    }

    /**
     * @param \Spryker\Zed\Dataset\Dependency\Adapter\CsvWriterInterface $writerAdapter
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return void
     */
    protected function insertDataByTransfer(CsvWriterInterface $writerAdapter, DatasetTransfer $datasetTransfer): void
    {
        $header = [''];
        $rowValues = [];
        foreach ($datasetTransfer->getDatasetRowColumnValues() as $datasetRowColumnValue) {
            $datasetColumn = $datasetRowColumnValue->getDatasetColumn();

            if (!in_array($datasetColumn->getTitle(), $header)) {
                array_push($header, $datasetColumn->getTitle());
            }

            $rowValues[$datasetRowColumnValue->getDatasetRow()->getTitle()][] = $datasetRowColumnValue->getValue();
        }
        $writerAdapter->insertOne($header);
        foreach ($rowValues as $rowTitle => $values) {
            array_unshift($values, $rowTitle);
            $writerAdapter->insertOne($values);
        }
    }
}
