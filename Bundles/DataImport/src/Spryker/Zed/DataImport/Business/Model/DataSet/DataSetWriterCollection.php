<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataSet;

use Generated\Shared\Transfer\DataSetItemTransfer;
use Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetBulkWriterPluginInterface;
use Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface;

class DataSetWriterCollection implements DataSetWriterInterface
{
    /**
     * @var \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]|\Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetItemWriterPluginInterface[]
     */
    protected $dataSetWriters = [];

    /**
     * @var bool
     */
    protected $isBulkEnabled;

    /**
     * @var string|null
     */
    protected $databaseEngine;

    /**
     * @param \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]|\Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetItemWriterPluginInterface[] $dataSetWriter
     * @param bool $isBulkEnabled
     * @param string|null $databaseEngine
     */
    public function __construct(
        array $dataSetWriter,
        bool $isBulkEnabled = false,
        ?string $databaseEngine = null
    ) {
        $this->dataSetWriters = $dataSetWriter;
        $this->isBulkEnabled = $isBulkEnabled;
        $this->databaseEngine = $databaseEngine;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet)
    {
        foreach ($this->getDatasetWriters() as $dataSetWriter) {
            /**
             * This check was added because of BC and will be removed once the `\Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface` is removed.
             */
            if ($dataSetWriter instanceof DataSetWriterPluginInterface) {
                $dataSetWriter->write($dataSet);

                continue;
            }

            $dataSetItemTransfer = $this->mapDataSetToDataSetItemTransfer($dataSet);
            $dataSetWriter->write($dataSetItemTransfer);
        }
    }

    /**
     * @return void
     */
    public function flush()
    {
        foreach ($this->dataSetWriters as $dataSetWriter) {
            $dataSetWriter->flush();
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Generated\Shared\Transfer\DataSetItemTransfer
     */
    protected function mapDataSetToDataSetItemTransfer(DataSetInterface $dataSet): DataSetItemTransfer
    {
        return (new DataSetItemTransfer())->setPayload(
            $dataSet->getArrayCopy()
        );
    }

    /**
     * Generates DatasetWritersPlugin[] that are matching conditions.
     *
     * @return \Generator
     */
    protected function getDatasetWriters()
    {
        foreach ($this->dataSetWriters as $dataSetWriter) {

            if (
                $this->checkIfDatasetWriterMatchingBulkConditions($dataSetWriter)
                || $this->checkIfDatasetWriterMatchingNonBulkConditions($dataSetWriter)
            ) {
                yield $dataSetWriter;
            }

        }
    }

    /**
     * @param \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface|\Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetItemWriterPluginInterface $dataSetWriterPlugin
     * @return bool
     */
    protected function checkIfDatasetWriterMatchingBulkConditions($datasetWriterPlugin)
    {
        return $this->isBulkEnabled
            && $this->checkIsBulkDatasetWriterPlugin($datasetWriterPlugin)
            && in_array($this->databaseEngine, $datasetWriterPlugin->getCompatibleDatabaseEngines());
    }

    /**
     * @param \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface|\Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetItemWriterPluginInterface $dataSetWriterPlugin
     * @return bool
     */
    protected function checkIfDatasetWriterMatchingNonBulkConditions($datasetWriterPlugin)
    {
        return !$this->isBulkEnabled
            && !$this->checkIsBulkDatasetWriterPlugin($datasetWriterPlugin);
    }

    /**
     * @param \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface|\Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetItemWriterPluginInterface $dataSetWriterPlugin
     * @return bool
     */
    protected function checkIsBulkDatasetWriterPlugin($dataSetWriterPlugin)
    {
        return ($dataSetWriterPlugin instanceof DataSetBulkWriterPluginInterface);
    }
}
