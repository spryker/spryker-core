<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabel\DataSet\ProductLabelDataSetInterface;
use Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabel\Hook\ProductLabelAfterImportPublishHook;
use Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabel\ProductLabelAttributeWriterStep;
use Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabel\ProductLabelProductAbstractWriterStep;
use Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabel\ProductLabelWriterStep;
use Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabel\SkusToProductAbstractIdsStep;
use Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabelStore\ProductLabelNameToIdProductLabelStep;
use Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabelStore\ProductLabelStoreWriteStep;
use Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabelStore\StoreNameToIdStoreStep;

/**
 * @method \Spryker\Zed\ProductLabelDataImport\ProductLabelDataImportConfig getConfig()
 */
class ProductLabelDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getProductLabelImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductLabelDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createLocalizedAttributesExtractorStep([ProductLabelDataSetInterface::COL_NAME]))
            ->addStep($this->createSkusToProductAbstractIdsStep())
            ->addStep($this->createProductLabelWriterStep())
            ->addStep($this->createProductLabelAttributeWriteStep())
            ->addStep($this->createProductLabelProductAbstractWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->addAfterImportHook($this->createProductLabelAfterImportPublishHook());

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getProductLabelStoreImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductLabelStoreImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createProductLabelNameToIdProductLabelStep())
            ->addStep($this->createStoreNameToIdStoreStep())
            ->addStep($this->createProductLabelStoreWriteStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createSkusToProductAbstractIdsStep(): DataImportStepInterface
    {
        return new SkusToProductAbstractIdsStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductLabelWriterStep(): DataImportStepInterface
    {
        return new ProductLabelWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductLabelAttributeWriteStep(): DataImportStepInterface
    {
        return new ProductLabelAttributeWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductLabelProductAbstractWriterStep(): DataImportStepInterface
    {
        return new ProductLabelProductAbstractWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportInterface
     */
    public function createProductLabelAfterImportPublishHook(): DataImporterAfterImportInterface
    {
        return new ProductLabelAfterImportPublishHook();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductLabelNameToIdProductLabelStep(): DataImportStepInterface
    {
        return new ProductLabelNameToIdProductLabelStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStoreNameToIdStoreStep(): DataImportStepInterface
    {
        return new StoreNameToIdStoreStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductLabelStoreWriteStep(): DataImportStepInterface
    {
        return new ProductLabelStoreWriteStep();
    }
}
