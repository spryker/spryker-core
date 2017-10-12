<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;

class DataImporterCollection implements DataImporterCollectionInterface, DataImporterInterface
{
    const IMPORT_TYPE = 'full';

    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataImporterInterface[]
     */
    protected $dataImporter = [];

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

        if ($importType !== $this->getImportType()) {
            $this->executeDataImporter(
                $this->dataImporter[$importType],
                $dataImporterReportTransfer,
                $dataImporterConfigurationTransfer
            );

            return $dataImporterReportTransfer;
        }

        foreach ($this->dataImporter as $dataImporter) {
            $this->executeDataImporter(
                $dataImporter,
                $dataImporterReportTransfer,
                $dataImporterConfigurationTransfer
            );
        }

        return $dataImporterReportTransfer;
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
