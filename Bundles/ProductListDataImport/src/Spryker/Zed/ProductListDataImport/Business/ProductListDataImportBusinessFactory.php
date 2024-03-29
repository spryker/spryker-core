<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductListDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\ProductListDataImport\Business\Model\ProductListToCategoryWriterStep;
use Spryker\Zed\ProductListDataImport\Business\Model\ProductListToProductConcreteWriterStep;
use Spryker\Zed\ProductListDataImport\Business\Model\ProductListWriterStep;
use Spryker\Zed\ProductListDataImport\Business\Model\Step\CategoryKeyToIdCategoryStep;
use Spryker\Zed\ProductListDataImport\Business\Model\Step\ProductConcreteSkuToIdProductConcreteStep;
use Spryker\Zed\ProductListDataImport\Business\Model\Step\ProductListKeyToIdProductListStep;

/**
 * @method \Spryker\Zed\ProductListDataImport\ProductListDataImportConfig getConfig()
 * @method \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware createTransactionAwareDataSetStepBroker($bulkSize = null)
 * @method \Spryker\Zed\DataImport\Business\Model\DataImporter getCsvDataImporterFromConfig(\Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
 */
class ProductListDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createProductListDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getProductListDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new ProductListWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createProductListCategoryDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getProductListCategoryDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createProductListKeyToIdProductListStep())
            ->addStep($this->createCategoryKeyToIdCategoryStep())
            ->addStep(new ProductListToCategoryWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createProductListProductConcreteDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getProductListProductConcreteDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createProductListKeyToIdProductListStep())
            ->addStep($this->createProductConcreteSkuToIdProductConcreteStep())
            ->addStep(new ProductListToProductConcreteWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductConcreteSkuToIdProductConcreteStep(): DataImportStepInterface
    {
        return new ProductConcreteSkuToIdProductConcreteStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductListKeyToIdProductListStep(): DataImportStepInterface
    {
        return new ProductListKeyToIdProductListStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCategoryKeyToIdCategoryStep(): DataImportStepInterface
    {
        return new CategoryKeyToIdCategoryStep();
    }
}
