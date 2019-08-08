<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleDataImport\Business;

use Spryker\Zed\ConfigurableBundleDataImport\Business\ConfigurableBundleDataImportStep\ConfigurableBundleTemplateKeyToIdConfigurableBundleTemplate;
use Spryker\Zed\ConfigurableBundleDataImport\Business\ConfigurableBundleDataImportStep\ConfigurableBundleTemplateSlotWriterStep;
use Spryker\Zed\ConfigurableBundleDataImport\Business\ConfigurableBundleDataImportStep\ConfigurableBundleTemplateWriterStep;
use Spryker\Zed\ConfigurableBundleDataImport\Business\ConfigurableBundleDataImportStep\ProductListKeyToIdProductList;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;

/**
 * @method \Spryker\Zed\ConfigurableBundleDataImport\ConfigurableBundleDataImportConfig getConfig()
 * @method \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware createTransactionAwareDataSetStepBroker($bulkSize = null)
 * @method \Spryker\Zed\DataImport\Business\Model\DataImporter getCsvDataImporterFromConfig(\Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
 */
class ConfigurableBundleDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getConfigurableBundleTemplateDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getConfigurableBundleTemplateDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createConfigurableBundleTemplateWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getConfigurableBundleTemplateSlotDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getConfigurableBundleTemplateSlotDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createConfigurableBundleTemplateKeyToIdConfigurableBundleTemplateStep())
            ->addStep($this->createProductListKeyToIdProductListStep())
            ->addStep($this->createConfigurableBundleTemplateSlotWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createConfigurableBundleTemplateWriterStep(): DataImportStepInterface
    {
        return new ConfigurableBundleTemplateWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createConfigurableBundleTemplateSlotWriterStep(): DataImportStepInterface
    {
        return new ConfigurableBundleTemplateSlotWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createConfigurableBundleTemplateKeyToIdConfigurableBundleTemplateStep(): DataImportStepInterface
    {
        return new ConfigurableBundleTemplateKeyToIdConfigurableBundleTemplate();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductListKeyToIdProductListStep(): DataImportStepInterface
    {
        return new ProductListKeyToIdProductList();
    }
}
