<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerDataImport\Business;

use Spryker\Zed\ContentBannerDataImport\Business\Model\ContentBannerWriterStep;
use Spryker\Zed\ContentBannerDataImport\Business\Model\Step\CheckContentDataStep;
use Spryker\Zed\ContentBannerDataImport\Business\Model\Step\CheckLocalizedContentBannerTermStep;
use Spryker\Zed\ContentBannerDataImport\Business\Model\Step\PrepareLocalizedContentBannerTermStep;
use Spryker\Zed\ContentBannerDataImport\ContentBannerDataImportDependencyProvider;
use Spryker\Zed\ContentBannerDataImport\Dependency\Facade\ContentBannerDataImportToContentBannerInterface;
use Spryker\Zed\ContentBannerDataImport\Dependency\Facade\ContentBannerDataImportToContentInterface;
use Spryker\Zed\ContentBannerDataImport\Dependency\Service\ContentBannerDataImportToUtilEncodingInterface;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;

/**
 * @method \Spryker\Zed\ContentBannerDataImport\ContentBannerDataImportConfig getConfig()
 */
class ContentBannerDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getContentBannerDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getContentBannerDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createCheckContentDataStep());
        $dataSetStepBroker->addStep($this->createAddLocalesStep());
        $dataSetStepBroker->addStep($this->createPrepareLocalizedContentBannerTermStep());
        $dataSetStepBroker->addStep($this->createCheckLocalizedContentBannerTermStep());
        $dataSetStepBroker->addStep($this->createContentBannerWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createPrepareLocalizedContentBannerTermStep(): DataImportStepInterface
    {
        return new PrepareLocalizedContentBannerTermStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCheckLocalizedContentBannerTermStep(): DataImportStepInterface
    {
        return new CheckLocalizedContentBannerTermStep($this->getContentBannerFacade());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createContentBannerWriterStep(): DataImportStepInterface
    {
        return new ContentBannerWriterStep($this->getUtilEncoding());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCheckContentDataStep(): DataImportStepInterface
    {
        return new CheckContentDataStep($this->getContentFacade());
    }

    /**
     * @return \Spryker\Zed\ContentBannerDataImport\Dependency\Facade\ContentBannerDataImportToContentBannerInterface
     */
    public function getContentBannerFacade(): ContentBannerDataImportToContentBannerInterface
    {
        return $this->getProvidedDependency(ContentBannerDataImportDependencyProvider::FACADE_CONTENT_BANNER);
    }

    /**
     * @return \Spryker\Zed\ContentBannerDataImport\Dependency\Facade\ContentBannerDataImportToContentInterface
     */
    public function getContentFacade(): ContentBannerDataImportToContentInterface
    {
        return $this->getProvidedDependency(ContentBannerDataImportDependencyProvider::FACADE_CONTENT);
    }

    /**
     * @return \Spryker\Zed\ContentBannerDataImport\Dependency\Service\ContentBannerDataImportToUtilEncodingInterface
     */
    public function getUtilEncoding(): ContentBannerDataImportToUtilEncodingInterface
    {
        return $this->getProvidedDependency(ContentBannerDataImportDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
