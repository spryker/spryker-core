<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\ProductAlternativeDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\ProductAlternativeDataImport\Business\Model\ProductAlternativeWriterStep;
use Spryker\Zed\ProductAlternativeDataImport\Business\Model\Step\AlternativeProductAbstractSkuToProductIdStep;
use Spryker\Zed\ProductAlternativeDataImport\Business\Model\Step\AlternativeProductConcreteSkuToProductIdStep;
use Spryker\Zed\ProductAlternativeDataImport\Business\Model\Step\DataValidationStep;
use Spryker\Zed\ProductAlternativeDataImport\Business\Model\Step\ProductSkuToProductIdStep;

/**
 * @method \Spryker\Zed\ProductAlternativeDataImport\ProductAlternativeDataImportConfig getConfig()
 */
class ProductAlternativeDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getProductAlternativeDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductAlternativeDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createDataValidationStep());
        $dataSetStepBroker->addStep($this->createProductSkuToProductIdStep());
        $dataSetStepBroker->addStep($this->createAlternativeProductConcreteSkuToProductIdStep());
        $dataSetStepBroker->addStep($this->createAlternativeProductAbstractSkuToProductIdStep());
        $dataSetStepBroker->addStep($this->createProductAlternativeDataImportWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductAlternativeDataImportWriterStep()
    {
        return new ProductAlternativeWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAlternativeProductConcreteSkuToProductIdStep()
    {
        return new AlternativeProductConcreteSkuToProductIdStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAlternativeProductAbstractSkuToProductIdStep()
    {
        return new AlternativeProductAbstractSkuToProductIdStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductSkuToProductIdStep()
    {
        return new ProductSkuToProductIdStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createDataValidationStep()
    {
        return new DataValidationStep();
    }
}
