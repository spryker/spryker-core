<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetDataImport\Business;

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
 */
class ContentProductSetDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getContentProductSetDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getContentProductSetDataImporterConfiguration());

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
