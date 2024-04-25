<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantCommissionDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\Common\MerchantCommissionKeyToIdMerchantCommissionDataImportStep;
use Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\MerchantCommission\MerchantCommissionGroupKeyToIdMerchantCommissionGroupDataImportStep;
use Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\MerchantCommission\MerchantCommissionWriteDataImportStep;
use Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\MerchantCommissionAmount\CurrencyCodeToIdCurrencyDataImportStep;
use Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\MerchantCommissionAmount\MerchantCommissionAmountWriteDataImportStep;
use Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\MerchantCommissionGroup\MerchantCommissionGroupWriteDataImportStep;
use Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\MerchantCommissionMerchant\MerchantCommissionMerchantWriteDataImportStep;
use Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\MerchantCommissionMerchant\MerchantReferenceToIdMerchantDataImportStep;
use Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\MerchantCommissionStore\MerchantCommissionStoreWriteDataImportStep;
use Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\MerchantCommissionStore\StoreNameToIdStoreDataImportStep;
use Spryker\Zed\MerchantCommissionDataImport\Business\Validator\DataSetValidator;
use Spryker\Zed\MerchantCommissionDataImport\Business\Validator\DataSetValidatorInterface;

/**
 * @method \Spryker\Zed\MerchantCommissionDataImport\MerchantCommissionDataImportConfig getConfig()
 */
class MerchantCommissionDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getMerchantCommissionGroupDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getMerchantCommissionGroupDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createMerchantCommissionGroupWriteDataImportStep());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getMerchantCommissionDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getMerchantCommissionStoreDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createMerchantCommissionGroupKeyToIdMerchantCommissionGroupDataImportStep());
        $dataSetStepBroker->addStep($this->createMerchantCommissionWriteDataImportStep());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getMerchantCommissionAmountDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getMerchantCommissionAmountDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createCurrencyCodeToIdCurrencyDataImportStep());
        $dataSetStepBroker->addStep($this->createMerchantCommissionKeyToIdMerchantCommissionDataImportStep());
        $dataSetStepBroker->addStep($this->createMerchantCommissionAmountWriteDataImportStep());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getMerchantCommissionStoreDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getMerchantCommissionStoreDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createStoreNameToIdStoreDataImportStep());
        $dataSetStepBroker->addStep($this->createMerchantCommissionKeyToIdMerchantCommissionDataImportStep());
        $dataSetStepBroker->addStep($this->createMerchantCommissionStoreWriteDataImportStep());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getMerchantCommissionMerchantDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getMerchantCommissionMerchantDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createMerchantReferenceToIdMerchantDataImportStep());
        $dataSetStepBroker->addStep($this->createMerchantCommissionKeyToIdMerchantCommissionDataImportStep());
        $dataSetStepBroker->addStep($this->createMerchantCommissionMerchantWriteDataImportStep());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantCommissionGroupWriteDataImportStep(): DataImportStepInterface
    {
        return new MerchantCommissionGroupWriteDataImportStep($this->createDataSetValidator());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantCommissionWriteDataImportStep(): DataImportStepInterface
    {
        return new MerchantCommissionWriteDataImportStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantCommissionAmountWriteDataImportStep(): DataImportStepInterface
    {
        return new MerchantCommissionAmountWriteDataImportStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantCommissionStoreWriteDataImportStep(): DataImportStepInterface
    {
        return new MerchantCommissionStoreWriteDataImportStep($this->createDataSetValidator());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantCommissionMerchantWriteDataImportStep(): DataImportStepInterface
    {
        return new MerchantCommissionMerchantWriteDataImportStep($this->createDataSetValidator());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantCommissionGroupKeyToIdMerchantCommissionGroupDataImportStep(): DataImportStepInterface
    {
        return new MerchantCommissionGroupKeyToIdMerchantCommissionGroupDataImportStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantCommissionKeyToIdMerchantCommissionDataImportStep(): DataImportStepInterface
    {
        return new MerchantCommissionKeyToIdMerchantCommissionDataImportStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStoreNameToIdStoreDataImportStep(): DataImportStepInterface
    {
        return new StoreNameToIdStoreDataImportStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantReferenceToIdMerchantDataImportStep(): DataImportStepInterface
    {
        return new MerchantReferenceToIdMerchantDataImportStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCurrencyCodeToIdCurrencyDataImportStep(): DataImportStepInterface
    {
        return new CurrencyCodeToIdCurrencyDataImportStep();
    }

    /**
     * @return \Spryker\Zed\MerchantCommissionDataImport\Business\Validator\DataSetValidatorInterface
     */
    public function createDataSetValidator(): DataSetValidatorInterface
    {
        return new DataSetValidator();
    }
}
