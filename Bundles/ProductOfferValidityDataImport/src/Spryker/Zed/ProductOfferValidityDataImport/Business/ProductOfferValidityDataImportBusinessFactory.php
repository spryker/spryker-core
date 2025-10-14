<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferValidityDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\ProductOfferValidityDataImport\Business\Step\ProductOfferReferenceToIdProductOfferStep;
use Spryker\Zed\ProductOfferValidityDataImport\Business\Step\ProductOfferValidityWriterStep;
use Spryker\Zed\ProductOfferValidityDataImport\Dependency\Facade\ProductOfferValidityDataImportToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferValidityDataImport\ProductOfferValidityDataImportDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferValidityDataImport\ProductOfferValidityDataImportConfig getConfig()
 */
class ProductOfferValidityDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createProductOfferValidityDataImporter(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterInterface {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $dataImporterConfigurationTransfer ?? $this->getConfig()->getProductOfferValidityDataImporterConfiguration(),
        );

        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware $dataSetStepBroker */
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createProductOfferReferenceToIdProductOfferStep())
            ->addStep(new ProductOfferValidityWriterStep());

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
     * @return \Spryker\Zed\ProductOfferValidityDataImport\Dependency\Facade\ProductOfferValidityDataImportToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): ProductOfferValidityDataImportToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferValidityDataImportDependencyProvider::FACADE_PRODUCT_OFFER);
    }
}
