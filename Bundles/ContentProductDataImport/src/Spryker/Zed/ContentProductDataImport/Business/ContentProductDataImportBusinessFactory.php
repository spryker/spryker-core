<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductDataImport\Business;

use Spryker\Zed\ContentProductDataImport\Business\Model\ContentProductAbstractListWriterStep;
use Spryker\Zed\ContentProductDataImport\Business\Model\Step\ContentProductAbstractListContentKeyToIdStep;
use Spryker\Zed\ContentProductDataImport\Business\Model\Step\ContentProductAbstractListPrepareLocalizedItemsStep;
use Spryker\Zed\ContentProductDataImport\Business\Model\Step\ContentProductAbstractListSkusToIdsStep;
use Spryker\Zed\ContentProductDataImport\ContentProductDataImportDependencyProvider;
use Spryker\Zed\ContentProductDataImport\Dependency\Facade\ContentProductDataImportToContentProductFacadeInterface;
use Spryker\Zed\ContentProductDataImport\Dependency\Service\ContentProductDataImportToUtilEncodingServiceInterface;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;

/**
 * @method \Spryker\Zed\ContentProductDataImport\ContentProductDataImportConfig getConfig()
 */
class ContentProductDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getContentProductAbstractListDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getContentProductAbstractListDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createContentProductAbstractListContentKeyToIdStep());
        $dataSetStepBroker->addStep($this->createContentProductAbstractListSkusToIdsStep());
        $dataSetStepBroker->addStep($this->createContentProductAbstractListPrepareLocalizedItemsStep());
        $dataSetStepBroker->addStep($this->createContentProductAbstractListWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createContentProductAbstractListContentKeyToIdStep(): DataImportStepInterface
    {
        return new ContentProductAbstractListContentKeyToIdStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createContentProductAbstractListSkusToIdsStep(): DataImportStepInterface
    {
        return new ContentProductAbstractListSkusToIdsStep($this->getStore());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createContentProductAbstractListPrepareLocalizedItemsStep(): DataImportStepInterface
    {
        return new ContentProductAbstractListPrepareLocalizedItemsStep(
            $this->getUtilEncodingService(),
            $this->getContentProductFacade()
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
}
