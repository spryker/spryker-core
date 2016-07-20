<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\File\Adapter;

class CsvAdapter extends AbstractAdapter
{

    /**
     * @var string
     */
    protected $delimiter;

    /**
     * @var string
     */
    protected $enclosure;

    /**
     * @var string
     */
    protected $escape;

    /**
     * @var \SplFileObject
     */
    protected $csvFile;

    /**
     * @param string $directory
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     */
    public function __construct($directory, $delimiter = ",", $enclosure = "\"", $escape = "\\")
    {
        parent::__construct($directory);

        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
    }

    /**
     * @param array $data
     * @param string $type
     *
     * @return int
     */
    public function write(array $data, $type = '')
    {
        $result = 0;
        $csvFile = $this->getCsvFile($data);

        foreach ($data as $key => $row) {
            $result = $csvFile->fputcsv($row);
        }

        return $result;
    }

    /**
     * @param \SplFileObject $csvFile
     * @param array $dataRow
     *
     * @return void
     */
    protected function initializeHeaderColumns(\SplFileObject $csvFile, array $dataRow)
    {
        $csvFile->fputcsv(array_keys($dataRow));
    }

    /**
     * @param array $data
     *
     * @throws \Spryker\Zed\Collector\Business\Exporter\Exception\FileWriterException
     *
     * @return \SplFileObject
     */
    protected function getCsvFile(array $data)
    {
        if (!$this->csvFile) {
            $this->csvFile = new \SplFileObject($this->getAbsolutePath(), 'w');
            $this->csvFile->setCsvControl($this->delimiter, $this->enclosure, $this->escape);

            $this->initializeHeaderColumns($this->csvFile, current($data));
        }

        return $this->csvFile;
    }

}
