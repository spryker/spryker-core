<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\File;

use Spryker\Shared\Library\Writer\Csv\CsvWriterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;

class FileWriter implements WriterInterface
{

    /**
     * @var \Spryker\Shared\Library\Writer\Csv\CsvWriterInterface
     */
    protected $fileWriterAdapter;

    /**
     * FileWriter constructor.
     * @param \Spryker\Shared\Library\Writer\Csv\CsvWriterInterface $fileWriterAdapter
     */
    public function __construct(CsvWriterInterface $fileWriterAdapter)
    {
        $this->fileWriterAdapter = $fileWriterAdapter;
    }

    /**
     * @param array $dataSet
     * @param string $type
     *
     * @return bool
     */
    public function write(array $dataSet, $type = '')
    {
        return (bool)$this->fileWriterAdapter->write($dataSet);
    }

    /**
     * @param array $dataSet
     *
     * @return bool
     */
    public function delete(array $dataSet)
    {
        // none for now
        return false;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'file-writer';
    }

}
