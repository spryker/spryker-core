<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Generated\Shared\Transfer\SpyDatasetEntityTransfer;
use League\Csv\Writer;
use SplTempFileObject;

class WriterManager implements WriterManagerInterface
{
    /**
     * @var \Spryker\Zed\Dataset\Business\Model\DatasetFinderInterface
     */
    protected $datasetFinder;

    /**
     * @param \Spryker\Zed\Dataset\Business\Model\DatasetFinderInterface $datasetFinder
     */
    public function __construct(DatasetFinderInterface $datasetFinder)
    {
        $this->datasetFinder = $datasetFinder;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetTransfer
     *
     * @return string
     */
    public function getDatasetContentBy(SpyDatasetEntityTransfer $datasetTransfer)
    {
        $writer = $this->getWriter();
        $this->insertDataByTransfer($writer, $datasetTransfer);

        return $writer->getContent();
    }

    /**
     * @param \League\Csv\Writer $writer
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetTransfer
     *
     * @return void
     */
    protected function insertDataByTransfer(Writer $writer, SpyDatasetEntityTransfer $datasetTransfer)
    {
        $rowValues = [];
        $headerUnique = [];
        $header = [''];
        foreach ($datasetTransfer->getSpyDatasetRowColumnValues() as $datasetRowColumnValue) {
            $datasetColumn = $datasetRowColumnValue->getSpyDatasetColumn();

            if (empty($headerUnique[$datasetColumn->getIdDatasetColumn()])) {
                $headerUnique[$datasetColumn->getIdDatasetColumn()] = true;
                array_push($header, $datasetColumn->getTitle());
            }

            $rowValues[$datasetRowColumnValue->getSpyDatasetRow()->getTitle()][] =
                $datasetRowColumnValue->getValue();
        }
        $writer->insertOne($header);
        foreach ($rowValues as $rowTitle => $values) {
            array_unshift($values, $rowTitle);
            $writer->insertOne($values);
        }
    }

    /**
     * @return \League\Csv\Writer
     */
    protected function getWriter()
    {
        return Writer::createFromFileObject(new SplTempFileObject());
    }
}
