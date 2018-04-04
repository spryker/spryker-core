<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportAfterImportHookInterface;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportBeforeImportHookInterface;

class DataImporterCollection implements
    DataImporterCollectionInterface,
    DataImporterPluginCollectionInterface,
    DataImporterInterface,
    DataImportBeforeImportHookInterface,
    DataImportAfterImportHookInterface
{
    const IMPORT_TYPE = 'full';

    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataImporterInterface[]
     */
    protected $dataImporter = [];

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Plugin\DataImportBeforeImportHookInterface[]
     */
    protected $beforeImportHooks = [];

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Plugin\DataImportAfterImportHookInterface[]
     */
    protected $afterImportHooks = [];

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Plugin\DataImportBeforeImportHookInterface[] $beforeImportHooks
     * @param \Spryker\Zed\DataImport\Dependency\Plugin\DataImportAfterImportHookInterface[] $afterImportHooks
     */
    public function __construct(array $beforeImportHooks = [], array $afterImportHooks = [])
    {
        $this->beforeImportHooks = $beforeImportHooks;
        $this->afterImportHooks = $afterImportHooks;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataImporterInterface $dataImporter
     *
     * @return $this
     */
    public function addDataImporter(DataImporterInterface $dataImporter)
    {
        $this->dataImporter[$dataImporter->getImportType()] = $dataImporter;

        return $this;
    }

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface[] $dataImporterPluginCollection
     *
     * @return $this
     */
    public function addDataImporterPlugins(array $dataImporterPluginCollection)
    {
        foreach ($dataImporterPluginCollection as $dataImporterPlugin) {
            if (is_array($dataImporterPlugin)) {
                $this->addAfter($dataImporterPlugin);

                continue;
            }
            $this->dataImporter[$dataImporterPlugin->getImportType()] = $dataImporterPlugin;
        }

        return $this;
    }

    /**
     * @param array $dataImporterPluginWithAddAfterDefinition
     *
     * @return void
     */
    protected function addAfter(array $dataImporterPluginWithAddAfterDefinition)
    {
        $dataImporterPlugin = $dataImporterPluginWithAddAfterDefinition[0];
        $afterDataImporter = $dataImporterPluginWithAddAfterDefinition[1];

        $addedAfterImporter = false;
        $reorderedDataImporter = [];

        foreach ($this->dataImporter as $dataImporterType => $dataImporter) {
            $reorderedDataImporter[$dataImporterType] = $dataImporter;

            if ($dataImporterType === $afterDataImporter) {
                $reorderedDataImporter[$dataImporterPlugin->getImportType()] = $dataImporterPlugin;
                $addedAfterImporter = true;
            }
        }

        if (!$addedAfterImporter) {
            $reorderedDataImporter[$dataImporterPlugin->getImportType()] = $dataImporterPlugin;
        }

        $this->dataImporter = $reorderedDataImporter;
    }

    /**
     * @return void
     */
    public function beforeImport()
    {
        foreach ($this->beforeImportHooks as $beforeImportHook) {
            $beforeImportHook->beforeImport();
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null)
    {
        $importType = $this->getCurrentImportType($dataImporterConfigurationTransfer);
        $dataImporterReportTransfer = $this->prepareDataImporterReport($importType);

        $this->beforeImport();

        if ($importType !== $this->getImportType()) {
            $this->executeDataImporter(
                $this->dataImporter[$importType],
                $dataImporterReportTransfer,
                $dataImporterConfigurationTransfer
            );

            $this->afterImport();

            return $dataImporterReportTransfer;
        }

        foreach ($this->dataImporter as $dataImporter) {
            $this->executeDataImporter(
                $dataImporter,
                $dataImporterReportTransfer,
                $dataImporterConfigurationTransfer
            );
        }

        $this->afterImport();

        return $dataImporterReportTransfer;
    }

    /**
     * @return void
     */
    public function afterImport()
    {
        foreach ($this->afterImportHooks as $afterImportHook) {
            $afterImportHook->afterImport();
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getImportType()
    {
        return static::IMPORT_TYPE;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataImporterInterface $dataImporter
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $dataImporterReportTransfer
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return void
     */
    protected function executeDataImporter(
        DataImporterInterface $dataImporter,
        DataImporterReportTransfer $dataImporterReportTransfer,
        DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ) {
        $innerDataImportReportTransfer = $dataImporter->import($dataImporterConfigurationTransfer);
        $dataImporterReportTransfer
            ->addDataImporterReport($innerDataImportReportTransfer)
            ->setImportedDataSetCount($dataImporterReportTransfer->getImportedDataSetCount() + $innerDataImportReportTransfer->getImportedDataSetCount());

        if (!$innerDataImportReportTransfer->getIsSuccess()) {
            $dataImporterReportTransfer->setIsSuccess(false);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return string
     */
    protected function getCurrentImportType(DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null)
    {
        if ($dataImporterConfigurationTransfer && $dataImporterConfigurationTransfer->getImportType()) {
            return $dataImporterConfigurationTransfer->getImportType();
        }

        return $this->getImportType();
    }

    /**
     * @param string $importType
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    protected function prepareDataImporterReport($importType)
    {
        $dataImporterReportTransfer = new DataImporterReportTransfer();
        $dataImporterReportTransfer
            ->setImportType($importType)
            ->setIsSuccess(true)
            ->setImportedDataSetCount(0);

        return $dataImporterReportTransfer;
    }
}
