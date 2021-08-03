<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface;
use Spryker\Shared\ZedUi\ZedUiFactoryInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilder;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\GuiTableConfigurationProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\ProductGuiTableConfigurationProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\ProductOfferGuiTableConfigurationProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\OffersDashboardCardProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\OffersDashboardCardProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\PriceProductDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\PriceProductDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\PriceProductOfferDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\PriceProductOfferDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\ProductGuiTableDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\ProductOfferGuiTableDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Deleter\PriceDeleter;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Deleter\PriceDeleterInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Expander\MerchantOrderItemTableExpander;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Expander\MerchantOrderItemTableExpanderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Expander\PriceProductsVolumeDataExpander;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Expander\PriceProductsVolumeDataExpanderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Filter\PriceProductFilter;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Filter\PriceProductFilterInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Constraint\ValidProductOfferPriceIdsOwnByMerchantConstraint;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider\ProductOfferCreateFormDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider\ProductOfferCreateFormDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider\ProductOfferUpdateFormDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider\ProductOfferUpdateFormDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\ProductOfferForm;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\Merger\MergeStrategy\PriceProductMatchingExistingVolumePriceMergeStrategy;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\Merger\MergeStrategy\PriceProductMergeStrategyInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\Merger\MergeStrategy\VolumePriceMatchingExistingPriceProductMergeStrategy;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\Merger\MergeStrategy\VolumePriceNotMatchingExistingPriceProductMergeStrategy;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\Merger\PriceProductsMerger;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\Merger\PriceProductsMergerInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\PriceProductOfferTransformer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\ProductOfferStockTransformer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\QuantityTransformer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\StoresTransformer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\Column\ColumnIdCreator;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\Column\ColumnIdCreatorInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductOfferCreateGuiTableConfigurationProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductOfferCreateGuiTableConfigurationProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductOfferUpdateGuiTableConfigurationProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductOfferUpdateGuiTableConfigurationProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\DataProvider\ProductOfferPriceGuiTableDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Mapper\PriceProductOfferMapper;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Mapper\PriceProductOfferTableDataMapper;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Mapper\PriceProductOfferTableDataMapperInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Reader\PriceProductReader;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Reader\PriceProductReaderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Sorter\ComparisonStrategy\PriceProductOfferTableView\PriceProductOfferTableViewComparisonStrategyInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Sorter\ComparisonStrategy\PriceProductOfferTableView\PriceProductOfferTableViewPriceComparisonStrategy;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Sorter\ComparisonStrategy\PriceProductOfferTableView\PriceProductOfferTableViewSimpleGetterComparisonStrategy;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Sorter\PriceProductOfferTableViewSorter;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Sorter\PriceProductOfferTableViewSorterInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\Constraint\VolumePriceHasBasePriceProductConstraint;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\PriceProductOfferCollectionConstraintProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\PriceProductOfferConstraintProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\PriceProductOfferValidator;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\PriceProductOfferValidatorInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\PropertyPath\PriceProductOfferPropertyPathAnalyzer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\PropertyPath\PriceProductOfferPropertyPathAnalyzerInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\External\ProductOfferMerchantPortalGuiToValidationAdapterInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantStockFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferVolumeFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToRouterFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToPriceProductVolumeServiceInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiDependencyProvider;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Twig\Environment;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiConfig getConfig()
 */
class ProductOfferMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\GuiTableConfigurationProviderInterface
     */
    public function createProductGuiTableConfigurationProvider(): GuiTableConfigurationProviderInterface
    {
        return new ProductGuiTableConfigurationProvider(
            $this->getTranslatorFacade(),
            $this->getGuiTableFactory()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\GuiTableConfigurationProviderInterface
     */
    public function createProductOfferGuiTableConfigurationProvider(): GuiTableConfigurationProviderInterface
    {
        return new ProductOfferGuiTableConfigurationProvider(
            $this->getStoreFacade(),
            $this->getTranslatorFacade(),
            $this->getGuiTableFactory()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductOfferUpdateGuiTableConfigurationProviderInterface
     */
    public function createPriceProductOfferUpdateGuiTableConfigurationProvider(): PriceProductOfferUpdateGuiTableConfigurationProviderInterface
    {
        return new PriceProductOfferUpdateGuiTableConfigurationProvider(
            $this->getGuiTableFactory(),
            $this->getPriceProductFacade(),
            $this->getStoreFacade(),
            $this->getCurrencyFacade(),
            $this->createColumnIdCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\PriceProductOfferCreateGuiTableConfigurationProviderInterface
     */
    public function createPriceProductOfferCreateGuiTableConfigurationProvider(): PriceProductOfferCreateGuiTableConfigurationProviderInterface
    {
        return new PriceProductOfferCreateGuiTableConfigurationProvider(
            $this->getGuiTableFactory(),
            $this->getPriceProductFacade(),
            $this->getStoreFacade(),
            $this->getCurrencyFacade(),
            $this->createColumnIdCreator()
        );
    }

    /**
     * @return \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface
     */
    public function createProductTableDataProvider(): GuiTableDataProviderInterface
    {
        return new ProductGuiTableDataProvider(
            $this->getRepository(),
            $this->getTranslatorFacade(),
            $this->createProductNameBuilder(),
            $this->getMerchantUserFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface
     */
    public function createProductOfferTableDataProvider(): GuiTableDataProviderInterface
    {
        return new ProductOfferGuiTableDataProvider(
            $this->getRepository(),
            $this->getTranslatorFacade(),
            $this->createProductNameBuilder(),
            $this->getMerchantUserFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @param int|null $idProductOffer
     *
     * @return \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface
     */
    public function createProductOfferPriceTableDataProvider(?int $idProductOffer = null): GuiTableDataProviderInterface
    {
        return new ProductOfferPriceGuiTableDataProvider(
            $this->getMerchantUserFacade(),
            $this->getMoneyFacade(),
            $this->createPriceProductOfferTableDataMapper(),
            $this->createPriceProductReader(),
            $this->createPriceProductOfferTableViewSorter(),
            $idProductOffer
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface
     */
    public function createProductNameBuilder(): ProductNameBuilderInterface
    {
        return new ProductNameBuilder();
    }

    /**
     * @phpstan-return \Symfony\Component\Form\FormInterface<mixed>
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer|null $data
     * @param mixed[] $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductOfferForm(?ProductOfferTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ProductOfferForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider\ProductOfferCreateFormDataProviderInterface
     */
    public function createProductOfferCreateFormDataProvider(): ProductOfferCreateFormDataProviderInterface
    {
        return new ProductOfferCreateFormDataProvider(
            $this->getProductFacade(),
            $this->getMerchantUserFacade(),
            $this->getMerchantStockFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider\ProductOfferUpdateFormDataProviderInterface
     */
    public function createProductOfferUpdateFormDataProvider(): ProductOfferUpdateFormDataProviderInterface
    {
        return new ProductOfferUpdateFormDataProvider(
            $this->getProductFacade(),
            $this->getProductOfferFacade(),
            $this->getMerchantStockFacade(),
            $this->getMerchantUserFacade()
        );
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createStoresTransformer(): DataTransformerInterface
    {
        return new StoresTransformer();
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
    public function createProductOfferStockTransformer(): DataTransformerInterface
    {
        return new ProductOfferStockTransformer();
    }

    /**
     * @param int|null $idProductOffer
     *
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createPriceProductOfferTransformer(?int $idProductOffer = null): DataTransformerInterface
    {
        return new PriceProductOfferTransformer(
            $this->getUtilEncodingService(),
            $this->getPriceProductFacade(),
            $this->getCurrencyFacade(),
            $this->getMoneyFacade(),
            $this->createPriceProductToPriceProductOfferMerger(),
            $this->createColumnIdCreator(),
            $this->createPriceProductOfferDataProvider(),
            $idProductOffer,
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\OffersDashboardCardProviderInterface
     */
    public function createOffersDashboardCardProvider(): OffersDashboardCardProviderInterface
    {
        return new OffersDashboardCardProvider(
            $this->getRepository(),
            $this->getMerchantUserFacade(),
            $this->getRouterFacade(),
            $this->getConfig(),
            $this->getTwigEnvironment()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Mapper\PriceProductOfferMapper
     */
    public function createPriceProductOfferMapper(): PriceProductOfferMapper
    {
        return new PriceProductOfferMapper(
            $this->getPriceProductFacade(),
            $this->getMoneyFacade(),
            $this->getPriceProductOfferVolumeFacade(),
            $this->getPriceProductVolumeService(),
            $this->createPriceProductOfferPropertyPathAnalyzer(),
            $this->createColumnIdCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Expander\MerchantOrderItemTableExpanderInterface
     */
    public function createMerchantOrderItemTableExpander(): MerchantOrderItemTableExpanderInterface
    {
        return new MerchantOrderItemTableExpander($this->getProductOfferFacade());
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Mapper\PriceProductOfferTableDataMapperInterface
     */
    public function createPriceProductOfferTableDataMapper(): PriceProductOfferTableDataMapperInterface
    {
        return new PriceProductOfferTableDataMapper(
            $this->getPriceProductFacade(),
            $this->getStoreFacade(),
            $this->createColumnIdCreator()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Reader\PriceProductReaderInterface
     */
    public function createPriceProductReader(): PriceProductReaderInterface
    {
        return new PriceProductReader(
            $this->getPriceProductOfferFacade(),
            $this->createPriceProductFilter()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Filter\PriceProductFilterInterface
     */
    public function createPriceProductFilter(): PriceProductFilterInterface
    {
        return new PriceProductFilter();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Sorter\PriceProductOfferTableViewSorterInterface
     */
    public function createPriceProductOfferTableViewSorter(): PriceProductOfferTableViewSorterInterface
    {
        return new PriceProductOfferTableViewSorter(
            $this->createPriceProductOfferTableViewSimpleGetterComparisonStrategy(),
            $this->createPriceProductOfferTableViewComparisonStrategies(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Sorter\ComparisonStrategy\PriceProductOfferTableView\PriceProductOfferTableViewComparisonStrategyInterface[]
     */
    public function createPriceProductOfferTableViewComparisonStrategies(): array
    {
        return [
            $this->createPriceProductOfferTableViewPriceComparisonStrategy(),
        ];
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Sorter\ComparisonStrategy\PriceProductOfferTableView\PriceProductOfferTableViewComparisonStrategyInterface
     */
    public function createPriceProductOfferTableViewSimpleGetterComparisonStrategy(): PriceProductOfferTableViewComparisonStrategyInterface
    {
        return new PriceProductOfferTableViewSimpleGetterComparisonStrategy();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Sorter\ComparisonStrategy\PriceProductOfferTableView\PriceProductOfferTableViewComparisonStrategyInterface
     */
    public function createPriceProductOfferTableViewPriceComparisonStrategy(): PriceProductOfferTableViewComparisonStrategyInterface
    {
        return new PriceProductOfferTableViewPriceComparisonStrategy(
            $this->createColumnIdCreator()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\PriceProductOfferValidatorInterface
     */
    public function createPriceProductOfferValidator(): PriceProductOfferValidatorInterface
    {
        return new PriceProductOfferValidator(
            $this->getValidationAdapter(),
            $this->createPriceProductOfferCollectionConstraintProvider(),
            $this->getPriceProductOfferFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\PriceProductOfferConstraintProviderInterface
     */
    public function createPriceProductOfferCollectionConstraintProvider(): PriceProductOfferConstraintProviderInterface
    {
        return new PriceProductOfferCollectionConstraintProvider(
            $this->createPriceProductOfferCollectionConstraints(),
        );
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    public function createPriceProductOfferCollectionConstraints(): array
    {
        return [
            $this->createVolumePriceHasBasePriceProductConstraint(),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createVolumePriceHasBasePriceProductConstraint(): SymfonyConstraint
    {
        return new VolumePriceHasBasePriceProductConstraint();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\Merger\PriceProductsMergerInterface
     */
    public function createPriceProductToPriceProductOfferMerger(): PriceProductsMergerInterface
    {
        return new PriceProductsMerger(
            $this->createPriceProductMergeStrategies(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\Merger\MergeStrategy\PriceProductMergeStrategyInterface[]
     */
    public function createPriceProductMergeStrategies(): array
    {
        return [
            $this->createPriceProductMatchingExistingVolumePriceMergeStrategy(),
            $this->createVolumePriceMatchingExistingPriceProductMergeStrategy(),
            $this->createVolumePriceNotMatchingExistingPriceProductMergeStrategy(),
        ];
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\Merger\MergeStrategy\PriceProductMergeStrategyInterface
     */
    public function createVolumePriceNotMatchingExistingPriceProductMergeStrategy(): PriceProductMergeStrategyInterface
    {
        return new VolumePriceNotMatchingExistingPriceProductMergeStrategy(
            $this->getPriceProductVolumeService(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\Merger\MergeStrategy\PriceProductMergeStrategyInterface
     */
    public function createPriceProductMatchingExistingVolumePriceMergeStrategy(): PriceProductMergeStrategyInterface
    {
        return new PriceProductMatchingExistingVolumePriceMergeStrategy(
            $this->getPriceProductVolumeService(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\Merger\MergeStrategy\PriceProductMergeStrategyInterface
     */
    public function createVolumePriceMatchingExistingPriceProductMergeStrategy(): PriceProductMergeStrategyInterface
    {
        return new VolumePriceMatchingExistingPriceProductMergeStrategy(
            $this->getPriceProductVolumeService(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\Column\ColumnIdCreatorInterface
     */
    public function createColumnIdCreator(): ColumnIdCreatorInterface
    {
        return new ColumnIdCreator();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\PropertyPath\PriceProductOfferPropertyPathAnalyzerInterface
     */
    public function createPriceProductOfferPropertyPathAnalyzer(): PriceProductOfferPropertyPathAnalyzerInterface
    {
        return new PriceProductOfferPropertyPathAnalyzer(
            $this->createColumnIdCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Expander\PriceProductsVolumeDataExpanderInterface
     */
    public function createPriceProductsVolumeDataExpander(): PriceProductsVolumeDataExpanderInterface
    {
        return new PriceProductsVolumeDataExpander(
            $this->getPriceProductVolumeService(),
            $this->createPriceProductOfferMapper(),
            $this->getPriceProductOfferVolumeFacade(),
            $this->createPriceProductFilter(),
            $this->createPriceProductOfferDataProvider()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\PriceProductDataProviderInterface
     */
    public function createPriceProductDataProvider(): PriceProductDataProviderInterface
    {
        return new PriceProductDataProvider(
            $this->getPriceProductFacade(),
            $this->getPriceProductOfferFacade(),
            $this->createPriceProductOfferMapper(),
            $this->createPriceProductsVolumeDataExpander()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Deleter\PriceDeleterInterface
     */
    public function createPriceDeleter(): PriceDeleterInterface
    {
        return new PriceDeleter(
            $this->getPriceProductOfferFacade(),
            $this->getPriceProductVolumeService(),
            $this->createPriceProductOfferValidator(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\PriceProductOfferDataProviderInterface
     */
    public function createPriceProductOfferDataProvider(): PriceProductOfferDataProviderInterface
    {
        return new PriceProductOfferDataProvider(
            $this->getMerchantUserFacade(),
            $this->getProductOfferFacade(),
            $this->createPriceProductFilter()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductOfferMerchantPortalGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): ProductOfferMerchantPortalGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductOfferMerchantPortalGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToRouterFacadeInterface
     */
    public function getRouterFacade(): ProductOfferMerchantPortalGuiToRouterFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_ROUTER);
    }

    /**
     * @return \Twig\Environment
     */
    protected function getTwigEnvironment(): Environment
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::SERVICE_TWIG);
    }

    /**
     * @return \Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface
     */
    public function getGuiTableHttpDataRequestExecutor(): GuiTableDataRequestExecutorInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR);
    }

    /**
     * @return \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    public function getGuiTableFactory(): GuiTableFactoryInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::SERVICE_GUI_TABLE_FACTORY);
    }

    /**
     * @return \Spryker\Shared\ZedUi\ZedUiFactoryInterface
     */
    public function getZedUiFactory(): ZedUiFactoryInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::SERVICE_ZED_UI_FACTORY);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductFacadeInterface
     */
    public function getProductFacade(): ProductOfferMerchantPortalGuiToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): ProductOfferMerchantPortalGuiToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_PRODUCT_OFFER);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantStockFacadeInterface
     */
    public function getMerchantStockFacade(): ProductOfferMerchantPortalGuiToMerchantStockFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_STOCK);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): ProductOfferMerchantPortalGuiToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface
     */
    public function getPriceProductFacade(): ProductOfferMerchantPortalGuiToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\External\ProductOfferMerchantPortalGuiToValidationAdapterInterface
     */
    public function getValidationAdapter(): ProductOfferMerchantPortalGuiToValidationAdapterInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::EXTERNAL_ADAPTER_VALIDATION);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface
     */
    public function getPriceProductOfferFacade(): ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_PRICE_PRODUCT_OFFER);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferVolumeFacadeInterface
     */
    public function getPriceProductOfferVolumeFacade(): ProductOfferMerchantPortalGuiToPriceProductOfferVolumeFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_PRICE_PRODUCT_OFFER_VOLUME);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToPriceProductVolumeServiceInterface
     */
    public function getPriceProductVolumeService(): ProductOfferMerchantPortalGuiToPriceProductVolumeServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::SERVICE_PRICE_PRODUCT_VOLUME);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createValidProductOfferPriceIdsOwnByMerchantConstraint(): SymfonyConstraint
    {
        return new ValidProductOfferPriceIdsOwnByMerchantConstraint();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface
     */
    public function getMoneyFacade(): ProductOfferMerchantPortalGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_MONEY);
    }
}
