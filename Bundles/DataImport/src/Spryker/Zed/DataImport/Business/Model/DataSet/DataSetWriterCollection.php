<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataSet;

class DataSetWriterCollection implements DataSetWriterInterface
{
    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface[]
     */
    protected $dataSetWriters = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface[] $dataSetWriter
     */
    public function __construct(array $dataSetWriter)
    {
        $this->dataSetWriters = $dataSetWriter;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet)
    {
        foreach ($this->dataSetWriters as $dataSetWriter) {
            $dataSetWriter->write($dataSet);
        }
    }

    /**
     * @return void
     */
    public function flush()
    {
        foreach ($this->dataSetWriters as $dataSetWriter) {
            $dataSetWriter->flush();
        }
    }
}
