<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ContentProductSetDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Orm\Zed\ProductSet\Persistence\SpyProductSetQuery;
use Spryker\Zed\ContentProductSetDataImport\Business\Model\ContentProductSetWriterStep;
use Spryker\Zed\ContentProductSetDataImport\Business\Model\Step\CheckContentDataStep;
use Spryker\Zed\ContentProductSetDataImport\Business\Model\Step\PrepareLocalizedItemsStep;
use Spryker\Zed\ContentProductSetDataImport\Business\Model\Step\ProductSetKeyToIdStep;
use Spryker\Zed\ContentProductSetDataImport\ContentProductSetDataImportDependencyProvider;
use Spryker\Zed\ContentProductSetDataImport\Dependency\Facade\ContentProductSetDataImportToContentInterface;
use Spryker\Zed\ContentProductSetDataImport\Dependency\Service\ContentProductSetDataImportToUtilEncodingServiceInterface;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;

/**
 * @method \Spryker\Zed\ContentProductSetDataImport\ContentProductSetDataImportConfig getConfig()
 * @method \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware createTransactionAwareDataSetStepBroker($bulkSize = null)
 * @method \Spryker\Zed\DataImport\Business\Model\DataImporter getCsvDataImporterFromConfig(\Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
 */
class ContentProductSetDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getContentProductSetDataImport(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $dataImporterConfigurationTransfer ?? $this->getConfig()->getContentProductSetDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createCheckContentDataStep());
        $dataSetStepBroker->addStep($this->createAddLocalesStep());
        $dataSetStepBroker->addStep($this->createProductSetKeyToIdStep());
        $dataSetStepBroker->addStep($this->createPrepareLocalizedItemsStep());
        $dataSetStepBroker->addStep($this->createContentProductSetWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\ContentProductSetDataImport\Business\Model\Step\CheckContentDataStep
     */
    public function createCheckContentDataStep(): CheckContentDataStep
    {
        return new CheckContentDataStep($this->getContentFacade());
    }

    /**
     * @return \Spryker\Zed\ContentProductSetDataImport\Business\Model\Step\ProductSetKeyToIdStep
     */
    public function createProductSetKeyToIdStep(): ProductSetKeyToIdStep
    {
        return new ProductSetKeyToIdStep($this->getProductSetQuery());
    }

    /**
     * @return \Spryker\Zed\ContentProductSetDataImport\Business\Model\Step\PrepareLocalizedItemsStep
     */
    public function createPrepareLocalizedItemsStep(): PrepareLocalizedItemsStep
    {
        return new PrepareLocalizedItemsStep($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Zed\ContentProductSetDataImport\Business\Model\ContentProductSetWriterStep
     */
    public function createContentProductSetWriterStep(): ContentProductSetWriterStep
    {
        return new ContentProductSetWriterStep();
    }

    /**
     * @return \Spryker\Zed\ContentProductSetDataImport\Dependency\Facade\ContentProductSetDataImportToContentInterface
     */
    public function getContentFacade(): ContentProductSetDataImportToContentInterface
    {
        return $this->getProvidedDependency(ContentProductSetDataImportDependencyProvider::FACADE_CONTENT);
    }

    /**
     * @return \Spryker\Zed\ContentProductSetDataImport\Dependency\Service\ContentProductSetDataImportToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ContentProductSetDataImportToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ContentProductSetDataImportDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetQuery
     */
    public function getProductSetQuery(): SpyProductSetQuery
    {
        return $this->getProvidedDependency(ContentProductSetDataImportDependencyProvider::PROPEL_QUERY_PRODUCT_SET);
    }
}
