<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetDataImport\Business;

use Spryker\Zed\ContentProductSetDataImport\Business\Model\ContentProductSetWriterStep;
use Spryker\Zed\ContentProductSetDataImport\Business\Model\Step\CheckContentDataStep;
use Spryker\Zed\ContentProductSetDataImport\Business\Model\Step\PrepareLocalizedItemsStep;
use Spryker\Zed\ContentProductSetDataImport\ContentProductSetDataImportDependencyProvider;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;

/**
 * @method \Spryker\Zed\ContentProductSetDataImport\ContentProductSetDataImportConfig getConfig()
 */
class ContentProductSetDataImportBusinessFactory extends DataImportBusinessFactory
{
    public function getContentProductSetDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getContentProductSetDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createCheckContentDataStep());
        $dataSetStepBroker->addStep($this->createAddLocalesStep());
        $dataSetStepBroker->addStep($this->createPrepareLocalizedItemsStep());
        $dataSetStepBroker->addStep($this->createContentProductSetWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    public function createCheckContentDataStep(): CheckContentDataStep
    {
        return new CheckContentDataStep($this->getContentFacade());
    }

    public function createPrepareLocalizedItemsStep(): PrepareLocalizedItemsStep
    {
        return new PrepareLocalizedItemsStep();
    }

    public function createContentProductSetWriterStep(): ContentProductSetWriterStep
    {
        new ContentProductSetWriterStep();
    }

    public function getContentFacade()
    {
        return $this->getProvidedDependency(ContentProductSetDataImportDependencyProvider::FACADE_CONTENT);
    }
}
