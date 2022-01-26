<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferShoppingListDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\ProductOfferShoppingListDataImport\Business\DataImportStep\ProductOfferShoppingListItemDataImportWriterStep;
use Spryker\Zed\ProductOfferShoppingListDataImport\Communication\Dependency\Facade\ProductOfferShoppingListDataImportToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferShoppingListDataImport\ProductOfferShoppingListDataImportDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferShoppingListDataImport\ProductOfferShoppingListDataImportConfig getConfig()
 * @method \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware createTransactionAwareDataSetStepBroker()
 * @method \Spryker\Zed\DataImport\Business\Model\DataImporter getCsvDataImporterFromConfig(\Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
 */
class ProductOfferShoppingListDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getProductOfferShoppingListItemDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductOfferShoppingListItemDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();

        $dataSetStepBroker->addStep(
            $this->createProductOfferShoppingListItemDataImportWriterStep(),
        );

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductOfferShoppingListItemDataImportWriterStep(): DataImportStepInterface
    {
        return new ProductOfferShoppingListItemDataImportWriterStep(
            $this->getProductOfferFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferShoppingListDataImport\Communication\Dependency\Facade\ProductOfferShoppingListDataImportToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): ProductOfferShoppingListDataImportToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferShoppingListDataImportDependencyProvider::FACADE_PRODUCT_OFFER);
    }
}
