<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use InvalidArgumentException;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\DataImporter\DataImporterDataSetIdentifierAwareInterface;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\DataReader\CsvAdapterReaderConfiguration;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Expander\MerchantCombinedMerchantProductOfferRequestExpander;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Expander\MerchantCombinedMerchantProductOfferRequestExpanderInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\MerchantCombinedProductOfferAccessValidationStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\MerchantCombinedProductOfferAddCurrenciesStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\MerchantCombinedProductOfferAddMerchantReferenceStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\MerchantCombinedProductOfferAddMerchantStocksStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\MerchantCombinedProductOfferAddPriceTypeStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\MerchantCombinedProductOfferAddProductStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\MerchantCombinedProductOfferPriceExtractorStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\MerchantCombinedProductOfferPriceWriterStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\MerchantCombinedProductOfferStockExtractorStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\MerchantCombinedProductOfferStockWriterStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\MerchantCombinedProductOfferStoreWriterStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\MerchantCombinedProductOfferValidityWriterStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\MerchantCombinedProductOfferWriterStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\ApprovalStatusValidationStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\ConcreteSkuValidationStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\MerchantProductOfferStoreWriterStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\MerchantProductOfferWriterStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\MerchantReferenceToIdMerchantStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\MerchantSkuValidationStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\ProductOfferReferenceToIdProductOfferStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\StoreNameToIdStoreStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Validator\MerchantCombinedProductOfferValidator;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Validator\MerchantCombinedProductOfferValidatorInterface;

/**
 * @method \Spryker\Zed\MerchantProductOfferDataImport\MerchantProductOfferDataImportConfig getConfig()
 */
class MerchantProductOfferDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getMerchantProductOfferDataImport(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $dataImporterConfigurationTransfer ?? $this->getConfig()->getMerchantProductOfferDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();

        $dataSetStepBroker->addStep($this->createMerchantReferenceToIdMerchantStep());
        $dataSetStepBroker->addStep($this->createConcreteSkuValidationStep());
        $dataSetStepBroker->addStep($this->createMerchantSkuValidationStep());
        $dataSetStepBroker->addStep($this->createApprovalStatusValidationStep());
        $dataSetStepBroker->addStep($this->createMerchantProductOfferWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getMerchantProductOfferStoreDataImport(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $dataImporterConfigurationTransfer ?? $this->getConfig()->getMerchantProductOfferStoreDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();

        $dataSetStepBroker->addStep($this->createProductOfferReferenceToIdProductOfferStep());
        $dataSetStepBroker->addStep($this->createStoreNameToIdStoreStep());
        $dataSetStepBroker->addStep($this->createMerchantProductOfferStoreWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function createMerchantCombinedMerchantProductOfferDataImport(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterInterface {
        if ($dataImporterConfigurationTransfer === null) {
            throw new InvalidArgumentException(sprintf(
                '%s cannot be null.',
                DataImporterConfigurationTransfer::class,
            ));
        }

        $dataImporter = $this->getCsvDataImporterContextAwareFromConfig($dataImporterConfigurationTransfer);

        if ($dataImporter instanceof DataImporterDataSetIdentifierAwareInterface) {
            $dataImporter->setDataSetIdentifierKey(
                $this->getConfig()->getMerchantCombinedProductOfferDataSetIdentifier(),
            );
        }

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(
            $this->getConfig()->getBulkSizeMerchantCombinedProductOfferImport(),
        );
        $dataSetStepBroker->addStep($this->createMerchantCombinedProductOfferAccessValidationStep());
        $dataSetStepBroker->addStep($this->createMerchantCombinedProductOfferAddProductStep());
        $dataSetStepBroker->addStep($this->createAddStoresStep());
        $dataSetStepBroker->addStep($this->createMerchantCombinedProductOfferAddMerchantStocksStep());
        $dataSetStepBroker->addStep($this->createMerchantCombinedProductOfferAddMerchantReferenceStep());
        $dataSetStepBroker->addStep($this->createMerchantCombinedProductOfferWriterStep());
        $dataSetStepBroker->addStep($this->createMerchantCombinedProductOfferStoreWriterStep());
        $dataSetStepBroker->addStep($this->createMerchantCombinedProductOfferStockExtractorStep());
        $dataSetStepBroker->addStep($this->createMerchantCombinedProductOfferStockWriterStep());
        $dataSetStepBroker->addStep($this->createMerchantCombinedProductOfferValidityWriterStep());
        $dataSetStepBroker->addStep($this->createMerchantCombinedProductOfferPriceExtractorStep());
        $dataSetStepBroker->addStep($this->createMerchantCombinedProductOfferAddCurrenciesStep());
        $dataSetStepBroker->addStep($this->createMerchantCombinedProductOfferAddPriceTypeStep());
        $dataSetStepBroker->addStep($this->createMerchantCombinedProductOfferPriceWriterStep());

        if ($dataImporter instanceof DataSetStepBrokerAwareInterface) {
            $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        }

        return $dataImporter;
    }

    public function createMerchantCombinedProductOfferValidator(): MerchantCombinedProductOfferValidatorInterface
    {
        return new MerchantCombinedProductOfferValidator($this->getConfig());
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
    public function createApprovalStatusValidationStep(): DataImportStepInterface
    {
        return new ApprovalStatusValidationStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantProductOfferStoreWriterStep(): DataImportStepInterface
    {
        return new MerchantProductOfferStoreWriterStep($this->getEventFacade());
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

    public function createMerchantCombinedProductOfferAddProductStep(): DataImportStepInterface
    {
        return new MerchantCombinedProductOfferAddProductStep();
    }

    public function createMerchantCombinedProductOfferWriterStep(): DataImportStepInterface
    {
        return new MerchantCombinedProductOfferWriterStep($this->getEventFacade());
    }

    public function createMerchantCombinedProductOfferStoreWriterStep(): DataImportStepInterface
    {
        return new MerchantCombinedProductOfferStoreWriterStep($this->getEventFacade());
    }

    public function createMerchantCombinedProductOfferStockWriterStep(): DataImportStepInterface
    {
        return new MerchantCombinedProductOfferStockWriterStep($this->getEventFacade());
    }

    public function createMerchantCombinedProductOfferValidityWriterStep(): DataImportStepInterface
    {
        return new MerchantCombinedProductOfferValidityWriterStep($this->getEventFacade());
    }

    public function createMerchantCombinedProductOfferAddMerchantReferenceStep(): DataImportStepInterface
    {
        return new MerchantCombinedProductOfferAddMerchantReferenceStep();
    }

    public function createMerchantCombinedProductOfferAddMerchantStocksStep(): DataImportStepInterface
    {
        return new MerchantCombinedProductOfferAddMerchantStocksStep();
    }

    public function createMerchantCombinedProductOfferAccessValidationStep(): DataImportStepInterface
    {
        return new MerchantCombinedProductOfferAccessValidationStep();
    }

    public function createMerchantCombinedProductOfferPriceExtractorStep(): DataImportStepInterface
    {
        return new MerchantCombinedProductOfferPriceExtractorStep();
    }

    public function createMerchantCombinedProductOfferPriceWriterStep(): DataImportStepInterface
    {
        return new MerchantCombinedProductOfferPriceWriterStep();
    }

    public function createMerchantCombinedProductOfferAddCurrenciesStep(): DataImportStepInterface
    {
        return new MerchantCombinedProductOfferAddCurrenciesStep();
    }

    public function createMerchantCombinedProductOfferAddPriceTypeStep(): DataImportStepInterface
    {
        return new MerchantCombinedProductOfferAddPriceTypeStep();
    }

    public function createMerchantCombinedProductOfferStockExtractorStep(): DataImportStepInterface
    {
        return new MerchantCombinedProductOfferStockExtractorStep();
    }

    public function createMerchantCombinedMerchantProductOfferRequestExpander(): MerchantCombinedMerchantProductOfferRequestExpanderInterface
    {
        return new MerchantCombinedMerchantProductOfferRequestExpander(
            $this->getConfig(),
        );
    }

    public function getCsvDataImporterContextAwareFromConfig(
        DataImporterConfigurationTransfer $dataImporterConfigurationTransfer
    ): DataImporterInterface {
        $csvReader = $this->createCsvAdapterReaderFromConfig($dataImporterConfigurationTransfer->getReaderConfigurationOrFail());

        return $this->createDataImporter($dataImporterConfigurationTransfer->getImportTypeOrFail(), $csvReader);
    }

    public function createCsvAdapterReaderFromConfig(
        DataImporterReaderConfigurationTransfer $dataImporterReaderConfigurationTransfer
    ): DataReaderInterface {
        $csvAdapterReaderConfiguration = new CsvAdapterReaderConfiguration(
            $dataImporterReaderConfigurationTransfer,
            $this->createFileResolver(),
            $this->getConfig(),
        );

        return $this->createCsvAdapterReader($csvAdapterReaderConfiguration);
    }
}
