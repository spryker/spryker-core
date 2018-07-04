<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\PriceProductDataImport\Business\Model\PriceProductWriterStep;
use Spryker\Zed\PriceProductDataImport\Business\Model\Step\AbstractSkuToIdProductAbstractStep;
use Spryker\Zed\PriceProductDataImport\Business\Model\Step\ConcreteSkuToIdProductStep;
use Spryker\Zed\PriceProductDataImport\Business\Model\Step\CurrencyToIdCurrencyStep;
use Spryker\Zed\PriceProductDataImport\Business\Model\Step\PreparePriceDataStep;
use Spryker\Zed\PriceProductDataImport\Business\Model\Step\StoreToIdStoreStep;
use Spryker\Zed\PriceProductDataImport\Dependency\Facade\PriceProductDataImportToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductDataImport\Dependency\Service\PriceProductDataImportToUtilEncodingServiceInterface;
use Spryker\Zed\PriceProductDataImport\PriceProductDataImportDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProductDataImport\PriceProductDataImportConfig getConfig()
 */
class PriceProductDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createPriceProductDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getPriceProductDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createAbstractSkuToIdProductAbstractStep());
        $dataSetStepBroker->addStep($this->createConcreteSkuToIdProductStep());
        $dataSetStepBroker->addStep($this->createStoreToIdStoreStep());
        $dataSetStepBroker->addStep($this->createCurrencyToIdCurrencyStep());
        $dataSetStepBroker->addStep($this->createPreparePriceDataStep());
        $dataSetStepBroker->addStep(new PriceProductWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAbstractSkuToIdProductAbstractStep(): DataImportStepInterface
    {
        return new AbstractSkuToIdProductAbstractStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createConcreteSkuToIdProductStep(): DataImportStepInterface
    {
        return new ConcreteSkuToIdProductStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStoreToIdStoreStep(): DataImportStepInterface
    {
        return new StoreToIdStoreStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCurrencyToIdCurrencyStep(): DataImportStepInterface
    {
        return new CurrencyToIdCurrencyStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createPreparePriceDataStep(): DataImportStepInterface
    {
        return new PreparePriceDataStep(
            $this->getPriceProductFacade(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductDataImport\Dependency\Facade\PriceProductDataImportToPriceProductFacadeInterface
     */
    public function getPriceProductFacade(): PriceProductDataImportToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductDataImportDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\PriceProductDataImport\Dependency\Service\PriceProductDataImportToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PriceProductDataImportToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PriceProductDataImportDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
