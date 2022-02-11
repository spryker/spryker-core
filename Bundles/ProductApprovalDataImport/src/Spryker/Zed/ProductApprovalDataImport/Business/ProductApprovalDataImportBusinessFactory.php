<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductApprovalDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\ProductApprovalDataImport\Business\Step\ApprovalStatusValidationStep;
use Spryker\Zed\ProductApprovalDataImport\Business\Step\ProductAbstractApprovalStatusWriterStep;

/**
 * @method \Spryker\Zed\ProductApprovalDataImport\ProductApprovalDataImportConfig getConfig()
 */
class ProductApprovalDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getProductAbstractApprovalStatusDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getProductAbstractApprovalStatusDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createApprovalStatusValidationStep());
        $dataSetStepBroker->addStep($this->createProductAbstractApprovalStatusWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductAbstractApprovalStatusWriterStep(): DataImportStepInterface
    {
        return new ProductAbstractApprovalStatusWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createApprovalStatusValidationStep(): DataImportStepInterface
    {
        return new ApprovalStatusValidationStep();
    }
}
