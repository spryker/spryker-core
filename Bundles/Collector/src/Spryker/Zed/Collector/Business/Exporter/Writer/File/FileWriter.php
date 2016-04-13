<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\File;

use Spryker\Shared\Library\Writer\Csv\CsvWriterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\MultiadapterWriterInterface;

class FileWriter implements MultiadapterWriterInterface
{

    protected $fileWriterAdapter;

    /**
     * @param array $dataSet
     * @param string $type
     *
     * @return bool
     */
    public function write(array $dataSet, $type = '')
    {
        return $this->fileWriterAdapter->write($dataSet);
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

    /**
     * @param \Spryker\Shared\Library\Writer\Csv\CsvWriterInterface $fileWriterAdapter
     *
     * @return mixed
     */
    public function setWriterAdapter(CsvWriterInterface $fileWriterAdapter)
    {
        return $this->fileWriterAdapter = $fileWriterAdapter;
    }

}
