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
        $rowValues = [];
        $headerUnique = [];
        $header = [''];
        foreach ($datasetTransfer->getSpyDatasetRowColumnValues() as $spyDatasetRowColumnValue) {
            if (empty($headerUnique[$spyDatasetRowColumnValue->getSpyDatasetColumn()->getIdDatasetColumn()])) {
                $headerUnique[$spyDatasetRowColumnValue->getSpyDatasetColumn()->getIdDatasetColumn()] = true;
                array_push($header, $spyDatasetRowColumnValue->getSpyDatasetColumn()->getTitle());
            }
            $rowValues[$spyDatasetRowColumnValue->getSpyDatasetRow()->getTitle()][] = $spyDatasetRowColumnValue->getValue();
        }
        $writer->insertOne($header);

        foreach ($rowValues as $rowTitle => $values) {
            array_unshift($values, $rowTitle);
            $writer->insertOne($values);
        }

        return $writer->getContent();
    }

    /**
     * @return \League\Csv\Writer
     */
    protected function getWriter()
    {
        return Writer::createFromFileObject(new SplTempFileObject());
    }
}
