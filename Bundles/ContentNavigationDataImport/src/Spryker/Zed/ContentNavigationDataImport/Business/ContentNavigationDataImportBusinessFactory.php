<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigationDataImport\Business;

use Spryker\Zed\ContentNavigationDataImport\Business\Step\CheckContentDataStep;
use Spryker\Zed\ContentNavigationDataImport\Business\Step\CheckLocalizedContentNavigationTermStep;
use Spryker\Zed\ContentNavigationDataImport\Business\Step\ContentNavigationWriterStep;
use Spryker\Zed\ContentNavigationDataImport\Business\Step\PrepareLocalizedContentNavigationTermStep;
use Spryker\Zed\ContentNavigationDataImport\ContentNavigationDataImportDependencyProvider;
use Spryker\Zed\ContentNavigationDataImport\Dependency\Facade\ContentNavigationDataImportToContentInterface;
use Spryker\Zed\ContentNavigationDataImport\Dependency\Facade\ContentNavigationDataImportToContentNavigationFacadeInterface;
use Spryker\Zed\ContentNavigationDataImport\Dependency\Service\ContentNavigationDataImportToUtilEncodingInterface;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;

/**
 * @method \Spryker\Zed\ContentNavigationDataImport\ContentNavigationDataImportConfig getConfig()
 */
class ContentNavigationDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getContentNavigationDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getContentNavigationDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createCheckContentDataStep());
        $dataSetStepBroker->addStep($this->createAddLocalesStep());
        $dataSetStepBroker->addStep($this->createPrepareLocalizedContentNavigationTermStep());
        $dataSetStepBroker->addStep($this->createCheckLocalizedContentNavigationTermStep());
        $dataSetStepBroker->addStep($this->createContentNavigationWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createPrepareLocalizedContentNavigationTermStep(): DataImportStepInterface
    {
        return new PrepareLocalizedContentNavigationTermStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCheckLocalizedContentNavigationTermStep(): DataImportStepInterface
    {
        return new CheckLocalizedContentNavigationTermStep($this->getContentNavigationFacade());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createContentNavigationWriterStep(): DataImportStepInterface
    {
        return new ContentNavigationWriterStep($this->getUtilEncoding());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCheckContentDataStep(): DataImportStepInterface
    {
        return new CheckContentDataStep($this->getContentFacade());
    }

    /**
     * @return \Spryker\Zed\ContentNavigationDataImport\Dependency\Facade\ContentNavigationDataImportToContentNavigationFacadeInterface
     */
    public function getContentNavigationFacade(): ContentNavigationDataImportToContentNavigationFacadeInterface
    {
        return $this->getProvidedDependency(ContentNavigationDataImportDependencyProvider::FACADE_CONTENT_NAVIGATION);
    }

    /**
     * @return \Spryker\Zed\ContentNavigationDataImport\Dependency\Facade\ContentNavigationDataImportToContentInterface
     */
    public function getContentFacade(): ContentNavigationDataImportToContentInterface
    {
        return $this->getProvidedDependency(ContentNavigationDataImportDependencyProvider::FACADE_CONTENT);
    }

    /**
     * @return \Spryker\Zed\ContentNavigationDataImport\Dependency\Service\ContentNavigationDataImportToUtilEncodingInterface
     */
    public function getUtilEncoding(): ContentNavigationDataImportToUtilEncodingInterface
    {
        return $this->getProvidedDependency(ContentNavigationDataImportDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
