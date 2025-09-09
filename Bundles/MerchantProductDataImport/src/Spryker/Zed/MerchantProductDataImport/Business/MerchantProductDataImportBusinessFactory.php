<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use InvalidArgumentException;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\DataImporter\DataImporterDataSetIdentifierAwareInterface;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepository;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\ProductImageRepository;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\ProductImageRepositoryInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\AddAttributeKeysStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\AddCurrenciesStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\AddMerchantIdKeyStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\AddMerchantStockStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\AddPriceTypesStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\AddProductCategoryKeysStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\AddTaxSetIdStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\AssertAssignedProductTypeStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\AssertRequiredProductAbstractDataStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\AssertRequiredProductDataStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\AttributeExtractorStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\CategoryExtractorStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\CombinedMerchantProductAbstractWriterStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\DefineIsNewProductStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\LocalizedUrlExtractorStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\MerchantCombinedProductAbstractHydratorStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\MerchantCombinedProductAbstractStoreHydratorStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\MerchantCombinedProductConcreteHydratorStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\MerchantCombinedProductStockExtractorStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\MerchantCombinedProductStockHydratorStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\MerchantCombinedProductStockWriterStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\PriceProductHydratorStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\PriceProductWriterStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\ProductAbstractMerchantOwnerCheckStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\ProductAbstractStoreWriterStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\ProductAbstractWriterStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\ProductImageHydratorStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\ProductImageWriterStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\ProductLocalizedAttributeExtractorStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\ProductMerchantOwnerCheckStep;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step\ProductWriterStep;
use Spryker\Zed\MerchantProductDataImport\Business\DataReader\CsvAdapterReaderConfiguration;
use Spryker\Zed\MerchantProductDataImport\Business\Expander\MerchantCombinedProductRequestExpander;
use Spryker\Zed\MerchantProductDataImport\Business\Expander\MerchantCombinedProductRequestExpanderInterface;
use Spryker\Zed\MerchantProductDataImport\Business\Expander\PossibleCsvHeaderExpander;
use Spryker\Zed\MerchantProductDataImport\Business\Expander\PossibleCsvHeaderExpanderInterface;
use Spryker\Zed\MerchantProductDataImport\Business\MerchantProduct\Step\MerchantProductAbstractWriterStep;
use Spryker\Zed\MerchantProductDataImport\Business\MerchantProduct\Step\MerchantReferenceToIdMerchantStep;
use Spryker\Zed\MerchantProductDataImport\Business\MerchantProduct\Step\ProductAbstractSkuToIdProductAbstractStep;
use Spryker\Zed\MerchantProductDataImport\Business\Validator\MerchantCombinedProductValidator;
use Spryker\Zed\MerchantProductDataImport\Business\Validator\MerchantCombinedProductValidatorInterface;
use Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToCurrencyFacadeInterface;
use Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToLocaleFacadeInterface;
use Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToMerchantFacadeInterface;
use Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToMerchantStockFacadeInterface;
use Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToProductAttributeFacadeInterface;
use Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToStoreFacadeInterface;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig getConfig()
 */
class MerchantProductDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getMerchantProductDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getMerchantProductDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createMerchantReferenceToIdMerchantStep())
            ->addStep($this->createProductAbstractSkuToIdProductAbstractStep())
            ->addStep($this->createMerchantProductAbstractWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @throws \InvalidArgumentException
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createMerchantCombinedProductImporter(
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
                $this->getConfig()->getCombinedMerchantProductRowIdentifier(),
            );
        }

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createAssertAssignedProductTypeStep())
            ->addStep($this->createAssertRequiredProductAbstractStep())
            ->addStep($this->createAssertRequiredProductStep())
            ->addStep($this->createAddMerchantIdStep())
            ->addStep($this->createProductAbstractMerchantOwnerCheckStep())
            ->addStep($this->createProductMerchantOwnerCheckStep())
            ->addStep($this->createDefineProductExistenceStep())
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createAddStoresStep())
            ->addStep($this->createAddCurrenciesStep())
            ->addStep($this->createAddPriceTypesStep())
            ->addStep($this->createAddMerchantCombinedProductCategoryKeysStep())
            ->addStep($this->createAddMerchantStockStep())
            ->addStep($this->createAddAttributeKeysStep())
            ->addStep($this->createCategoryExtractorStep())
            ->addStep($this->createMerchantCombinedProductStockExtractorStep())
            ->addStep($this->createAddTaxSetIdStep())
            ->addStep($this->createProductLocalizedAttributeExtractorStep())
            ->addStep($this->createAttributeExtractorStep())
            ->addStep($this->createLocalizedUrlExtractorStep())
            ->addStep($this->createMerchantCombinedProductAbstractHydratorStep())
            ->addStep($this->createMerchantCombinedProductAbstractStoreHydratorStep())
            ->addStep($this->createMerchantCombinedProductConcreteHydratorStep())
            ->addStep($this->createMerchantCombinedProductStockHydratorStep())
            ->addStep($this->createProductAbstractWriterStep())
            ->addStep($this->createProductAbstractStoreWriterStep())
            ->addStep($this->createProductWriterStep())
            ->addStep($this->createMerchantCombinedProductStockWriterStep())
            ->addStep($this->createPriceProductHydratorStep())
            ->addStep($this->createPriceProductWriterStep())
            ->addStep($this->createCombinedMerchantProductAbstractWriterStep())
            ->addStep($this->createProductImageHydratorStep())
            ->addStep($this->createProductImageWriterStep());

        if ($dataImporter instanceof DataSetStepBrokerAwareInterface) {
            $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        }

        return $dataImporter;
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
    public function createMerchantProductAbstractWriterStep(): DataImportStepInterface
    {
        return new MerchantProductAbstractWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductAbstractSkuToIdProductAbstractStep(): DataImportStepInterface
    {
        return new ProductAbstractSkuToIdProductAbstractStep();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getCsvDataImporterContextAwareFromConfig(
        DataImporterConfigurationTransfer $dataImporterConfigurationTransfer
    ): DataImporterInterface {
        $csvReader = $this->createCsvAdapterReaderFromConfig($dataImporterConfigurationTransfer->getReaderConfigurationOrFail());

        return $this->createDataImporter($dataImporterConfigurationTransfer->getImportTypeOrFail(), $csvReader);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer $dataImporterReaderConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface
     */
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

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAddMerchantIdStep(): DataImportStepInterface
    {
        return new AddMerchantIdKeyStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAssertAssignedProductTypeStep(): DataImportStepInterface
    {
        return new AssertAssignedProductTypeStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAssertRequiredProductAbstractStep(): DataImportStepInterface
    {
        return new AssertRequiredProductAbstractDataStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAssertRequiredProductStep(): DataImportStepInterface
    {
        return new AssertRequiredProductDataStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductAbstractMerchantOwnerCheckStep(): DataImportStepInterface
    {
        return new ProductAbstractMerchantOwnerCheckStep($this->createMerchantCombinedProductRepository());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductMerchantOwnerCheckStep(): DataImportStepInterface
    {
        return new ProductMerchantOwnerCheckStep($this->createMerchantCombinedProductRepository());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAddMerchantCombinedProductCategoryKeysStep(): DataImportStepInterface
    {
        return new AddProductCategoryKeysStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAddMerchantStockStep(): DataImportStepInterface
    {
        return new AddMerchantStockStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAddAttributeKeysStep(): DataImportStepInterface
    {
        return new AddAttributeKeysStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCategoryExtractorStep(): DataImportStepInterface
    {
        return new CategoryExtractorStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantCombinedProductStockExtractorStep(): DataImportStepInterface
    {
        return new MerchantCombinedProductStockExtractorStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAddTaxSetIdStep(): DataImportStepInterface
    {
        return new AddTaxSetIdStep($this->createMerchantCombinedProductRepository());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductLocalizedAttributeExtractorStep(): DataImportStepInterface
    {
        return new ProductLocalizedAttributeExtractorStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAttributeExtractorStep(): DataImportStepInterface
    {
        return new AttributeExtractorStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createLocalizedUrlExtractorStep(): DataImportStepInterface
    {
        return new LocalizedUrlExtractorStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantCombinedProductAbstractHydratorStep(): DataImportStepInterface
    {
        return new MerchantCombinedProductAbstractHydratorStep($this->createMerchantCombinedProductRepository());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantCombinedProductAbstractStoreHydratorStep(): DataImportStepInterface
    {
        return new MerchantCombinedProductAbstractStoreHydratorStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantCombinedProductConcreteHydratorStep(): DataImportStepInterface
    {
        return new MerchantCombinedProductConcreteHydratorStep($this->createMerchantCombinedProductRepository());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantCombinedProductStockHydratorStep(): DataImportStepInterface
    {
        return new MerchantCombinedProductStockHydratorStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductAbstractWriterStep(): DataImportStepInterface
    {
        return new ProductAbstractWriterStep($this->createMerchantCombinedProductRepository());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductWriterStep(): DataImportStepInterface
    {
        return new ProductWriterStep($this->createMerchantCombinedProductRepository());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductAbstractStoreWriterStep(): DataImportStepInterface
    {
        return new ProductAbstractStoreWriterStep($this->createMerchantCombinedProductRepository());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantCombinedProductStockWriterStep(): DataImportStepInterface
    {
        return new MerchantCombinedProductStockWriterStep($this->createMerchantCombinedProductRepository());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAddCurrenciesStep(): DataImportStepInterface
    {
        return new AddCurrenciesStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAddPriceTypesStep(): DataImportStepInterface
    {
        return new AddPriceTypesStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createPriceProductHydratorStep(): DataImportStepInterface
    {
        return new PriceProductHydratorStep($this->createMerchantCombinedProductRepository());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createPriceProductWriterStep(): DataImportStepInterface
    {
        return new PriceProductWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCombinedMerchantProductAbstractWriterStep(): DataImportStepInterface
    {
        return new CombinedMerchantProductAbstractWriterStep($this->createMerchantCombinedProductRepository());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductImageHydratorStep(): DataImportStepInterface
    {
        return new ProductImageHydratorStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductImageWriterStep(): DataImportStepInterface
    {
        return new ProductImageWriterStep(
            $this->createMerchantCombinedProductRepository(),
            $this->createProductImageRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createDefineProductExistenceStep(): DataImportStepInterface
    {
        return new DefineIsNewProductStep($this->createMerchantCombinedProductRepository());
    }

    /**
     * @return \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\ProductImageRepositoryInterface
     */
    public function createProductImageRepository(): ProductImageRepositoryInterface
    {
        return new ProductImageRepository();
    }

    /**
     * @return \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface
     */
    public function createMerchantCombinedProductRepository(): MerchantCombinedProductRepositoryInterface
    {
        return new MerchantCombinedProductRepository();
    }

    /**
     * @return \Spryker\Zed\MerchantProductDataImport\Business\Validator\MerchantCombinedProductValidatorInterface
     */
    public function createMerchantCombinedProductValidator(): MerchantCombinedProductValidatorInterface
    {
        return new MerchantCombinedProductValidator();
    }

    /**
     * @return \Spryker\Zed\MerchantProductDataImport\Business\Expander\MerchantCombinedProductRequestExpanderInterface
     */
    public function createMerchantCombinedProductRequestExpander(): MerchantCombinedProductRequestExpanderInterface
    {
        return new MerchantCombinedProductRequestExpander(
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProductDataImport\Business\Expander\PossibleCsvHeaderExpanderInterface
     */
    public function createPossibleCsvHeaderExpander(): PossibleCsvHeaderExpanderInterface
    {
        return new PossibleCsvHeaderExpander(
            $this->getConfig(),
            $this->getLocaleFacade(),
            $this->getMerchantFacade(),
            $this->getMerchantStockFacade(),
            $this->getStoreFacade(),
            $this->getCurrencyFacade(),
            $this->getProductAttributeFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToLocaleFacadeInterface
     */
    public function getLocaleFacade(): MerchantProductDataImportToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductDataImportDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantProductDataImportToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductDataImportDependencyProvider::FACADE_MERCHANT);
    }

    /**
     * @return \Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToMerchantStockFacadeInterface
     */
    public function getMerchantStockFacade(): MerchantProductDataImportToMerchantStockFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductDataImportDependencyProvider::FACADE_MERCHANT_STOCK);
    }

    /**
     * @return \Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToStoreFacadeInterface
     */
    public function getStoreFacade(): MerchantProductDataImportToStoreFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductDataImportDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): MerchantProductDataImportToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductDataImportDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToProductAttributeFacadeInterface
     */
    public function getProductAttributeFacade(): MerchantProductDataImportToProductAttributeFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductDataImportDependencyProvider::FACADE_PRODUCT_ATTRIBUTE);
    }
}
