<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\ConcreteSkuValidationStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\MerchantKeyToIdMerchantStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\MerchantProductOfferStoreWriterStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\MerchantProductOfferWriterStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\MerchantSkuValidationStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\ProductOfferReferenceToIdProductOfferStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\StoreNameToIdStoreStep;

/**
 * @method \Spryker\Zed\MerchantProductOfferDataImport\MerchantProductOfferDataImportConfig getConfig()
 */
class MerchantProductOfferDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getMerchantProductOfferDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getMerchantProductOfferDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();

        $dataSetStepBroker->addStep($this->createMerchantKeyToIdMerchantStep());
        $dataSetStepBroker->addStep($this->createConcreteSkuValidationStep());
        $dataSetStepBroker->addStep($this->createMerchantSkuValidationStep());
        $dataSetStepBroker->addStep($this->createMerchantProductOfferWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getMerchantProductOfferStoreDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getMerchantProductOfferStoreDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();

        $dataSetStepBroker->addStep($this->createProductOfferReferenceToIdProductOfferStep());
        $dataSetStepBroker->addStep($this->createStoreNameToIdStoreStep());
        $dataSetStepBroker->addStep($this->createMerchantProductOfferStoreWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantKeyToIdMerchantStep(): DataImportStepInterface
    {
        return new MerchantKeyToIdMerchantStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createConcreteSkuValidationStep(): DataImportStepInterface
    {
        return new ConcreteSkuValidationStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantSkuValidationStep(): DataImportStepInterface
    {
        return new MerchantSkuValidationStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantProductOfferStoreWriterStep(): DataImportStepInterface
    {
        return new MerchantProductOfferStoreWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStoreNameToIdStoreStep(): DataImportStepInterface
    {
        return new StoreNameToIdStoreStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductOfferReferenceToIdProductOfferStep(): DataImportStepInterface
    {
        return new ProductOfferReferenceToIdProductOfferStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantProductOfferWriterStep(): DataImportStepInterface
    {
        return new MerchantProductOfferWriterStep($this->getEventFacade());
    }
}
