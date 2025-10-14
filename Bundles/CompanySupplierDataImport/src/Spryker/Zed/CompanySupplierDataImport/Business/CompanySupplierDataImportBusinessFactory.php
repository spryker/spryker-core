<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanySupplierDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\CompanySupplierDataImport\Business\Model\CompanySupplierProductPriceWriterStep;
use Spryker\Zed\CompanySupplierDataImport\Business\Model\CompanySupplierWriterStep;
use Spryker\Zed\CompanySupplierDataImport\Business\Model\CompanyTypeWriterStep;
use Spryker\Zed\CompanySupplierDataImport\Business\Model\Step\CompanyKeyToIdCompanyStep;
use Spryker\Zed\CompanySupplierDataImport\Business\Model\Step\ConcreteSkuToIdProductStep;
use Spryker\Zed\CompanySupplierDataImport\Business\Model\Step\CurrencyToIdCurrencyStep;
use Spryker\Zed\CompanySupplierDataImport\Business\Model\Step\StoreToIdStoreStep;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;

/**
 * @method \Spryker\Zed\CompanySupplierDataImport\CompanySupplierDataImportConfig getConfig()
 * @method \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware createTransactionAwareDataSetStepBroker($bulkSize = null)
 * @method \Spryker\Zed\DataImport\Business\Model\DataImporter getCsvDataImporterFromConfig(\Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
 */
class CompanySupplierDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCompanyTypeDataImport(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterInterface {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $dataImporterConfigurationTransfer ?: $this->getConfig()->getCompanyTypeDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createCompanyKeyToIdCompanyStep());
        $dataSetStepBroker->addStep(new CompanyTypeWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCompanySupplierDataImport(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterInterface {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $dataImporterConfigurationTransfer ?: $this->getConfig()->getCompanySupplierDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createCompanyKeyToIdCompanyStep());
        $dataSetStepBroker->addStep($this->createConcreteSkuToIdProductStep());
        $dataSetStepBroker->addStep(new CompanySupplierWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCompanySupplierProductPriceDataImport(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterInterface {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $dataImporterConfigurationTransfer ?: $this->getConfig()->getCompanySupplierProductPriceDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createCompanyKeyToIdCompanyStep());
        $dataSetStepBroker->addStep($this->createConcreteSkuToIdProductStep());
        $dataSetStepBroker->addStep($this->createCurrencyToIdCurrencyStep());
        $dataSetStepBroker->addStep($this->createStoreToIdStoreStep());
        $dataSetStepBroker->addStep(new CompanySupplierProductPriceWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\CompanySupplierDataImport\Business\Model\Step\CompanyKeyToIdCompanyStep
     */
    protected function createCompanyKeyToIdCompanyStep(): CompanyKeyToIdCompanyStep
    {
        return new CompanyKeyToIdCompanyStep();
    }

    /**
     * @return \Spryker\Zed\CompanySupplierDataImport\Business\Model\Step\ConcreteSkuToIdProductStep
     */
    protected function createConcreteSkuToIdProductStep(): ConcreteSkuToIdProductStep
    {
        return new ConcreteSkuToIdProductStep();
    }

    /**
     * @return \Spryker\Zed\CompanySupplierDataImport\Business\Model\Step\StoreToIdStoreStep
     */
    protected function createStoreToIdStoreStep(): StoreToIdStoreStep
    {
        return new StoreToIdStoreStep();
    }

    /**
     * @return \Spryker\Zed\CompanySupplierDataImport\Business\Model\Step\CurrencyToIdCurrencyStep
     */
    protected function createCurrencyToIdCurrencyStep(): CurrencyToIdCurrencyStep
    {
        return new CurrencyToIdCurrencyStep();
    }
}
