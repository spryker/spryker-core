<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CommentDataImport\Business;

use Spryker\Zed\CommentDataImport\Business\CommentDataImportStep\CommentWriterStep;
use Spryker\Zed\CommentDataImport\Business\CommentDataImportStep\CustomerReferenceToIdCustomerStep;
use Spryker\Zed\CommentDataImport\Business\CommentDataImportStep\QuoteOwnerKeyToCommentThreadOwnerIdStep;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;

/**
 * @method \Spryker\Zed\CommentDataImport\CommentDataImportConfig getConfig()
 * @method \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware createTransactionAwareDataSetStepBroker()
 * @method \Spryker\Zed\DataImport\Business\Model\DataImporter getCsvDataImporterFromConfig(\Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
 */
class CommentDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getCommentDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCommentDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createCustomerReferenceToIdCustomerStep())
            ->addStep($this->createQuoteOwnerKeyToCommentThreadOwnerIdStep())
            ->addStep($this->createCommentWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCommentWriterStep(): DataImportStepInterface
    {
        return new CommentWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createQuoteOwnerKeyToCommentThreadOwnerIdStep(): DataImportStepInterface
    {
        return new QuoteOwnerKeyToCommentThreadOwnerIdStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCustomerReferenceToIdCustomerStep(): DataImportStepInterface
    {
        return new CustomerReferenceToIdCustomerStep();
    }
}
