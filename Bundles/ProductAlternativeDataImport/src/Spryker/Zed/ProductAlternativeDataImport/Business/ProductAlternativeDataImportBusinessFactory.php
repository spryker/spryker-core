<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\ProductAlternativeDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\ProductAlternativeDataImport\Business\Model\ProductAlternativeWriterStep;

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
        $dataSetStepBroker->addStep($this->createProductAlternativeDataImportWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeDataImport\Business\Model\ProductAlternativeWriterStep
     */
    public function createProductAlternativeDataImportWriterStep(): ProductAlternativeWriterStep
    {
        return new ProductAlternativeWriterStep();
    }
}
