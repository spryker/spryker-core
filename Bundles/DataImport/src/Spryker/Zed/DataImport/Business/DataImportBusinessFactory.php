<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImporter;
use Spryker\Zed\DataImport\Business\Model\DataImporterCollection;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\RenameDataSetKeysStep;
use Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReader;
use Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReaderConfiguration;
use Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReaderConfigurationInterface;
use Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBroker;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware;
use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\DataImport\DataImportConfig getConfig()
 */
class DataImportBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getImporter()
    {
        return $this->createDataImporterCollection();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createDataImporterCollection()
    {
        $dataImporterCollection = new DataImporterCollection();

        return $dataImporterCollection;
    }

    /**
     * @param string $importType
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface $reader
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface
     */
    public function createDataImporter($importType, DataReaderInterface $reader)
    {
        return new DataImporter($importType, $reader);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getCsvDataImporterFromConfig(DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
    {
        $csvReader = $this->createCsvReaderFromConfig($dataImporterConfigurationTransfer->getReaderConfiguration());

        return $this->createDataImporter($dataImporterConfigurationTransfer->getImportType(), $csvReader);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer $dataImporterReaderConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface
     */
    public function createCsvReaderFromConfig(DataImporterReaderConfigurationTransfer $dataImporterReaderConfigurationTransfer)
    {
        $csvReaderConfiguration = new CsvReaderConfiguration($dataImporterReaderConfigurationTransfer);

        return $this->createCsvReader($csvReaderConfiguration);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReaderConfigurationInterface $csvReaderConfiguration
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReader|\Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface
     */
    public function createCsvReader(CsvReaderConfigurationInterface $csvReaderConfiguration)
    {
        $csvReader = new CsvReader($csvReaderConfiguration, $this->createDataSet());

        return $csvReader;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerInterface|\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepAwareInterface
     */
    public function createDataSetStepBroker()
    {
        return new DataSetStepBroker();
    }

    /**
     * @param int|null $bulkSize
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerInterface|\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepAwareInterface
     */
    public function createTransactionAwareDataSetStepBroker($bulkSize = null)
    {
        return new DataSetStepBrokerTransactionAware($this->getPropelConnection(), $bulkSize);
    }

    /**
     * @param array $data
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    public function createDataSet(array $data = [])
    {
        return new DataSet($data);
    }

    /**
     * Use this one to rename keys in your data set. `$map` has as key the current data set key and as value the new expected key.
     *
     * @param array $map
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\RenameDataSetKeysStep|\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createRenameDataSetKeysStep(array $map)
    {
        return new RenameDataSetKeysStep($map);
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Facade\DataImportToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionInterface
     */
    protected function getPropelConnection()
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::PROPEL_CONNECTION);
    }
}
