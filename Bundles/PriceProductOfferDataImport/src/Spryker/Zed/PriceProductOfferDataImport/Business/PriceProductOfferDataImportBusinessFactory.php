<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\PriceProductOfferDataImport\Business\Step\CurrencyToIdCurrencyStep;
use Spryker\Zed\PriceProductOfferDataImport\Business\Step\PreparePriceDataStep;
use Spryker\Zed\PriceProductOfferDataImport\Business\Step\PriceProductOfferWriterStep;
use Spryker\Zed\PriceProductOfferDataImport\Business\Step\PriceProductStoreWriterStep;
use Spryker\Zed\PriceProductOfferDataImport\Business\Step\PriceProductWriterStep;
use Spryker\Zed\PriceProductOfferDataImport\Business\Step\PriceTypeToIdPriceTypeStep;
use Spryker\Zed\PriceProductOfferDataImport\Business\Step\ProductOfferReferenceToProductOfferDataStep;
use Spryker\Zed\PriceProductOfferDataImport\Business\Step\ProductOfferToIdProductStep;
use Spryker\Zed\PriceProductOfferDataImport\Business\Step\StoreToIdStoreStep;
use Spryker\Zed\PriceProductOfferDataImport\Dependency\Facade\PriceProductOfferDataImportToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductOfferDataImport\PriceProductOfferDataImportDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProductOfferDataImport\PriceProductOfferDataImportConfig getConfig()
 */
class PriceProductOfferDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createPriceProductOfferDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getPriceProductOfferDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();

        $dataSetStepBroker->addStep($this->createProductOfferReferenceToProductOfferDataStep());
        $dataSetStepBroker->addStep($this->createProductOfferToIdProductStep());
        $dataSetStepBroker->addStep($this->createPriceTypeToIdPriceTypeStep());
        $dataSetStepBroker->addStep($this->createPriceProductWriterStep());
        $dataSetStepBroker->addStep($this->createStoreToIdStoreStep());
        $dataSetStepBroker->addStep($this->createCurrencyToIdCurrencyStep());
        $dataSetStepBroker->addStep($this->createPreparePriceDataStep());
        $dataSetStepBroker->addStep($this->createPriceProductStoreWriterStep());

        $dataSetStepBroker->addStep(new PriceProductOfferWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createPriceTypeToIdPriceTypeStep(): DataImportStepInterface
    {
        return new PriceTypeToIdPriceTypeStep();
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
    public function createProductOfferReferenceToProductOfferDataStep(): DataImportStepInterface
    {
        return new ProductOfferReferenceToProductOfferDataStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductOfferToIdProductStep(): DataImportStepInterface
    {
        return new ProductOfferToIdProductStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createPriceProductWriterStep(): DataImportStepInterface
    {
        return new PriceProductWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createPriceProductStoreWriterStep(): DataImportStepInterface
    {
        return new PriceProductStoreWriterStep();
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
     * @return \Spryker\Zed\PriceProductOfferDataImport\Dependency\Facade\PriceProductOfferDataImportToPriceProductFacadeInterface
     */
    public function getPriceProductFacade(): PriceProductOfferDataImportToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductOfferDataImportDependencyProvider::FACADE_PRICE_PRODUCT);
    }
}
