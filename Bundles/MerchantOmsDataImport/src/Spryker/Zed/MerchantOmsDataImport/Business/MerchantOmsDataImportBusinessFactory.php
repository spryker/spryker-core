<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantOmsDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\MerchantOmsDataImport\Business\Step\MerchantWriterStep;
use Spryker\Zed\MerchantOmsDataImport\Business\Step\StateMachineProcessWriterStep;

/**
 * @method \Spryker\Zed\MerchantOmsDataImport\MerchantOmsDataImportConfig getConfig()
 */
class MerchantOmsDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStateMachineProcessWriterStep(): DataImportStepInterface
    {
        return new StateMachineProcessWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantWriterStep(): DataImportStepInterface
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
            ->addStep($this->createStateMachineProcessWriterStep())
            ->addStep($this->createMerchantWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }
}
