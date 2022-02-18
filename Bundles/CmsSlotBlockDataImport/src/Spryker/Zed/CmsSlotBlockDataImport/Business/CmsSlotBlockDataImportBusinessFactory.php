<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business;

use Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsBlockKeyToCmsBlockIdStep;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsPageKeysToIdsConditionsStep;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsSlotBlockCategoryConditionsStep;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsSlotBlockProductCategoryConditionsStep;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsSlotBlockValidatorStep;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsSlotBlockWriterStep;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsSlotKeyToCmsSlotIdStep;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsSlotTemplatePathToCmsSlotTemplateIdStep;
use Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\AllConditionResolver;
use Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\CategoryKeysToIdsConditionResolver;
use Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\CmsPageKeysToIdsConditionResolver;
use Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionResolverInterface;
use Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ProductAbstractSkusToIdsConditionResolver;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;

/**
 * @method \Spryker\Zed\CmsSlotBlockDataImport\CmsSlotBlockDataImportConfig getConfig()
 * @method \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware createTransactionAwareDataSetStepBroker()
 * @method \Spryker\Zed\DataImport\Business\Model\DataImporter getCsvDataImporterFromConfig(\Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
 */
class CmsSlotBlockDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getCmsSlotBlockDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getCmsSlotBlockDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createCmsSlotTemplatePathToCmsSlotTemplateIdStep());
        $dataSetStepBroker->addStep($this->createCmsSlotKeyToCmsSlotIdStep());
        $dataSetStepBroker->addStep($this->createCmsBlockKeyToCmsBlockIdStep());
        $dataSetStepBroker->addStep($this->createCmsSlotBlockCategoryConditionsStep());
        $dataSetStepBroker->addStep($this->createCmsSlotBlockProductCategoryConditionsStep());
        $dataSetStepBroker->addStep($this->createCmsPageKeysToIdsConditionsStep());
        $dataSetStepBroker->addStep($this->createCmsSlotBlockValidatorStep());
        $dataSetStepBroker->addStep($this->createCmsSlotBlockWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCmsSlotTemplatePathToCmsSlotTemplateIdStep(): DataImportStepInterface
    {
        return new CmsSlotTemplatePathToCmsSlotTemplateIdStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCmsSlotKeyToCmsSlotIdStep(): DataImportStepInterface
    {
        return new CmsSlotKeyToCmsSlotIdStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCmsBlockKeyToCmsBlockIdStep(): DataImportStepInterface
    {
        return new CmsBlockKeyToCmsBlockIdStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCmsSlotBlockValidatorStep(): DataImportStepInterface
    {
        return new CmsSlotBlockValidatorStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCmsSlotBlockCategoryConditionsStep(): DataImportStepInterface
    {
        return new CmsSlotBlockCategoryConditionsStep(
            $this->createAllConditionsResolver(),
            $this->createCategoryKeysToIdsConditionsResolver(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCmsSlotBlockProductCategoryConditionsStep(): DataImportStepInterface
    {
        return new CmsSlotBlockProductCategoryConditionsStep(
            $this->createAllConditionsResolver(),
            $this->createProductAbstractSkusToIdsConditionsResolver(),
            $this->createCategoryKeysToIdsConditionsResolver(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCmsPageKeysToIdsConditionsStep(): DataImportStepInterface
    {
        return new CmsPageKeysToIdsConditionsStep(
            $this->createAllConditionsResolver(),
            $this->createCmsPageKeysToIdsConditionsResolver(),
        );
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionResolverInterface
     */
    public function createAllConditionsResolver(): ConditionResolverInterface
    {
        return new AllConditionResolver();
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionResolverInterface
     */
    public function createProductAbstractSkusToIdsConditionsResolver(): ConditionResolverInterface
    {
        return new ProductAbstractSkusToIdsConditionResolver();
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionResolverInterface
     */
    public function createCategoryKeysToIdsConditionsResolver(): ConditionResolverInterface
    {
        return new CategoryKeysToIdsConditionResolver();
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionResolverInterface
     */
    public function createCmsPageKeysToIdsConditionsResolver(): ConditionResolverInterface
    {
        return new CmsPageKeysToIdsConditionResolver();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCmsSlotBlockWriterStep(): DataImportStepInterface
    {
        return new CmsSlotBlockWriterStep($this->getUtilEncodingService());
    }
}
