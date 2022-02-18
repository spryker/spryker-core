<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ContentProductDataImport\Business;

use Spryker\Zed\ContentProductDataImport\Business\Model\ContentProductAbstractListWriterStep;
use Spryker\Zed\ContentProductDataImport\Business\Model\Step\ContentProductAbstractListCheckContentDataStep;
use Spryker\Zed\ContentProductDataImport\Business\Model\Step\ContentProductAbstractListPrepareLocalizedTermsStep;
use Spryker\Zed\ContentProductDataImport\Business\Model\Step\ContentProductAbstractListSkusToIdsStep;
use Spryker\Zed\ContentProductDataImport\ContentProductDataImportDependencyProvider;
use Spryker\Zed\ContentProductDataImport\Dependency\Facade\ContentProductDataImportToContentInterface;
use Spryker\Zed\ContentProductDataImport\Dependency\Facade\ContentProductDataImportToContentProductFacadeInterface;
use Spryker\Zed\ContentProductDataImport\Dependency\Service\ContentProductDataImportToUtilEncodingServiceInterface;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;

/**
 * @method \Spryker\Zed\ContentProductDataImport\ContentProductDataImportConfig getConfig()
 * @method \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware createTransactionAwareDataSetStepBroker()
 * @method \Spryker\Zed\DataImport\Business\Model\DataImporter getCsvDataImporterFromConfig(\Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
 */
class ContentProductDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getContentProductAbstractListDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getContentProductAbstractListDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createAddLocalesStep());
        $dataSetStepBroker->addStep($this->createContentProductAbstractListCheckContentDataStep());
        $dataSetStepBroker->addStep($this->createContentProductAbstractListSkusToIdsStep());
        $dataSetStepBroker->addStep($this->createContentProductAbstractListPrepareLocalizedTermsStep());
        $dataSetStepBroker->addStep($this->createContentProductAbstractListWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createContentProductAbstractListCheckContentDataStep(): DataImportStepInterface
    {
        return new ContentProductAbstractListCheckContentDataStep($this->getContentFacade());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createContentProductAbstractListSkusToIdsStep(): DataImportStepInterface
    {
        return new ContentProductAbstractListSkusToIdsStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createContentProductAbstractListPrepareLocalizedTermsStep(): DataImportStepInterface
    {
        return new ContentProductAbstractListPrepareLocalizedTermsStep(
            $this->getUtilEncodingService(),
            $this->getContentProductFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createContentProductAbstractListWriterStep(): DataImportStepInterface
    {
        return new ContentProductAbstractListWriterStep();
    }

    /**
     * @return \Spryker\Zed\ContentProductDataImport\Dependency\Service\ContentProductDataImportToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ContentProductDataImportToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ContentProductDataImportDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ContentProductDataImport\Dependency\Facade\ContentProductDataImportToContentProductFacadeInterface
     */
    public function getContentProductFacade(): ContentProductDataImportToContentProductFacadeInterface
    {
        return $this->getProvidedDependency(ContentProductDataImportDependencyProvider::FACADE_CONTENT_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ContentProductDataImport\Dependency\Facade\ContentProductDataImportToContentInterface
     */
    public function getContentFacade(): ContentProductDataImportToContentInterface
    {
        return $this->getProvidedDependency(ContentProductDataImportDependencyProvider::FACADE_CONTENT);
    }
}
