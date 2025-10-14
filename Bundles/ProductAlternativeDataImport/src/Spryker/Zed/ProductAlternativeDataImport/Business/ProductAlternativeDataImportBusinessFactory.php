<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductAlternativeDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\ProductAlternativeDataImport\Business\Step\AlternativeProductAbstractSkuToProductIdStep;
use Spryker\Zed\ProductAlternativeDataImport\Business\Step\AlternativeProductConcreteSkuToProductIdStep;
use Spryker\Zed\ProductAlternativeDataImport\Business\Step\DataValidationStep;
use Spryker\Zed\ProductAlternativeDataImport\Business\Step\ProductAlternativeWriterStep;
use Spryker\Zed\ProductAlternativeDataImport\Business\Step\ProductSkuToProductIdStep;

/**
 * @method \Spryker\Zed\ProductAlternativeDataImport\ProductAlternativeDataImportConfig getConfig()
 */
class ProductAlternativeDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getProductAlternativeDataImport(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterInterface {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $dataImporterConfigurationTransfer ?? $this->getConfig()->getProductAlternativeDataImporterConfiguration(),
        );

        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware $dataSetStepBroker */
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
