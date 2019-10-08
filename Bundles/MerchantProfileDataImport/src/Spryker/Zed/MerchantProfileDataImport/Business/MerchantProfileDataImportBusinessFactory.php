<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProfileDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\MerchantProfileDataImport\Business\Profile\DataSet\MerchantProfileDataSetInterface;
use Spryker\Zed\MerchantProfileDataImport\Business\Profile\MerchantProfileWriterStep;
use Spryker\Zed\MerchantProfileDataImport\Business\Profile\Step\MerchantKeyToIdMerchantStep;

/**
 * @method \Spryker\Zed\MerchantProfileDataImport\MerchantProfileDataImportConfig getConfig()
 */
class MerchantProfileDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getMerchantProfileDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getMerchantProfileDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createMerchantKeyToIdMerchantStep())
            ->addStep($this->createLocalizedAttributesExtractorStep([
                MerchantProfileDataSetInterface::DESCRIPTION_GLOSSARY_KEY,
                MerchantProfileDataSetInterface::BANNER_URL_GLOSSARY_KEY,
                MerchantProfileDataSetInterface::DELIVERY_TIME_GLOSSARY,
                MerchantProfileDataSetInterface::TERMS_CONDITIONS_GLOSSARY_KEY,
                MerchantProfileDataSetInterface::CANCELLATION_POLICY_GLOSSARY_KEY,
                MerchantProfileDataSetInterface::IMPRINT_GLOSSARY_KEY,
                MerchantProfileDataSetInterface::DATA_PRIVACY_GLOSSARY_KEY,
                MerchantProfileDataSetInterface::URL,
            ]))
            ->addStep($this->createMerchantProfileWriterStep());

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
    public function createMerchantKeyToIdMerchantStep(): DataImportStepInterface
    {
        return new MerchantKeyToIdMerchantStep();
    }
}
