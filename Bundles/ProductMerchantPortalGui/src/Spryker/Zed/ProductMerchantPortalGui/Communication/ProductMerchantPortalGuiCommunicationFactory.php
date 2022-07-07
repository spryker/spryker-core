<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface;
use Spryker\Shared\ZedUi\ZedUiFactoryInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Creator\PriceProductTableColumnCreator;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Creator\PriceProductTableColumnCreatorInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\AttributesDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\AttributesDataProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\LocaleDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\LocaleDataProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\ProductAttributeDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\SuperAttributesDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\SuperAttributesDataProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Deleter\PriceDeleter;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Deleter\PriceDeleterInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Expander\MerchantDataExpander;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Expander\MerchantDataExpanderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Expander\ProductAbstractLocalizedAttributesExpander;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Expander\ProductAbstractLocalizedAttributesExpanderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Expander\ProductConcreteLocalizedAttributesExpander;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Expander\ProductConcreteLocalizedAttributesExpanderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Expander\ProductStockExpander;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Expander\ProductStockExpanderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Extractor\LocalizedAttributesExtractor;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Extractor\LocalizedAttributesExtractorInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\AddProductConcreteForm;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ProductAbstractAttributeUniqueCombinationConstraint;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ProductAttributesNotBlankConstraint;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ProductConcreteOwnedByMerchantConstraint;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractForm;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractWithMultiConcreteForm;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractWithSingleConcreteForm;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\CreateProductAbstractWithSingleConcreteFormDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\CreateProductAbstractWithSingleConcreteFormDataProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\ProductAbstractFormDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\ProductAbstractFormDataProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\ProductConcreteEditFormDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\ProductConcreteEditFormDataProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductAbstractForm;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteBulkForm;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Transformer\EmptyStringTransformer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Transformer\LocaleTransformer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Transformer\PriceProductTransformer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Transformer\ProductAttributeTransformer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Transformer\ProductConcreteEditFormDataTransformer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Transformer\QuantityTransformer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Transformer\StockTransformer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Generator\CreateProductUrlGenerator;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Generator\CreateProductUrlGeneratorInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\CategoryFilterOptionsProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\CategoryFilterOptionsProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ConfigurationBuilderProvider\PriceProductGuiTableConfigurationBuilderProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ConfigurationBuilderProvider\PriceProductGuiTableConfigurationBuilderProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\CurrencyFilterConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\CurrencyFilterConfigurationProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductAbstractGuiTableConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductAbstractGuiTableConfigurationProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductConcreteGuiTableConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductConcreteGuiTableConfigurationProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractAttributeGuiTableConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractAttributeGuiTableConfigurationProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractGuiTableConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractGuiTableConfigurationProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAttributeGuiTableConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAttributeGuiTableConfigurationProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductConcreteAttributeGuiTableConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductConcreteAttributeGuiTableConfigurationProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\StoreFilterOptionsProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\StoreFilterOptionsProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider\PriceProductAbstractTableDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider\PriceProductConcreteTableDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider\ProductAbstractAttributeTableDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider\ProductAbstractTableDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider\ProductConcreteAttributeTableDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider\ProductTableDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Expander\ProductApprovalStatusProductTableConfigurationExpander;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Expander\ProductApprovalStatusProductTableConfigurationExpanderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Expander\ProductApprovalStatusProductTableDataResponseExpander;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Expander\ProductApprovalStatusProductTableDataResponseExpanderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter\ComparisonStrategy\DefaultFieldSortingComparisonStrategy;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter\ComparisonStrategy\PriceFieldSortingComparisonStrategy;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter\ComparisonStrategy\PriceProductSortingComparisonStrategyInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter\PriceProductTableViewSorter;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter\PriceProductTableViewSorterInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\FieldStrategy\CurrencyAndStoreFieldMapperStrategy;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\FieldStrategy\FieldMapperStrategyInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\FieldStrategy\PriceFieldMapperStrategy;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\FieldStrategy\VolumeQuantityFieldMapperStrategy;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\FormErrorsMapper;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\FormErrorsMapperInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ImageSetMapper;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ImageSetMapperInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\MergeStrategy\PriceProductAlreadyAddedMergeStrategy;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\MergeStrategy\PriceProductMergeStrategyInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\MergeStrategy\VolumePriceForExistingPriceProductMergeStrategy;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\MergeStrategy\VolumePriceForNonExistingPriceProductMergeStrategy;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\PriceProductMerger;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\PriceProductMergerInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductMapper;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductMapperInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductTableDataMapper;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductTableDataMapperInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductValidationMapper;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductValidationMapperInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductAttributesMapper;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductAttributesMapperInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductConcreteMapper;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductConcreteMapperInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductFormTransferMapper;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductFormTransferMapperInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\SingleFieldPriceProductMapper;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\SingleFieldPriceProductMapperInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Matcher\PriceProductTableRowMatcher;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Matcher\PriceProductTableRowMatcherInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Reader\ApplicableApprovalStatusReader;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Reader\ApplicableApprovalStatusReaderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Reader\PriceProductReader;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Reader\PriceProductReaderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Validator\ProductConcreteValidator;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Validator\ProductConcreteValidatorInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\External\ProductMerchantPortalGuiToValidationAdapterInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCategoryFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantStockFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToOmsFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductVolumeFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductApprovalFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductCategoryFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductValidityFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductServiceInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductVolumeServiceInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface;
use Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiDependencyProvider;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig getConfig()
 */
class ProductMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractGuiTableConfigurationProviderInterface
     */
    public function createProductAbstractGuiTableConfigurationProvider(): ProductAbstractGuiTableConfigurationProviderInterface
    {
        return new ProductAbstractGuiTableConfigurationProvider(
            $this->getGuiTableFactory(),
            $this->createCategoryFilterOptionsProvider(),
            $this->createStoreFilterOptionsProvider(),
            $this->getTranslatorFacade(),
        );
    }

    /**
     * @return \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface
     */
    public function createProductAbstractTableDataProvider(): GuiTableDataProviderInterface
    {
        return new ProductAbstractTableDataProvider(
            $this->getRepository(),
            $this->getLocaleFacade(),
            $this->getMerchantUserFacade(),
            $this->getTranslatorFacade(),
            $this->createLocalizedAttributesExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\CategoryFilterOptionsProviderInterface
     */
    public function createCategoryFilterOptionsProvider(): CategoryFilterOptionsProviderInterface
    {
        return new CategoryFilterOptionsProvider(
            $this->getCategoryFacade(),
            $this->getLocaleFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer|null $data
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface<mixed>
     */
    public function createProductAbstractForm(?ProductAbstractTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ProductAbstractForm::class, $data, $options);
    }

    /**
     * @param array<mixed>|null $data
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface<mixed>
     */
    public function createCreateProductAbstractForm(?array $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(CreateProductAbstractForm::class, $data, $options);
    }

    /**
     * @param array<mixed>|null $data
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface<mixed>
     */
    public function createCreateProductAbstractWithSingleConcreteForm(?array $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(CreateProductAbstractWithSingleConcreteForm::class, $data, $options);
    }

    /**
     * @param array<mixed>|null $data
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface<mixed>
     */
    public function createCreateProductAbstractWithMultiConcreteForm(?array $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(CreateProductAbstractWithMultiConcreteForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\ProductAbstractFormDataProviderInterface
     */
    public function createProductAbstractFormDataProvider(): ProductAbstractFormDataProviderInterface
    {
        return new ProductAbstractFormDataProvider(
            $this->getMerchantProductFacade(),
            $this->getStoreFacade(),
            $this->getCategoryFacade(),
            $this->getLocaleFacade(),
            $this->getProductCategoryFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\CreateProductAbstractWithSingleConcreteFormDataProviderInterface
     */
    public function createCreateProductAbstractWithSingleConcreteFormDataProvider(): CreateProductAbstractWithSingleConcreteFormDataProviderInterface
    {
        return new CreateProductAbstractWithSingleConcreteFormDataProvider();
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductAbstractGuiTableConfigurationProviderInterface
     */
    public function createPriceProductAbstractGuiTableConfigurationProvider(): PriceProductAbstractGuiTableConfigurationProviderInterface
    {
        return new PriceProductAbstractGuiTableConfigurationProvider(
            $this->createPriceProductGuiTableConfigurationBuilderProvider(),
            $this->getPriceProductAbstractTableConfigurationExpanderPlugins(),
        );
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface
     */
    public function createPriceProductAbstractTableDataProvider(int $idProductAbstract): GuiTableDataProviderInterface
    {
        return new PriceProductAbstractTableDataProvider(
            $idProductAbstract,
            $this->createPriceProductReader(),
            $this->createPriceProductTableDataMapper(),
            $this->createPriceProductTableViewSorter(),
            $this->getMerchantUserFacade(),
            $this->getMoneyFacade(),
        );
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface
     */
    public function createProductAbstractAttributesTableDataProvider(int $idProductAbstract): GuiTableDataProviderInterface
    {
        return new ProductAbstractAttributeTableDataProvider(
            $this->getProductFacade(),
            $idProductAbstract,
        );
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface
     */
    public function createProductConcreteAttributesTableDataProvider(int $idProductConcrete): GuiTableDataProviderInterface
    {
        return new ProductConcreteAttributeTableDataProvider(
            $this->getProductFacade(),
            $this->getLocaleFacade(),
            $this->createLocalizedAttributesExtractor(),
            $idProductConcrete,
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\ProductAttributeDataProvider
     */
    public function createProductAttributeDataProvider(): ProductAttributeDataProvider
    {
        return new ProductAttributeDataProvider();
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface
     */
    public function createPriceProductConcreteTableDataProvider(int $idProductConcrete): GuiTableDataProviderInterface
    {
        return new PriceProductConcreteTableDataProvider(
            $idProductConcrete,
            $this->createPriceProductReader(),
            $this->createPriceProductTableDataMapper(),
            $this->createPriceProductTableViewSorter(),
            $this->getMerchantUserFacade(),
            $this->getMoneyFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\StoreFilterOptionsProviderInterface
     */
    public function createStoreFilterOptionsProvider(): StoreFilterOptionsProviderInterface
    {
        return new StoreFilterOptionsProvider($this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\CurrencyFilterConfigurationProviderInterface
     */
    public function createCurrencyFilterConfigurationProvider(): CurrencyFilterConfigurationProviderInterface
    {
        return new CurrencyFilterConfigurationProvider($this->getCurrencyFacade());
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Transformer\PriceProductTransformer
     */
    public function createPriceProductTransformer(): DataTransformerInterface
    {
        return new PriceProductTransformer(
            $this->getPriceProductFacade(),
            $this->createPriceProductMapper(),
            $this->createPriceProductTableDataMapper(),
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createAttributeProductTransformer(): DataTransformerInterface
    {
        return new ProductAttributeTransformer(
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createStockTransformer(): DataTransformerInterface
    {
        return new StockTransformer();
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createQuantityTransformer(): DataTransformerInterface
    {
        return new QuantityTransformer();
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createEmptyStringTransformer(): DataTransformerInterface
    {
        return new EmptyStringTransformer();
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\FormErrorsMapperInterface
     */
    public function createFormErrorsMapper(): FormErrorsMapperInterface
    {
        return new FormErrorsMapper();
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createProductConcreteEditFormDataTransformer(): DataTransformerInterface
    {
        return new ProductConcreteEditFormDataTransformer();
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductMapperInterface
     */
    public function createPriceProductMapper(): PriceProductMapperInterface
    {
        return new PriceProductMapper(
            $this->getPriceProductFacade(),
            $this->getCurrencyFacade(),
            $this->getMoneyFacade(),
            $this->createPriceProductMerger(),
            $this->getUtilEncodingService(),
            $this->getPriceProductMapperPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\PriceProductMergerInterface
     */
    public function createPriceProductMerger(): PriceProductMergerInterface
    {
        return new PriceProductMerger(
            $this->getPriceProductMergeStrategies(),
        );
    }

    /**
     * @return array<\Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\MergeStrategy\PriceProductMergeStrategyInterface>
     */
    public function getPriceProductMergeStrategies(): array
    {
        return [
            $this->createPriceProductAlreadyAddedMergeStrategy(),
            $this->createVolumePriceForExistingPriceProductMergeStrategy(),
            $this->createVolumePriceForNonExistingPriceProductMergeStrategy(),
        ];
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\MergeStrategy\PriceProductMergeStrategyInterface
     */
    public function createVolumePriceForNonExistingPriceProductMergeStrategy(): PriceProductMergeStrategyInterface
    {
        return new VolumePriceForNonExistingPriceProductMergeStrategy(
            $this->getPriceProductVolumeService(),
            $this->getPriceProductService(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\MergeStrategy\PriceProductMergeStrategyInterface
     */
    public function createVolumePriceForExistingPriceProductMergeStrategy(): PriceProductMergeStrategyInterface
    {
        return new VolumePriceForExistingPriceProductMergeStrategy(
            $this->getPriceProductVolumeService(),
            $this->getPriceProductService(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\MergeStrategy\PriceProductMergeStrategyInterface
     */
    public function createPriceProductAlreadyAddedMergeStrategy(): PriceProductMergeStrategyInterface
    {
        return new PriceProductAlreadyAddedMergeStrategy(
            $this->getPriceProductService(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductTableDataMapperInterface
     */
    public function createPriceProductTableDataMapper(): PriceProductTableDataMapperInterface
    {
        return new PriceProductTableDataMapper(
            $this->getPriceProductFacade(),
            $this->getStoreFacade(),
            $this->getUtilEncodingService(),
            $this->getPriceProductService(),
            $this->createPriceProductMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Reader\PriceProductReaderInterface
     */
    public function createPriceProductReader(): PriceProductReaderInterface
    {
        return new PriceProductReader(
            $this->getPriceProductFacade(),
            $this->getProductFacade(),
            $this->getPriceProductVolumeFacade(),
            $this->getPriceProductTableFilterPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Deleter\PriceDeleterInterface
     */
    public function createPriceDeleter(): PriceDeleterInterface
    {
        return new PriceDeleter(
            $this->getPriceProductFacade(),
            $this->getPriceProductVolumeService(),
            $this->createPriceProductMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter\PriceProductTableViewSorterInterface
     */
    public function createPriceProductTableViewSorter(): PriceProductTableViewSorterInterface
    {
        return new PriceProductTableViewSorter(
            [
                $this->createPriceFieldSortingComparisonStrategy(),
            ],
            $this->createDefaultFieldSortingComparisonStrategy(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter\ComparisonStrategy\PriceProductSortingComparisonStrategyInterface
     */
    public function createDefaultFieldSortingComparisonStrategy(): PriceProductSortingComparisonStrategyInterface
    {
        return new DefaultFieldSortingComparisonStrategy();
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter\ComparisonStrategy\PriceProductSortingComparisonStrategyInterface
     */
    public function createPriceFieldSortingComparisonStrategy(): PriceProductSortingComparisonStrategyInterface
    {
        return new PriceFieldSortingComparisonStrategy();
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Creator\PriceProductTableColumnCreatorInterface
     */
    public function createPriceProductTableColumnCreator(): PriceProductTableColumnCreatorInterface
    {
        return new PriceProductTableColumnCreator();
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductValidationMapperInterface
     */
    public function createPriceProductValidationMapper(): PriceProductValidationMapperInterface
    {
        return new PriceProductValidationMapper(
            $this->createPriceProductTableColumnCreator(),
            $this->createPriceProductTableRowMatcher(),
            $this->getTranslatorFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Matcher\PriceProductTableRowMatcherInterface
     */
    public function createPriceProductTableRowMatcher(): PriceProductTableRowMatcherInterface
    {
        return new PriceProductTableRowMatcher(
            $this->createPriceProductTableColumnCreator(),
            $this->getPriceProductVolumeFacade(),
            $this->getMoneyFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductConcreteMapperInterface
     */
    public function createProductConcreteMapper(): ProductConcreteMapperInterface
    {
        return new ProductConcreteMapper();
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Expander\ProductAbstractLocalizedAttributesExpanderInterface
     */
    public function createProductAbstractLocalizedAttributesExpander(): ProductAbstractLocalizedAttributesExpanderInterface
    {
        return new ProductAbstractLocalizedAttributesExpander(
            $this->getLocaleFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Expander\ProductConcreteLocalizedAttributesExpanderInterface
     */
    public function createProductConcreteLocalizedAttributesExpander(): ProductConcreteLocalizedAttributesExpanderInterface
    {
        return new ProductConcreteLocalizedAttributesExpander(
            $this->getLocaleFacade(),
            $this->getProductAttributeFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Expander\MerchantDataExpanderInterface
     */
    public function createMerchantDataExpander(): MerchantDataExpanderInterface
    {
        return new MerchantDataExpander(
            $this->getMerchantUserFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\SingleFieldPriceProductMapperInterface
     */
    public function createSingleFieldPriceProductMapper(): SingleFieldPriceProductMapperInterface
    {
        return new SingleFieldPriceProductMapper(
            $this->getFieldMapperStrategies(),
            $this->createPriceProductMapper(),
        );
    }

    /**
     * @return array<\Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\FieldStrategy\FieldMapperStrategyInterface>
     */
    public function getFieldMapperStrategies(): array
    {
        return [
            $this->createCurrencyAndStoreFieldMapperStrategy(),
            $this->createPriceFieldMapperStrategy(),
            $this->createVolumeQuantityFieldMapperStrategy(),
        ];
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\FieldStrategy\FieldMapperStrategyInterface
     */
    public function createCurrencyAndStoreFieldMapperStrategy(): FieldMapperStrategyInterface
    {
        return new CurrencyAndStoreFieldMapperStrategy(
            $this->getPriceProductFacade(),
            $this->getPriceProductVolumeService(),
            $this->getCurrencyFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\FieldStrategy\FieldMapperStrategyInterface
     */
    public function createPriceFieldMapperStrategy(): FieldMapperStrategyInterface
    {
        return new PriceFieldMapperStrategy(
            $this->getPriceProductFacade(),
            $this->getPriceProductVolumeService(),
            $this->getMoneyFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\FieldStrategy\FieldMapperStrategyInterface
     */
    public function createVolumeQuantityFieldMapperStrategy(): FieldMapperStrategyInterface
    {
        return new VolumeQuantityFieldMapperStrategy(
            $this->getPriceProductFacade(),
            $this->getPriceProductVolumeService(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAttributeGuiTableConfigurationProviderInterface
     */
    public function createProductAttributeGuiTableConfigurationProvider(): ProductAttributeGuiTableConfigurationProviderInterface
    {
        return new ProductAttributeGuiTableConfigurationProvider(
            $this->getGuiTableFactory(),
            $this->createProductAttributeDataProvider(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractAttributeGuiTableConfigurationProviderInterface
     */
    public function createProductAbstractAttributeGuiTableConfigurationProvider(): ProductAbstractAttributeGuiTableConfigurationProviderInterface
    {
        return new ProductAbstractAttributeGuiTableConfigurationProvider(
            $this->getGuiTableFactory(),
            $this->getProductAttributeFacade(),
            $this->getProductFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductConcreteGuiTableConfigurationProviderInterface
     */
    public function createPriceProductConcreteGuiTableConfigurationProvider(): PriceProductConcreteGuiTableConfigurationProviderInterface
    {
        return new PriceProductConcreteGuiTableConfigurationProvider(
            $this->createPriceProductGuiTableConfigurationBuilderProvider(),
            $this->getPriceProductConcreteTableConfigurationExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ConfigurationBuilderProvider\PriceProductGuiTableConfigurationBuilderProviderInterface
     */
    public function createPriceProductGuiTableConfigurationBuilderProvider(): PriceProductGuiTableConfigurationBuilderProviderInterface
    {
        return new PriceProductGuiTableConfigurationBuilderProvider(
            $this->getGuiTableFactory(),
            $this->getPriceProductFacade(),
            $this->createStoreFilterOptionsProvider(),
            $this->createCurrencyFilterConfigurationProvider(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductConcreteAttributeGuiTableConfigurationProviderInterface
     */
    public function createProductConcreteAttributeGuiTableConfigurationProvider(): ProductConcreteAttributeGuiTableConfigurationProviderInterface
    {
        return new ProductConcreteAttributeGuiTableConfigurationProvider(
            $this->getGuiTableFactory(),
            $this->getProductAttributeFacade(),
            $this->getProductFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProviderInterface
     */
    public function createProductGuiTableConfigurationProvider(): ProductGuiTableConfigurationProviderInterface
    {
        return new ProductGuiTableConfigurationProvider(
            $this->getGuiTableFactory(),
            $this->getTranslatorFacade(),
            $this->getProductConcreteTableExpanderPlugins(),
        );
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface
     */
    public function createProductTableDataProvider(int $idProductAbstract): GuiTableDataProviderInterface
    {
        return new ProductTableDataProvider(
            $idProductAbstract,
            $this->getRepository(),
            $this->getLocaleFacade(),
            $this->getMerchantUserFacade(),
            $this->getTranslatorFacade(),
            $this->createLocalizedAttributesExtractor(),
            $this->getProductConcreteTableExpanderPlugins(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer|null $data
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface<mixed>
     */
    public function createProductConcreteBulkForm(?ProductConcreteTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ProductConcreteBulkForm::class, $data, $options);
    }

    /**
     * @param array<mixed>|null $data
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface<mixed>
     */
    public function createProductConcreteEditForm(?array $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ProductConcreteEditForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\ProductConcreteEditFormDataProviderInterface
     */
    public function createProductConcreteEditFormDataProvider(): ProductConcreteEditFormDataProviderInterface
    {
        return new ProductConcreteEditFormDataProvider(
            $this->getMerchantUserFacade(),
            $this->getMerchantProductFacade(),
            $this->getLocaleFacade(),
            $this->getProductFacade(),
            $this->createProductAttributeDataProvider(),
            $this->createPriceProductReader(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Extractor\LocalizedAttributesExtractorInterface
     */
    public function createLocalizedAttributesExtractor(): LocalizedAttributesExtractorInterface
    {
        return new LocalizedAttributesExtractor($this->getProductAttributeFacade());
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createProductConcreteOwnedByMerchantConstraint(): Constraint
    {
        return new ProductConcreteOwnedByMerchantConstraint(
            $this->getMerchantUserFacade(),
            $this->getMerchantProductFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\SuperAttributesDataProviderInterface
     */
    public function createSuperAttributesDataProvider(): SuperAttributesDataProviderInterface
    {
        return new SuperAttributesDataProvider(
            $this->getProductAttributeFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Validator\ProductConcreteValidatorInterface
     */
    public function createProductConcreteValidator(): ProductConcreteValidatorInterface
    {
        return new ProductConcreteValidator(
            $this->getValidationAdapter(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Generator\CreateProductUrlGeneratorInterface
     */
    public function createCreateProductUrlGenerator(): CreateProductUrlGeneratorInterface
    {
        return new CreateProductUrlGenerator();
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\LocaleDataProviderInterface
     */
    public function createLocaleDataProvider(): LocaleDataProviderInterface
    {
        return new LocaleDataProvider(
            $this->getLocaleFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\AttributesDataProviderInterface
     */
    public function createAttributesDataProvider(): AttributesDataProviderInterface
    {
        return new AttributesDataProvider($this->createLocalizedAttributesExtractor());
    }

    /**
     * @param array<mixed>|null $data
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface<mixed>
     */
    public function createAddProductConcreteForm(?array $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(AddProductConcreteForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductFormTransferMapperInterface
     */
    public function createProductFormTransferMapper(): ProductFormTransferMapperInterface
    {
        return new ProductFormTransferMapper($this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Expander\ProductStockExpanderInterface
     */
    public function createProductStockExpander(): ProductStockExpanderInterface
    {
        return new ProductStockExpander(
            $this->getMerchantStockFacade(),
            $this->getMerchantUserFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Reader\ApplicableApprovalStatusReaderInterface
     */
    public function createApplicableApprovalStatusReader(): ApplicableApprovalStatusReaderInterface
    {
        return new ApplicableApprovalStatusReader($this->getConfig());
    }

    /**
     * @return array<int, \Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\PriceProductMapperPluginInterface>
     */
    public function getPriceProductMapperPlugins(): array
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::PLUGINS_PRICE_PRODUCT_MAPPER);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductMerchantPortalGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): ProductMerchantPortalGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): ProductMerchantPortalGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductMerchantPortalGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface
     */
    public function getGuiTableHttpDataRequestExecutor(): GuiTableDataRequestExecutorInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR);
    }

    /**
     * @return \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    public function getGuiTableFactory(): GuiTableFactoryInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::SERVICE_GUI_TABLE_FACTORY);
    }

    /**
     * @return \Spryker\Shared\ZedUi\ZedUiFactoryInterface
     */
    public function getZedUiFactory(): ZedUiFactoryInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::SERVICE_ZED_UI_FACTORY);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCategoryFacadeInterface
     */
    public function getCategoryFacade(): ProductMerchantPortalGuiToCategoryFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface
     */
    public function getMerchantProductFacade(): ProductMerchantPortalGuiToMerchantProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductCategoryFacadeInterface
     */
    public function getProductCategoryFacade(): ProductMerchantPortalGuiToProductCategoryFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_PRODUCT_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface
     */
    public function getMoneyFacade(): ProductMerchantPortalGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): ProductMerchantPortalGuiToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface
     */
    public function getPriceProductFacade(): ProductMerchantPortalGuiToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductVolumeFacadeInterface
     */
    public function getPriceProductVolumeFacade(): ProductMerchantPortalGuiToPriceProductVolumeFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_PRICE_PRODUCT_VOLUME);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface
     */
    public function getProductFacade(): ProductMerchantPortalGuiToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface
     */
    public function getProductAttributeFacade(): ProductMerchantPortalGuiToProductAttributeFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_PRODUCT_ATTRIBUTE);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductValidityFacadeInterface
     */
    public function getProductValidityFacade(): ProductMerchantPortalGuiToProductValidityFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_PRODUCT_VALIDITY);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToOmsFacadeInterface
     */
    public function getOmsFacade(): ProductMerchantPortalGuiToOmsFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantStockFacadeInterface
     */
    public function getMerchantStockFacade(): ProductMerchantPortalGuiToMerchantStockFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_STOCK);
    }

    /**
     * @return array<\Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\ProductAbstractFormExpanderPluginInterface>
     */
    public function getProductAbstractFormExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_FORM_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductMerchantPortalGuiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductVolumeServiceInterface
     */
    public function getPriceProductVolumeService(): ProductMerchantPortalGuiToPriceProductVolumeServiceInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::SERVICE_PRICE_PRODUCT_VOLUME);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductServiceInterface
     */
    public function getPriceProductService(): ProductMerchantPortalGuiToPriceProductServiceInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::SERVICE_PRICE_PRODUCT);
    }

    /**
     * @return array<\Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\ProductConcreteTableExpanderPluginInterface>
     */
    public function getProductConcreteTableExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::PLUGINS_PRODUCT_CONCRETE_TABLE_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\External\ProductMerchantPortalGuiToValidationAdapterInterface
     */
    public function getValidationAdapter(): ProductMerchantPortalGuiToValidationAdapterInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::ADAPTER_VALIDATION);
    }

    /**
     * @return array<\Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\PriceProductAbstractTableConfigurationExpanderPluginInterface>
     */
    public function getPriceProductAbstractTableConfigurationExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::PLUGINS_PRICE_PRODUCT_ABSTRACT_TABLE_CONFIGURATION_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\PriceProductConcreteTableConfigurationExpanderPluginInterface>
     */
    public function getPriceProductConcreteTableConfigurationExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::PLUGINS_PRICE_PRODUCT_CONCRETE_TABLE_CONFIGURATION_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\PriceProductTableFilterPluginInterface>
     */
    public function getPriceProductTableFilterPlugins(): array
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::PLUGINS_PRICE_PRODUCT_TABLE_FILTER);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ProductAttributesNotBlankConstraint
     */
    public function createProductAttributesNotBlankConstraint(): Constraint
    {
        return new ProductAttributesNotBlankConstraint(
            $this->getProductAttributeFacade(),
            $this->getProductFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ProductAbstractAttributeUniqueCombinationConstraint
     */
    public function createAbstractProductAttributeUniqueCombinationConstraint(): Constraint
    {
        return new ProductAbstractAttributeUniqueCombinationConstraint(
            $this->getProductAttributeFacade(),
            $this->getProductFacade(),
            $this->getTranslatorFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductAttributesMapperInterface
     */
    public function createProductAttributesMapper(): ProductAttributesMapperInterface
    {
        return new ProductAttributesMapper(
            $this->createProductAttributeDataProvider(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ImageSetMapperInterface
     */
    public function createImageSetMapper(): ImageSetMapperInterface
    {
        return new ImageSetMapper();
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createLocaleTransformer(): DataTransformerInterface
    {
        return new LocaleTransformer(
            $this->getLocaleFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Expander\ProductApprovalStatusProductTableConfigurationExpanderInterface
     */
    public function createProductApprovalStatusProductTableConfigurationExpander(): ProductApprovalStatusProductTableConfigurationExpanderInterface
    {
        return new ProductApprovalStatusProductTableConfigurationExpander($this->getTranslatorFacade());
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Expander\ProductApprovalStatusProductTableDataResponseExpanderInterface
     */
    public function createProductApprovalStatusProductTableDataResponseExpander(): ProductApprovalStatusProductTableDataResponseExpanderInterface
    {
        return new ProductApprovalStatusProductTableDataResponseExpander(
            $this->getTranslatorFacade(),
            $this->getProductFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductApprovalFacadeInterface
     */
    public function getProductApprovalFacade(): ProductMerchantPortalGuiToProductApprovalFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_PRODUCT_APPROVAL);
    }
}
