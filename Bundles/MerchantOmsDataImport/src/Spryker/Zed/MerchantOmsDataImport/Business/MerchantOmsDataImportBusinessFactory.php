<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOmsDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\MerchantOmsDataImport\Business\MerchantOmsProcess\Step\MerchantOmsProcessWriterStep;
use Spryker\Zed\MerchantOmsDataImport\Business\MerchantOmsProcess\Step\MerchantWriterStep;

/**
 * @method \Spryker\Zed\MerchantOmsDataImport\MerchantOmsDataImportConfig getConfig()
 */
class MerchantOmsDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantOmsDataImport\Business\MerchantOmsProcess\Step\MerchantOmsProcessWriterStep
     */
    public function createMerchantOmsProcessWriterStep(): MerchantOmsProcessWriterStep
    {
        return new MerchantOmsProcessWriterStep();
    }

    /**
     * @return \Spryker\Zed\MerchantOmsDataImport\Business\MerchantOmsProcess\Step\MerchantWriterStep
     */
    public function createMerchantWriterStep(): MerchantWriterStep
    {
        return new MerchantWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getMerchantOmsProcessDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getMerchantOmsProcessDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createMerchantOmsProcessWriterStep())
            ->addStep($this->createMerchantWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }
}
