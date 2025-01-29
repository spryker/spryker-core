<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterDataSourceConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use ReflectionClass;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerInterface;
use Spryker\Zed\Kernel\ClassResolver\Business\BusinessFactoryResolver;

/**
 * Specification:
 * - Allows access to spryker/data-import internal models that are currently fulfilling DataImport domain related infrastructural responsibilities.
 * - Use this trait in your concrete data import domain's business factory to have access to such entities.
 */
trait DataImportFactoryTrait
{
    /**
     * @internal
     *
     * @var \Spryker\Zed\DataImport\Business\DataImportBusinessFactory|null
     */
    public ?DataImportBusinessFactory $dataImportFactory = null;

    /**
     * Specification:
     * - Retrieves a data importer from the configuration.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterDataSourceConfigurationTransfer $dataImporterDataSourceConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getCsvDataImporterFromConfig(
        DataImporterDataSourceConfigurationTransfer $dataImporterDataSourceConfigurationTransfer
    ): DataImporterInterface {
        return $this->getDataImportFactory()->getCsvDataImporterFromConfig(
            $this->buildImporterConfiguration($dataImporterDataSourceConfigurationTransfer),
        );
    }

    /**
     * Specification:
     * - Creates transaction aware data set step broker.
     *
     * @api
     *
     * @param int|null $bulkSize
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerInterface|\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepAwareInterface
     */
    public function createTransactionAwareDataSetStepBroker($bulkSize = null): DataSetStepBrokerInterface
    {
        return $this->getDataImportFactory()->createTransactionAwareDataSetStepBroker($bulkSize);
    }

    /**
     * @internal
     *
     * @return \Spryker\Zed\DataImport\Business\DataImportBusinessFactory
     */
    private function getDataImportFactory(): DataImportBusinessFactory
    {
        if ($this->dataImportFactory === null) {
            $this->dataImportFactory = (new BusinessFactoryResolver())->resolve(DataImportBusinessFactory::class);
        }

        return $this->dataImportFactory;
    }

    /**
     * @internal
     *
     * @param \Generated\Shared\Transfer\DataImporterDataSourceConfigurationTransfer $dataImporterDataSourceConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    private function buildImporterConfiguration(DataImporterDataSourceConfigurationTransfer $dataImporterDataSourceConfigurationTransfer)
    {
        $dataImportReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();

        $dataImportDirectory = rtrim($dataImporterDataSourceConfigurationTransfer->getDirectory() ?: $this->getDefaultPath(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        $fullFileName = $this->getModuleDataImportDirectory($dataImporterDataSourceConfigurationTransfer->getModuleName()) . $dataImporterDataSourceConfigurationTransfer->getFileNameOrFail();

        $dataImportReaderConfigurationTransfer
            ->setFileName($fullFileName)
            ->addDirectory($dataImportDirectory);

        $dataImporterConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImporterConfigurationTransfer
            ->setImportType($dataImporterDataSourceConfigurationTransfer->getImportTypeOrFail())
            ->setReaderConfiguration($dataImportReaderConfigurationTransfer);

        return $dataImporterConfigurationTransfer;
    }

    /**
     * @internal
     *
     * @param string $moduleName
     *
     * @return string
     */
    private function getModuleDataImportDirectory(string $moduleName): string
    {
        $reflectionClass = new ReflectionClass(static::class);
        $directory = dirname($reflectionClass->getFileName());

        $moduleRoot = realpath(
            $directory
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..',
        );

        $moduleRoot = $moduleRoot . DIRECTORY_SEPARATOR . $moduleName . DIRECTORY_SEPARATOR;

        return $moduleRoot . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;
    }

    /**
     * @internal
     *
     * @return string
     */
    private function getDefaultPath(): string
    {
        $pathParts = [
            APPLICATION_ROOT_DIR,
            'data',
            'import',
        ];

        return implode(DIRECTORY_SEPARATOR, $pathParts) . DIRECTORY_SEPARATOR;
    }
}
