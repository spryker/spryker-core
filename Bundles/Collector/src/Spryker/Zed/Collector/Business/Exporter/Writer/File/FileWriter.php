<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\File;

class FileWriter implements FileWriterInterface
{

    /**
     * @var FileWriterAdapterInterface
     */
    protected $fileWriterAdapter;

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\File\FileWriterAdapterInterface $fileWriterAdapter
     */
    public function __construct(FileWriterAdapterInterface $fileWriterAdapter)
    {
        $this->fileWriterAdapter = $fileWriterAdapter;
    }

    /**
     * @param string $fileName
     *
     * @return $this
     */
    public function setFileName($fileName)
    {
        $this->fileWriterAdapter->setFileName($fileName);

        return $this;
    }


    /**
     * @param array $dataSet
     * @param string $type
     *
     * @return bool
     */
    public function write(array $dataSet, $type = '')
    {
        return (bool)$this->fileWriterAdapter->write($dataSet, $type);
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
