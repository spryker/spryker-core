<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Writer;

use Generated\Shared\Transfer\DatasetTransfer;
use League\Csv\Writer as CsvWriter;
use Spryker\Zed\Dataset\Dependency\Service\DatasetToCsvBridgeInterface;

class Writer implements WriterInterface
{
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
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return string
     */
    public function getCsvByDataset(DatasetTransfer $datasetTransfer): string
    {
        $writer = $this->datasetToCsvBridge->createCsvWriter();
        $this->insertDataByTransfer($writer, $datasetTransfer);

        return $writer->getContent();
    }

    /**
     * @param \League\Csv\Writer $writer
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return void
     */
    protected function insertDataByTransfer(CsvWriter $writer, DatasetTransfer $datasetTransfer): void
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
        $writer->insertOne($header);
        foreach ($rowValues as $rowTitle => $values) {
            array_unshift($values, $rowTitle);
            $writer->insertOne($values);
        }
    }
}
