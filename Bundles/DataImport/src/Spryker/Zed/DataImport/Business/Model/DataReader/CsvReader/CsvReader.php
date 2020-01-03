<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader;

use Countable;
use Exception;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use SplFileObject;
use Spryker\Zed\DataImport\Business\Exception\DataReaderException;
use Spryker\Zed\DataImport\Business\Exception\DataSetWithHeaderCombineFailedException;
use Spryker\Zed\DataImport\Business\Model\DataReader\ConfigurableDataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CsvReader implements DataReaderInterface, ConfigurableDataReaderInterface, Countable
{
    /**
     * @var \SplFileObject
     */
    protected $fileObject;

    /**
     * @var array|null
     */
    protected $dataSetKeys;

    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReaderConfigurationInterface
     */
    protected $csvReaderConfiguration;

    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    protected $dataSet;

    /**
     * @var int|null
     */
    protected $offset;

    /**
     * @var int|null
     */
    protected $limit;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReaderConfigurationInterface $csvReaderConfiguration
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     */
    public function __construct(CsvReaderConfigurationInterface $csvReaderConfiguration, DataSetInterface $dataSet)
    {
        $this->csvReaderConfiguration = $csvReaderConfiguration;
        $this->dataSet = $dataSet;
        $this->configureReader();
    }

    /**
     * @return void
     */
    protected function configureReader()
    {
        $this->createFileObject();
        $this->setCsvControl();
        $this->setFlags();
        $this->setDataSetKeys();
        $this->setOffsetAndLimit();
    }

    /**
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataReaderException
     *
     * @return void
     */
    protected function createFileObject()
    {
        $fileName = $this->csvReaderConfiguration->getFileName();

        if (!is_file($fileName) || !is_readable($fileName)) {
            throw new DataReaderException(sprintf('File "%s" could not be found or is not readable.', $fileName));
        }

        $this->fileObject = new SplFileObject($fileName);
    }

    /**
     * @return void
     */
    protected function setCsvControl()
    {
        $this->fileObject->setCsvControl(
            $this->csvReaderConfiguration->getDelimiter(),
            $this->csvReaderConfiguration->getEnclosure(),
            $this->csvReaderConfiguration->getEscape()
        );
    }

    /**
     * @return void
     */
    protected function setFlags()
    {
        $this->fileObject->setFlags($this->csvReaderConfiguration->getFlags());
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer $dataImportReaderConfigurationTransfer
     *
     * @return $this
     */
    public function configure(DataImporterReaderConfigurationTransfer $dataImportReaderConfigurationTransfer)
    {
        $this->csvReaderConfiguration->setDataImporterReaderConfigurationTransfer($dataImportReaderConfigurationTransfer);

        $this->configureReader();

        return $this;
    }

    /**
     * @return void
     */
    protected function setDataSetKeys()
    {
        if ($this->csvReaderConfiguration->hasHeader()) {
            $this->dataSetKeys = $this->fileObject->current();
            $this->next();
        }
    }

    /**
     * @return void
     */
    protected function setOffsetAndLimit()
    {
        $this->offset = $this->csvReaderConfiguration->getOffset();
        $this->limit = $this->csvReaderConfiguration->getLimit();
    }

    /**
     * Header columns will not be counted.
     *
     * @return int
     */
    public function count()
    {
        $dataSetCount = iterator_count($this->fileObject);
        if ($this->dataSetKeys) {
            --$dataSetCount;
        }

        return $dataSetCount;
    }

    /**
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataSetWithHeaderCombineFailedException
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    public function current()
    {
        $dataSet = $this->fileObject->current();
        if ($this->dataSetKeys) {
            $dataSetBeforeCombine = $dataSet;
            try {
                $dataSet = array_combine($this->dataSetKeys, $dataSet);
            } catch (Exception $e) {
                throw new DataSetWithHeaderCombineFailedException(sprintf(
                    'Can not combine data set header with current data set. Keys: "%s", Values "%s"',
                    implode(', ', $this->dataSetKeys),
                    implode(', ', array_values($dataSetBeforeCombine))
                ), 0, $e);
            }
        }

        $this->dataSet->exchangeArray($dataSet);

        return $this->dataSet;
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->fileObject->next();
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->fileObject->key();
    }

    /**
     * @return bool
     */
    public function valid()
    {
        if ($this->limit !== null && $this->limit !== 0) {
            if ($this->offset !== null) {
                return ($this->key() < $this->offset + $this->limit);
            }
        }

        return $this->fileObject->valid();
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->fileObject->rewind();

        if ($this->offset) {
            $this->fileObject->seek($this->offset - 1);
        }

        if ($this->csvReaderConfiguration->hasHeader()) {
            $this->next();
        }
    }
}
