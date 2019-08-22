<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Business\DataImporter\DataImporterImportGroupAwareInterface;
use Spryker\Zed\DataImport\Business\Exception\InvalidImportGroupException;
use Spryker\Zed\DataImport\DataImportConfig;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportAfterImportHookInterface;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportBeforeImportHookInterface;

class DataImporterCollection implements
    DataImporterCollectionInterface,
    DataImporterPluginCollectionInterface,
    DataImporterInterface,
    DataImportBeforeImportHookInterface,
    DataImportAfterImportHookInterface
{
    public const IMPORT_TYPE = 'full';

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
     * @var \Spryker\Zed\DataImport\DataImportConfig|null
     */
    protected $config = null;

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Plugin\DataImportBeforeImportHookInterface[] $beforeImportHooks
     * @param \Spryker\Zed\DataImport\Dependency\Plugin\DataImportAfterImportHookInterface[] $afterImportHooks
     * @param \Spryker\Zed\DataImport\DataImportConfig|null $config
     */
    public function __construct(array $beforeImportHooks = [], array $afterImportHooks = [], ?DataImportConfig $config = null)
    {
        $this->beforeImportHooks = $beforeImportHooks;
        $this->afterImportHooks = $afterImportHooks;
        $this->config = $config;
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
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null)
    {
        $importType = $this->getCurrentImportType($dataImporterConfigurationTransfer);
        $dataImporterReportTransfer = $this->prepareDataImporterReport($importType);
        $dataImporters = $this->getDataImportersByImportGroup($dataImporterConfigurationTransfer);

        $this->beforeImport();

        if ($importType !== $this->getImportType()) {
            $this->executeDataImporter(
                $dataImporters[$importType],
                $dataImporterReportTransfer,
                $dataImporterConfigurationTransfer
            );

            $this->afterImport();

            return $dataImporterReportTransfer;
        }

        $this->runDataImporters($dataImporters, $dataImporterReportTransfer, $dataImporterConfigurationTransfer);

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
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
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
     * @param \Spryker\Zed\DataImport\Business\Model\DataImporterInterface[] $dataImporters
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $dataImporterReportTransfer
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return void
     */
    protected function runDataImporters(
        array $dataImporters,
        DataImporterReportTransfer $dataImporterReportTransfer,
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer
    ): void {
        if ($this->config === null) {
            foreach ($dataImporters as $dataImporter) {
                $this->executeDataImporter(
                    $dataImporter,
                    $dataImporterReportTransfer,
                    $dataImporterConfigurationTransfer
                );
            }

            return;
        }

        foreach ($dataImporters as $dataImporter) {
            if ($this->config->getFullImportTypes()
                && !in_array($dataImporter->getImportType(), $this->config->getFullImportTypes(), true)
            ) {
                continue;
            }

            $this->executeDataImporter(
                $dataImporter,
                $dataImporterReportTransfer,
                $dataImporterConfigurationTransfer
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return string
     */
    protected function getCurrentImportType(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null)
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

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidImportGroupException
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface[]
     */
    protected function getDataImportersByImportGroup(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): array
    {
        if (!$dataImporterConfigurationTransfer
            || $dataImporterConfigurationTransfer->getImportGroup() === DataImportConfig::IMPORT_GROUP_FULL
        ) {
            return $this->dataImporter;
        }

        $dataImporters = [];

        foreach ($this->dataImporter as $dataImporter) {
            if (!$dataImporter instanceof DataImporterImportGroupAwareInterface) {
                continue;
            }

            if ($dataImporter->getImportGroup() === $dataImporterConfigurationTransfer->getImportGroup()) {
                $dataImporters[$dataImporter->getImportType()] = $dataImporter;
            }
        }

        if (!$dataImporters) {
            throw new InvalidImportGroupException(
                sprintf('No data importers found for the import group %s. Make the name of the group is spelled correctly.', $dataImporterConfigurationTransfer->getImportGroup())
            );
        }

        return $dataImporters;
    }
}
