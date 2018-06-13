<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\ProductAlternativeDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
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
    public function getProductAlternativeDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductAlternativeDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createDataValidationStep())
            ->addStep($this->createProductSkuToProductIdStep())
            ->addStep($this->createAlternativeProductConcreteSkuToProductIdStep())
            ->addStep($this->createAlternativeProductAbstractSkuToProductIdStep())
            ->addStep($this->createProductAlternativeDataImportWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductAlternativeDataImportWriterStep(): DataImportStepInterface
    {
        return new ProductAlternativeWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAlternativeProductConcreteSkuToProductIdStep(): DataImportStepInterface
    {
        return new AlternativeProductConcreteSkuToProductIdStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAlternativeProductAbstractSkuToProductIdStep(): DataImportStepInterface
    {
        return new AlternativeProductAbstractSkuToProductIdStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductSkuToProductIdStep(): DataImportStepInterface
    {
        return new ProductSkuToProductIdStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createDataValidationStep(): DataImportStepInterface
    {
        return new DataValidationStep();
    }
}
