<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\ProductMeasurementUnitDataImport\Business\Model\ProductMeasurementBaseUnitWriterStep;
use Spryker\Zed\ProductMeasurementUnitDataImport\Business\Model\ProductMeasurementSalesUnitStoreWriterStep;
use Spryker\Zed\ProductMeasurementUnitDataImport\Business\Model\ProductMeasurementSalesUnitWriterStep;
use Spryker\Zed\ProductMeasurementUnitDataImport\Business\Model\ProductMeasurementUnitWriterStep;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitDataImport\ProductMeasurementUnitDataImportConfig getConfig()
 */
class ProductMeasurementUnitDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getProductMeasurementUnitDataImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductMeasurementUnitDataImportConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createProductMeasurementUnitWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getProductMeasurementBaseUnitDataImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductMeasurementBaseUnitDataImportConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createProductMeasurementBaseUnitWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getProductMeasurementSalesUnitDataImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductMeasurementSalesUnitDataImportConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createProductMeasurementSalesUnitWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getProductMeasurementSalesUnitStoreDataImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductMeasurementSalesUnitStoreDataImportConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createProductMeasurementSalesUnitStoreWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnitDataImport\Business\Model\ProductMeasurementUnitWriterStep
     */
    public function createProductMeasurementUnitWriterStep(): ProductMeasurementUnitWriterStep
    {
        return new ProductMeasurementUnitWriterStep();
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnitDataImport\Business\Model\ProductMeasurementBaseUnitWriterStep
     */
    public function createProductMeasurementBaseUnitWriterStep(): ProductMeasurementBaseUnitWriterStep
    {
        return new ProductMeasurementBaseUnitWriterStep();
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnitDataImport\Business\Model\ProductMeasurementSalesUnitWriterStep
     */
    public function createProductMeasurementSalesUnitWriterStep(): ProductMeasurementSalesUnitWriterStep
    {
        return new ProductMeasurementSalesUnitWriterStep();
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnitDataImport\Business\Model\ProductMeasurementSalesUnitStoreWriterStep
     */
    public function createProductMeasurementSalesUnitStoreWriterStep(): ProductMeasurementSalesUnitStoreWriterStep
    {
        return new ProductMeasurementSalesUnitStoreWriterStep();
    }
}
