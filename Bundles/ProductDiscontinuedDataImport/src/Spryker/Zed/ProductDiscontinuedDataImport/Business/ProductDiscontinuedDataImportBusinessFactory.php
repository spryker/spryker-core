<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\ProductDiscontinuedDataImport\Business\Step\ConcreteSkuToIdProductStep;
use Spryker\Zed\ProductDiscontinuedDataImport\Business\Step\NoteExtractorStep;
use Spryker\Zed\ProductDiscontinuedDataImport\Business\Step\ProductDiscontinuedWriterStep;

/**
 * @method \Spryker\Zed\ProductDiscontinuedDataImport\ProductDiscontinuedDataImportConfig getConfig()
 */
class ProductDiscontinuedDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createProductDiscontinuedDataImport(): DataImporterInterface
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getProductDiscontinuedDataImporterConfiguration()
        );

        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware $dataSetStepBroker */
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createAddLocalesStep())
            ->addStep($this->createNoteExtractorStep())
            ->addStep($this->createConcreteSkuToIdProductStep())
            ->addStep(new ProductDiscontinuedWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createNoteExtractorStep(): DataImportStepInterface
    {
        return new NoteExtractorStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createConcreteSkuToIdProductStep(): DataImportStepInterface
    {
        return new ConcreteSkuToIdProductStep();
    }
}
