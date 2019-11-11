<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business;

use Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsBlockNameToCmsBlockIdStep;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsSlotBlockConditionsCategoryStep;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsSlotBlockConditionsProductStep;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsSlotBlockMapConditionsStep;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsSlotBlockWriterStep;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsSlotKeyToCmsSlotIdStep;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsSlotPositionValidatorStep;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsSlotTemplatePathToCmsSlotTemplateIdStep;
use Spryker\Zed\CmsSlotBlockDataImport\Business\Hook\CmsSlotBlockDataImportAfterImportHook;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;

/**
 * @method \Spryker\Zed\CmsSlotBlockDataImport\CmsSlotBlockDataImportConfig getConfig()
 */
class CmsSlotBlockDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getCmsSlotBlockDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getCmsSlotBlockDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createCmsSlotTemplatePathToCmsSlotTemplateIdStep());
        $dataSetStepBroker->addStep($this->createCmsSlotKeyToCmsSlotIdStep());
        $dataSetStepBroker->addStep($this->createCmsBlockNameToCmsBlockIdStep());
        $dataSetStepBroker->addStep($this->createCmsSlotPositionValidatorStep());
        $dataSetStepBroker->addStep($this->createCmsSlotBlockMapConditionsStep());
        $dataSetStepBroker->addStep($this->createCmsSlotBlockConditionsCategoryStep());
        $dataSetStepBroker->addStep($this->createCmsSlotBlockConditionsProductStep());
        $dataSetStepBroker->addStep($this->createCmsSlotBlockWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->addAfterImportHook($this->createAfterImportHook());

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
    public function createCmsBlockNameToCmsBlockIdStep(): DataImportStepInterface
    {
        return new CmsBlockNameToCmsBlockIdStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCmsSlotPositionValidatorStep(): DataImportStepInterface
    {
        return new CmsSlotPositionValidatorStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCmsSlotBlockMapConditionsStep(): DataImportStepInterface
    {
        return new CmsSlotBlockMapConditionsStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCmsSlotBlockConditionsCategoryStep(): DataImportStepInterface
    {
        return new CmsSlotBlockConditionsCategoryStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCmsSlotBlockConditionsProductStep(): DataImportStepInterface
    {
        return new CmsSlotBlockConditionsProductStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCmsSlotBlockWriterStep(): DataImportStepInterface
    {
        return new CmsSlotBlockWriterStep($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportInterface
     */
    public function createAfterImportHook(): DataImporterAfterImportInterface
    {
        return new CmsSlotBlockDataImportAfterImportHook($this->getEventFacade());
    }
}
