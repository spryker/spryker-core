<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader;

use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use SplFileObject;
use Spryker\Zed\DataImport\Business\Model\DataReader\FileResolver\FileResolverInterface;
use Spryker\Zed\DataImport\DataImportConfig;

class CsvReaderConfiguration implements CsvReaderConfigurationInterface
{
    /**
     * @var bool
     */
    public const DEFAULT_HAS_HEADER = true;

    /**
     * @var string
     */
    public const DEFAULT_DELIMITER = ',';

    /**
     * @var string
     */
    public const DEFAULT_ENCLOSURE = '"';

    /**
     * @var string
     */
    public const DEFAULT_ESCAPE = '\\';

    public const DEFAULT_FLAGS = SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY | SplFileObject::READ_AHEAD | SplFileObject::DROP_NEW_LINE;

    /**
     * @var string
     */
    protected const DEFAULT_FILE_SYSTEM = 'files-import';

    /**
     * @var \Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer
     */
    protected $dataImporterReaderConfigurationTransfer;

    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataReader\FileResolver\FileResolverInterface
     */
    protected $fileResolver;

    /**
     * @var \Spryker\Zed\DataImport\DataImportConfig
     */
    protected DataImportConfig $dataImportConfig;

    /**
     * @param \Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer $dataImporterReaderConfigurationTransfer
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\FileResolver\FileResolverInterface $fileResolver
     * @param \Spryker\Zed\DataImport\DataImportConfig $dataImportConfig
     */
    public function __construct(
        DataImporterReaderConfigurationTransfer $dataImporterReaderConfigurationTransfer,
        FileResolverInterface $fileResolver,
        DataImportConfig $dataImportConfig
    ) {
        $this->dataImporterReaderConfigurationTransfer = $dataImporterReaderConfigurationTransfer;
        $this->fileResolver = $fileResolver;
        $this->dataImportConfig = $dataImportConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer $dataImporterReaderConfigurationTransfer
     *
     * @return $this
     */
    public function setDataImporterReaderConfigurationTransfer(DataImporterReaderConfigurationTransfer $dataImporterReaderConfigurationTransfer)
    {
        $modified = $dataImporterReaderConfigurationTransfer->modifiedToArray();
        $modified = array_filter($modified, function ($value) {
            return ($value !== null);
        });
        $this->dataImporterReaderConfigurationTransfer->fromArray($modified);

        return $this;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        if ($this->dataImportConfig->isDataImportFromOtherSourceEnabled() === false) {
            return $this->fileResolver->resolveFile($this->dataImporterReaderConfigurationTransfer);
        }

        return $this->dataImporterReaderConfigurationTransfer->getFileName();
    }

    /**
     * @return string
     */
    public function getDelimiter()
    {
        if ($this->dataImporterReaderConfigurationTransfer->getCsvDelimiter()) {
            return $this->dataImporterReaderConfigurationTransfer->getCsvDelimiter();
        }

        return static::DEFAULT_DELIMITER;
    }

    /**
     * @return string
     */
    public function getEnclosure()
    {
        if ($this->dataImporterReaderConfigurationTransfer->getCsvEnclosure()) {
            return $this->dataImporterReaderConfigurationTransfer->getCsvEnclosure();
        }

        return static::DEFAULT_ENCLOSURE;
    }

    /**
     * @return string
     */
    public function getEscape()
    {
        if ($this->dataImporterReaderConfigurationTransfer->getCsvEscape()) {
            return $this->dataImporterReaderConfigurationTransfer->getCsvEscape();
        }

        return static::DEFAULT_ESCAPE;
    }

    /**
     * @return int
     */
    public function getFlags()
    {
        if ($this->dataImporterReaderConfigurationTransfer->getCsvFlags()) {
            return $this->dataImporterReaderConfigurationTransfer->getCsvFlags();
        }

        return static::DEFAULT_FLAGS;
    }

    /**
     * @return bool
     */
    public function hasHeader()
    {
        if ($this->dataImporterReaderConfigurationTransfer->getCsvHasHeader() !== null) {
            return $this->dataImporterReaderConfigurationTransfer->getCsvHasHeader();
        }

        return static::DEFAULT_HAS_HEADER;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return (int)$this->dataImporterReaderConfigurationTransfer->getOffset();
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return (int)$this->dataImporterReaderConfigurationTransfer->getLimit();
    }

    /**
     * @return string
     */
    public function getFileSystem(): string
    {
        return $this->dataImporterReaderConfigurationTransfer->getFileSystem() ?? static::DEFAULT_FILE_SYSTEM;
    }
}
