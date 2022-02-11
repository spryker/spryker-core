<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductApprovalDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\MerchantProductApprovalDataImport\Business\Step\ApprovalStatusValidationStep;
use Spryker\Zed\MerchantProductApprovalDataImport\Business\Step\MerchantProductApprovalStatusDefaultWriterStep;

/**
 * @method \Spryker\Zed\MerchantProductApprovalDataImport\MerchantProductApprovalDataImportConfig getConfig()
 */
class MerchantProductApprovalDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getMerchantProductApprovalStatusDefaultDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getMerchantProductApprovalStatusDefaultDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createApprovalStatusValidationStep());
        $dataSetStepBroker->addStep($this->createMerchantProductApprovalStatusDefaultWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createApprovalStatusValidationStep(): DataImportStepInterface
    {
        return new ApprovalStatusValidationStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantProductApprovalStatusDefaultWriterStep(): DataImportStepInterface
    {
        return new MerchantProductApprovalStatusDefaultWriterStep();
    }
}
