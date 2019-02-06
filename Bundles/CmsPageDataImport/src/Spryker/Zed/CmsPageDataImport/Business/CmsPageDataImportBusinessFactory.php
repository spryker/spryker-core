<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsPageDataImport\Business;

use Spryker\Zed\CmsPageDataImport\Business\CmsPage\CmsPagePublishStep;
use Spryker\Zed\CmsPageDataImport\Business\CmsPage\CmsPageWriterStep;
use Spryker\Zed\CmsPageDataImport\Business\CmsPage\PlaceholderExtractorStep;
use Spryker\Zed\CmsPageDataImport\Business\CmsPageStore\CmsPageKeyToIdCmsPageStep;
use Spryker\Zed\CmsPageDataImport\Business\CmsPageStore\CmsPageStoreWriterStep;
use Spryker\Zed\CmsPageDataImport\Business\CmsPageStore\StoreNameToIdStoreStep;
use Spryker\Zed\CmsPageDataImport\Business\DataSet\CmsPageDataSet;
use Spryker\Zed\CmsPageDataImport\CmsPageDataImportDependencyProvider;
use Spryker\Zed\CmsPageDataImport\Dependency\Facade\CmsPageDataImportToCmsFacadeInterface;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;

/**
 * @method \Spryker\Zed\CmsPageDataImport\CmsPageDataImportConfig getConfig()
 */
class CmsPageDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createCmsPageImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getCmsPageDataImporterConfiguration()
        );

        $cmsPageWriterStep = new CmsPageWriterStep();

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(CmsPageWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createPlaceholderExtractorStep([
                CmsPageDataSet::KEY_PLACEHOLDER_TITLE,
                CmsPageDataSet::KEY_PLACEHOLDER_CONTENT,
            ]))
            ->addStep($this->createLocalizedAttributesExtractorStep([
                CmsPageDataSet::KEY_URL,
                CmsPageDataSet::KEY_NAME,
                CmsPageDataSet::KEY_META_TITLE,
                CmsPageDataSet::KEY_META_DESCRIPTION,
                CmsPageDataSet::KEY_META_KEYWORDS,
            ]))
            ->addStep($cmsPageWriterStep);

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createCmsPageStoreImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getCmsPageStoreDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(CmsPageStoreWriterStep::BULK_SIZE);
        $dataSetStepBroker->addStep($this->createStoreNameToIdStoreStep());
        $dataSetStepBroker->addStep($this->createCmsPageKeyToIdCmsPageStep());
        $dataSetStepBroker->addStep(new CmsPageStoreWriterStep());
        $dataSetStepBroker->addStep($this->createCmsPagePublishStep());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createStoreNameToIdStoreStep(): DataImportStepInterface
    {
        return new StoreNameToIdStoreStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createCmsPageKeyToIdCmsPageStep(): DataImportStepInterface
    {
        return new CmsPageKeyToIdCmsPageStep();
    }

    /**
     * @param array $defaultPlaceholder
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createPlaceholderExtractorStep(array $defaultPlaceholder = []): DataImportStepInterface
    {
        return new PlaceholderExtractorStep($defaultPlaceholder);
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createCmsPagePublishStep(): DataImportStepInterface
    {
        return new CmsPagePublishStep($this->getCmsFacade());
    }

    /**
     * @return \Spryker\Zed\CmsPageDataImport\Dependency\Facade\CmsPageDataImportToCmsFacadeInterface
     */
    public function getCmsFacade(): CmsPageDataImportToCmsFacadeInterface
    {
        return $this->getProvidedDependency(CmsPageDataImportDependencyProvider::FACADE_CMS);
    }
}
