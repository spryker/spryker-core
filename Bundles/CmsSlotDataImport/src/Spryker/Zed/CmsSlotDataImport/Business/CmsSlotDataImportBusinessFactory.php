<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotDataImport\Business;

use Spryker\Zed\CmsSlotDataImport\Business\DataImportStep\CheckCmsSlotDataStep;
use Spryker\Zed\CmsSlotDataImport\Business\DataImportStep\CheckCmsSlotTemplateDataStep;
use Spryker\Zed\CmsSlotDataImport\Business\DataImportStep\CmsSlotMutatorDataStep;
use Spryker\Zed\CmsSlotDataImport\Business\DataImportStep\CmsSlotTemplateWriterStep;
use Spryker\Zed\CmsSlotDataImport\Business\DataImportStep\CmsSlotWriterStep;
use Spryker\Zed\CmsSlotDataImport\Business\DataImportStep\TemplatePathToCmsSlotTemplateIdStep;
use Spryker\Zed\CmsSlotDataImport\CmsSlotDataImportDependencyProvider;
use Spryker\Zed\CmsSlotDataImport\Dependency\Facade\CmsSlotDataImportToCmsSlotFacadeInterface;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;

/**
 * @method \Spryker\Zed\CmsSlotDataImport\CmsSlotDataImportConfig getConfig()
 */
class CmsSlotDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getCmsSlotDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCmsSlotDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createCmsSlotMutatorDataStep());
        $dataSetStepBroker->addStep($this->createCheckCmsSlotDataStep());
        $dataSetStepBroker->addStep($this->createTemplatePathToCmsSlotTemplateIdStep());
        $dataSetStepBroker->addStep($this->createCmsSlotWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getCmsSlotTemplateDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCmsSlotTemplateDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createCheckCmsSlotTemplateDataStep());
        $dataSetStepBroker->addStep($this->createCmsSlotTemplateWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCheckCmsSlotTemplateDataStep(): DataImportStepInterface
    {
        return new CheckCmsSlotTemplateDataStep($this->getCmsSlotFacade());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCmsSlotTemplateWriterStep(): DataImportStepInterface
    {
        return new CmsSlotTemplateWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCmsSlotMutatorDataStep(): DataImportStepInterface
    {
        return new CmsSlotMutatorDataStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCheckCmsSlotDataStep(): DataImportStepInterface
    {
        return new CheckCmsSlotDataStep($this->getCmsSlotFacade());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createTemplatePathToCmsSlotTemplateIdStep(): DataImportStepInterface
    {
        return new TemplatePathToCmsSlotTemplateIdStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCmsSlotWriterStep(): DataImportStepInterface
    {
        return new CmsSlotWriterStep();
    }

    /**
     * @return \Spryker\Zed\CmsSlotDataImport\Dependency\Facade\CmsSlotDataImportToCmsSlotFacadeInterface
     */
    public function getCmsSlotFacade(): CmsSlotDataImportToCmsSlotFacadeInterface
    {
        return $this->getProvidedDependency(CmsSlotDataImportDependencyProvider::FACADE_CMS_SLOT);
    }
}
