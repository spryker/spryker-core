<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferStockDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\ProductOfferStockDataImport\Business\Step\ProductOfferReferenceToIdProductOfferStep;
use Spryker\Zed\ProductOfferStockDataImport\Business\Step\ProductOfferStockWriterStep;
use Spryker\Zed\ProductOfferStockDataImport\Business\Step\StockNameToIdStockStep;
use Spryker\Zed\ProductOfferStockDataImport\Dependency\Facade\ProductOfferStockDataImportToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferStockDataImport\ProductOfferStockDataImportDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferStockDataImport\ProductOfferStockDataImportConfig getConfig()
 */
class ProductOfferStockDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createProductOfferStockDataImporter(): DataImporterInterface
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getProductOfferStockDataImporterConfiguration()
        );

        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware $dataSetStepBroker */
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createProductOfferReferenceToIdProductOfferStep())
            ->addStep($this->createStockNameToIdStockStep())
            ->addStep(new ProductOfferStockWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductOfferReferenceToIdProductOfferStep(): DataImportStepInterface
    {
        return new ProductOfferReferenceToIdProductOfferStep($this->getProductOfferFacade());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStockNameToIdStockStep(): DataImportStepInterface
    {
        return new StockNameToIdStockStep();
    }

    /**
     * @return \Spryker\Zed\ProductOfferStockDataImport\Dependency\Facade\ProductOfferStockDataImportToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): ProductOfferStockDataImportToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferStockDataImportDependencyProvider::FACADE_PRODUCT_OFFER);
    }
}
