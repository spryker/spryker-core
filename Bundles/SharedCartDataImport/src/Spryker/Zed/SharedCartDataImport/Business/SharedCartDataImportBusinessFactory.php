<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\SharedCartDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\SharedCartDataImport\Business\SharedCartImportStep\CompanyUserKeyToIdCompanyUserStep;
use Spryker\Zed\SharedCartDataImport\Business\SharedCartImportStep\QuoteKeyToIdQuoteStep;
use Spryker\Zed\SharedCartDataImport\Business\SharedCartImportStep\QuotePermissionGroupNameToIdQuotePermissionGroupStep;
use Spryker\Zed\SharedCartDataImport\Business\SharedCartImportStep\SharedCartWriterStep;

/**
 * @method \Spryker\Zed\SharedCartDataImport\SharedCartDataImportConfig getConfig()
 */
class SharedCartDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createSharedCartDataImport(): DataImporterInterface
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getSharedCartDataImporterConfiguration()
        );

        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware $dataSetStepBroker */
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createAddLocalesStep())
            ->addStep($this->createQuoteKeyToIdQuoteStep())
            ->addStep($this->createCompanyUserKeyToIdCompanyUserStep())
            ->addStep($this->createQuotePermissionGroupNameToIdQuotePermissionGroupStep())
            ->addStep(new SharedCartWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createQuoteKeyToIdQuoteStep(): DataImportStepInterface
    {
        return new QuoteKeyToIdQuoteStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCompanyUserKeyToIdCompanyUserStep(): DataImportStepInterface
    {
        return new CompanyUserKeyToIdCompanyUserStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createQuotePermissionGroupNameToIdQuotePermissionGroupStep(): DataImportStepInterface
    {
        return new QuotePermissionGroupNameToIdQuotePermissionGroupStep();
    }
}
