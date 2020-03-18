<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStockDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\MerchantStockDataImport\Business\MerchantStock\Step\MerchantReferenceToIdMerchantStep;
use Spryker\Zed\MerchantStockDataImport\Business\MerchantStock\Step\MerchantStockWriterStep;
use Spryker\Zed\MerchantStockDataImport\Business\MerchantStock\Step\StockNameToIdStockStep;

/**
 * @method \Spryker\Zed\MerchantStockDataImport\MerchantStockDataImportConfig getConfig()
 */
class MerchantStockDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getMerchantStockDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getMerchantStockDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createMerchantReferenceToIdMerchantStep())
            ->addStep($this->createStockNameToIdStockStep())
            ->addStep($this->createMerchantStockWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantReferenceToIdMerchantStep(): DataImportStepInterface
    {
        return new MerchantReferenceToIdMerchantStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStockNameToIdStockStep(): DataImportStepInterface
    {
        return new StockNameToIdStockStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantStockWriterStep(): DataImportStepInterface
    {
        return new MerchantStockWriterStep();
    }
}
