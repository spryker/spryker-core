<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business;

use Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsBlockNameToCmsBlockIdStep;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsSlotBlockWriterStep;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsSlotKeyToCmsSlotIdStep;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsSlotTemplatePathToCmsSlotTemplateIdStep;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;

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
        $dataSetStepBroker->addStep($this->createCmsSlotBlockWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsSlotTemplatePathToCmsSlotTemplateIdStep
     */
    public function createCmsSlotTemplatePathToCmsSlotTemplateIdStep(): CmsSlotTemplatePathToCmsSlotTemplateIdStep
    {
        return new CmsSlotTemplatePathToCmsSlotTemplateIdStep();
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsSlotKeyToCmsSlotIdStep
     */
    public function createCmsSlotKeyToCmsSlotIdStep(): CmsSlotKeyToCmsSlotIdStep
    {
        return new CmsSlotKeyToCmsSlotIdStep();
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsBlockNameToCmsBlockIdStep
     */
    public function createCmsBlockNameToCmsBlockIdStep(): CmsBlockNameToCmsBlockIdStep
    {
        return new CmsBlockNameToCmsBlockIdStep();
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep\CmsSlotBlockWriterStep
     */
    public function createCmsSlotBlockWriterStep(): CmsSlotBlockWriterStep
    {
        return new CmsSlotBlockWriterStep();
    }
}
