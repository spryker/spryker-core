<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\Writer;

use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class DataImportWriterCollection implements DataImportWriterInterface
{
    /**
     * @var \Spryker\Zed\DataImport\Business\Model\Writer\WriterInterface[]|\Spryker\Zed\DataImport\Business\Model\Writer\FlushInterface[]
     */
    protected $dataImportWriters = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\Writer\WriterInterface[]|\Spryker\Zed\DataImport\Business\Model\Writer\FlushInterface[] $dataImportWriter
     */
    public function __construct(array $dataImportWriter)
    {
        $this->dataImportWriters = $dataImportWriter;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet)
    {
        foreach ($this->dataImportWriters as $dataImportWriter) {
            if ($dataImportWriter instanceof WriterInterface) {
                $dataImportWriter->write($dataSet);
            }
        }
    }

    /**
     * @return void
     */
    public function flush()
    {
        foreach ($this->dataImportWriters as $dataImportWriter) {
            if ($dataImportWriter instanceof FlushInterface) {
                $dataImportWriter->flush();
            }
        }
    }
}
