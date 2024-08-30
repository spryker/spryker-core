<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\DataReader\CsvReader;

use Countable;
use Exception;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException;
use Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemStreamException;
use Spryker\Zed\DataImport\Business\Exception\DataReaderException;
use Spryker\Zed\DataImport\Business\Exception\DataSetWithHeaderCombineFailedException;
use Spryker\Zed\DataImport\Business\Model\DataReader\ConfigurableDataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReaderConfigurationInterface;
use Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToFlysystemServiceInterface;

class CsvAdapterReader implements DataReaderInterface, ConfigurableDataReaderInterface, Countable
{
    /**
     * @var string
     */
    protected const ERROR_FILE_NOT_FOUND = 'File "%s" could not be found or is not readable.';

    /**
     * @var string
     */
    protected const ERROR_FILE_STREAM = 'File "%s" can not be streamed: %s';

    /**
     * @var string
     */
    protected const ERROR_COMBINE_DATA = 'Can not combine data set header with current data set. Keys: "%s", Values "%s"';

    /**
     * @var resource
     */
    protected $fileObject;

    /**
     * @var array
     */
    protected array $dataSetKeys;

    /**
     * @var int|null
     */
    protected ?int $offset;

    /**
     * @var int|null
     */
    protected ?int $limit;

    /**
     * @var int
     */
    protected int $count = 0;

    /**
     * @var int
     */
    protected int $key = 0;

    /**
     * @var int
     */
    protected int $importableKey = 0;

    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReaderConfigurationInterface
     */
    protected CsvReaderConfigurationInterface $csvReaderConfiguration;

    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    protected DataSetInterface $dataSet;

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Service\DataImportToFlysystemServiceInterface
     */
    protected DataImportToFlysystemServiceInterface $flysystemService;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReaderConfigurationInterface $csvReaderConfiguration
     * @param \Spryker\Zed\DataImport\Dependency\Service\DataImportToFlysystemServiceInterface $flysystemService
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     */
    public function __construct(
        CsvReaderConfigurationInterface $csvReaderConfiguration,
        DataImportToFlysystemServiceInterface $flysystemService,
        DataSetInterface $dataSet
    ) {
        $this->csvReaderConfiguration = $csvReaderConfiguration;
        $this->dataSet = $dataSet;
        $this->flysystemService = $flysystemService;

        $this->configureReader();
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
     * @return bool
     */
    public function valid(): bool
    {
        if ($this->limit !== null && $this->limit !== 0) {
            if ($this->offset !== null) {
                return ($this->key() < $this->offset + $this->limit);
            }
        }

        return $this->importableKey < $this->count;
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        rewind($this->fileObject);
        $this->resetKeys();

        if ($this->offset) {
            fseek($this->fileObject, $this->offset - 1);
        }

        if ($this->csvReaderConfiguration->hasHeader()) {
            $this->next();
        }
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->key;
    }

    /**
     * @return void
     */
    public function next(): void
    {
        $this->getRowAndGoNext();
    }

    /**
     * @return array|null
     */
    protected function getRowAndGoNext(): ?array
    {
        $this->incrementKey();

        return $this->getRow();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        if (!$this->count) {
            $this->calculateCount();
        }

        return $this->count;
    }

    /**
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataSetWithHeaderCombineFailedException
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    public function current(): DataSetInterface
    {
        $dataSet = [];

        while ($this->valid()) {
            $dataSet = $this->getCurrentRow();

            if (!$this->isEmpty($dataSet)) {
                break;
            }

            $this->next();
        }

        $this->incrementImportableKey();

        if ($this->dataSetKeys) {
            $dataSetBeforeCombine = $dataSet;

            try {
                $dataSet = array_combine($this->dataSetKeys, $dataSet);
            } catch (Exception $e) {
                throw new DataSetWithHeaderCombineFailedException(sprintf(
                    static::ERROR_COMBINE_DATA,
                    implode(', ', $this->dataSetKeys),
                    implode(', ', array_values($dataSetBeforeCombine)),
                ), 0, $e);
            }
        }

        $this->dataSet->exchangeArray($dataSet);

        return $this->dataSet;
    }

    /**
     * @return void
     */
    protected function configureReader(): void
    {
        $this->createFileObject();
        $this->setDataSetKeys();
        $this->setOffsetAndLimit();
    }

    /**
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataReaderException
     *
     * @return void
     */
    protected function createFileObject(): void
    {
        $fileSystemName = $this->csvReaderConfiguration->getFileSystem();
        $fileName = $this->csvReaderConfiguration->getFileName();

        try {
            if (!$this->flysystemService->has($fileSystemName, $fileName)) {
                throw new DataReaderException(sprintf(static::ERROR_FILE_NOT_FOUND, $fileName));
            }
        } catch (FileSystemReadException $exception) {
            throw new DataReaderException(
                sprintf(static::ERROR_FILE_NOT_FOUND . $exception->getMessage(), $fileName),
                $exception->getCode(),
                $exception,
            );
        }

        try {
            $this->fileObject = $this->flysystemService->readStream($fileSystemName, $fileName);
        } catch (FileSystemStreamException $exception) {
            throw new DataReaderException(
                sprintf(static::ERROR_FILE_STREAM . $exception->getMessage(), $fileName),
                $exception->getCode(),
                $exception,
            );
        }
    }

    /**
     * @return void
     */
    protected function setDataSetKeys(): void
    {
        if ($this->csvReaderConfiguration->hasHeader() === false) {
            return;
        }

        $this->dataSetKeys = $this->getRowAndGoNext();
    }

    /**
     * @return void
     */
    protected function setOffsetAndLimit(): void
    {
        $this->offset = $this->csvReaderConfiguration->getOffset();
        $this->limit = $this->csvReaderConfiguration->getLimit();
    }

    /**
     * @return array|null
     */
    protected function getRow(): ?array
    {
        $row = fgetcsv(
            $this->fileObject,
            0,
            $this->csvReaderConfiguration->getDelimiter(),
            $this->csvReaderConfiguration->getEnclosure(),
            $this->csvReaderConfiguration->getEscape(),
        );

        if (!$row) {
            return null;
        }

        return $row;
    }

    /**
     * @return array|null
     */
    protected function getCurrentRow(): ?array
    {
        /** @var int $currentPosition */
        $currentPosition = ftell($this->fileObject);
        $row = $this->getRow();
        fseek($this->fileObject, $currentPosition);

        return $row;
    }

    /**
     * @return void
     */
    protected function calculateCount(): void
    {
        $this->count = 0;

        $this->rewind();

        while (($row = $this->getRowAndGoNext()) !== null) {
            if ($this->isEmpty($row)) {
                continue;
            }

            $this->count++;
        }

        $this->rewind();
    }

    /**
     * @param array $row
     *
     * @return bool
     */
    protected function isEmpty(array $row): bool
    {
        if (count($row) == 1 && $row[0] == '') {
            return true;
        }

        return false;
    }

    /**
     * @return void
     */
    protected function incrementKey(): void
    {
        $this->key++;
    }

    /**
     * @return void
     */
    protected function incrementImportableKey(): void
    {
        $this->importableKey++;
    }

    /**
     * @return void
     */
    protected function resetKeys(): void
    {
        $this->key = 0;
        $this->importableKey = 0;
    }
}
