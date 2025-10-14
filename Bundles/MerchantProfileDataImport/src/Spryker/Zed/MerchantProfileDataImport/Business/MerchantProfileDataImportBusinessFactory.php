<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProfileDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\MerchantProfileDataImport\Business\Address\Step\CountryIsoCodeToIdCountryStep;
use Spryker\Zed\MerchantProfileDataImport\Business\Address\Step\MerchantProfileAddressWriterStep;
use Spryker\Zed\MerchantProfileDataImport\Business\Address\Step\MerchantReferenceToIdMerchantProfileStep;
use Spryker\Zed\MerchantProfileDataImport\Business\MerchantProfile\DataSet\MerchantProfileDataSetInterface;
use Spryker\Zed\MerchantProfileDataImport\Business\MerchantProfile\MerchantProfileWriterStep;
use Spryker\Zed\MerchantProfileDataImport\Business\MerchantProfile\Step\MerchantReferenceToIdMerchantStep;

/**
 * @method \Spryker\Zed\MerchantProfileDataImport\MerchantProfileDataImportConfig getConfig()
 */
class MerchantProfileDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getMerchantProfileDataImporter(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterInterface {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $dataImporterConfigurationTransfer ?? $this->getConfig()->getMerchantProfileDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createMerchantReferenceToIdMerchantStep())
            ->addStep($this->createLocalizedAttributesExtractorStep([
                MerchantProfileDataSetInterface::DESCRIPTION_GLOSSARY_KEY,
                MerchantProfileDataSetInterface::BANNER_URL_GLOSSARY_KEY,
                MerchantProfileDataSetInterface::DELIVERY_TIME_GLOSSARY,
                MerchantProfileDataSetInterface::TERMS_CONDITIONS_GLOSSARY_KEY,
                MerchantProfileDataSetInterface::CANCELLATION_POLICY_GLOSSARY_KEY,
                MerchantProfileDataSetInterface::IMPRINT_GLOSSARY_KEY,
                MerchantProfileDataSetInterface::DATA_PRIVACY_GLOSSARY_KEY,
            ]))
            ->addStep($this->createMerchantProfileWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getMerchantProfileAddressDataImport(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $dataImporterConfigurationTransfer ?? $this->getConfig()->getMerchantProfileAddressDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createMerchantReferenceToIdMerchantProfileStep())
            ->addStep($this->createCountryIsoCodeToIdCountryStep())
            ->addStep($this->createMerchantProfileAddressWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantProfileWriterStep(): DataImportStepInterface
    {
        return new MerchantProfileWriterStep();
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
    public function createMerchantReferenceToIdMerchantProfileStep(): DataImportStepInterface
    {
        return new MerchantReferenceToIdMerchantProfileStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCountryIsoCodeToIdCountryStep(): DataImportStepInterface
    {
        return new CountryIsoCodeToIdCountryStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantProfileAddressWriterStep(): DataImportStepInterface
    {
        return new MerchantProfileAddressWriterStep();
    }
}
