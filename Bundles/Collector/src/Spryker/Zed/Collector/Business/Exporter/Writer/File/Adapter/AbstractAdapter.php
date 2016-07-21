<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\File\Adapter;

use Spryker\Zed\Collector\Business\Exporter\Exception\FileWriterException;

abstract class AbstractAdapter implements AdapterInterface
{

    /**
     * @var string
     */
    protected $directory;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @param string $directory
     */
    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    /**
     * @param string $directory
     *
     * @return $this
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * @param string $fileName
     *
     * @return $this
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @throws \Spryker\Zed\Collector\Business\Exporter\Exception\FileWriterException
     *
     * @return string
     */
    protected function getAbsolutePath()
    {
        if (!$this->directory) {
            throw new FileWriterException('Path to export file to not set properly');
        }
        if (!$this->fileName) {
            throw new FileWriterException('File name to export to not set properly');
        }

        $absolutePath = $this->directory . DIRECTORY_SEPARATOR . $this->fileName;

        return $absolutePath;
    }

}
