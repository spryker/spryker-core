<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Spryker\Service\PriceProduct\PriceProductServiceInterface;
use Spryker\Shared\PriceProduct\PriceProductConfig;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductReader\PriceProductReaderPluginExecutorInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface;

class PriceProductConcreteReader implements PriceProductConcreteReaderInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface
     */
    protected $priceProductQueryContainer;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductMapperInterface
     */
    protected $priceProductMapper;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface
     */
    protected $priceProductRepository;

    /**
     * @var \Spryker\Service\PriceProduct\PriceProductServiceInterface
     */
    protected $priceProductService;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductExpanderInterface
     */
    protected $priceProductExpander;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductReader\PriceProductReaderPluginExecutorInterface
     */
    protected $pluginExecutor;

    /**
     * @var \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductExternalProviderPluginInterface[]
     */
    protected $priceProductExternalProviderPlugins;

    /**
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface $priceProductQueryContainer
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductMapperInterface $priceProductMapper
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface $priceProductRepository
     * @param \Spryker\Service\PriceProduct\PriceProductServiceInterface $priceProductService
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductExpanderInterface $priceProductExpander
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductReader\PriceProductReaderPluginExecutorInterface $pluginExecutor
     * @param \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductExternalProviderPluginInterface[] $priceProductExternalProviderPlugins
     */
    public function __construct(
        PriceProductQueryContainerInterface $priceProductQueryContainer,
        PriceProductMapperInterface $priceProductMapper,
        PriceProductToStoreFacadeInterface $storeFacade,
        PriceProductRepositoryInterface $priceProductRepository,
        PriceProductServiceInterface $priceProductService,
        PriceProductExpanderInterface $priceProductExpander,
        PriceProductReaderPluginExecutorInterface $pluginExecutor,
        array $priceProductExternalProviderPlugins
    ) {
        $this->priceProductQueryContainer = $priceProductQueryContainer;
        $this->priceProductMapper = $priceProductMapper;
        $this->storeFacade = $storeFacade;
        $this->priceProductRepository = $priceProductRepository;
        $this->priceProductService = $priceProductService;
        $this->priceProductExpander = $priceProductExpander;
        $this->pluginExecutor = $pluginExecutor;
        $this->priceProductExternalProviderPlugins = $priceProductExternalProviderPlugins;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return bool
     */
    public function hasPriceForProductConcrete($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer)
    {
        return $this->findPriceForProductConcrete($sku, $priceProductCriteriaTransfer) !== null;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePricesBySkuForCurrentStore(
        string $sku,
        PriceProductDimensionTransfer $priceProductDimensionTransfer
    ): array {
        $idStore = $this->storeFacade->getCurrentStore()->getIdStore();

        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
            ->setIdStore($idStore)
            ->setPriceDimension($priceProductDimensionTransfer);

        $priceProductStoreEntities = $this->priceProductRepository
            ->findProductConcretePricesBySkuAndCriteria($sku, $priceProductCriteriaTransfer);

        $priceProductTransfers = $this->priceProductMapper->mapPriceProductStoreEntitiesToPriceProductTransfers(
            $priceProductStoreEntities
        );

        $priceProductTransfers = $this->priceProductExpander->expandPriceProductTransfers($priceProductTransfers);
        $priceProductTransfers = $this->pluginExecutor->executePriceExtractorPluginsForProductConcrete($priceProductTransfers);

        return $priceProductTransfers;
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePricesById(
        int $idProductConcrete,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): array {
        if (!$priceProductCriteriaTransfer) {
            $priceProductCriteriaTransfer = new PriceProductCriteriaTransfer();
        }

        $priceProductStoreEntities = $this->priceProductRepository->findProductConcretePricesByIdAndCriteria(
            $idProductConcrete,
            $priceProductCriteriaTransfer
        );

        $priceProductTransfers = $this->priceProductMapper->mapPriceProductStoreEntitiesToPriceProductTransfers(
            $priceProductStoreEntities
        );

        $priceProductTransfers = $this->priceProductExpander->expandPriceProductTransfers($priceProductTransfers);
        $priceProductTransfers = $this->pluginExecutor->executePriceExtractorPluginsForProductConcrete($priceProductTransfers);

        return $priceProductTransfers;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function findPriceForProductConcrete(string $sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ?PriceProductTransfer
    {
        $priceProductTransfers = $this->findProductConcretePricesBySkuAndCriteria($sku, $priceProductCriteriaTransfer);

        return $this->priceProductService->resolveProductPriceByPriceProductCriteria($priceProductTransfers, $priceProductCriteriaTransfer);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePricesBySkuAndCriteria(string $sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): array
    {
        $priceProductStoreEntities = $this->priceProductRepository
            ->findProductConcretePricesBySkuAndCriteria($sku, $priceProductCriteriaTransfer);

        $priceProductTransfers = $this->priceProductMapper->mapPriceProductStoreEntitiesToPriceProductTransfers(
            $priceProductStoreEntities
        );

        $priceProductTransfers = $this->priceProductExpander->expandPriceProductTransfers($priceProductTransfers);
        $priceProductTransfers = $this->pluginExecutor->executePriceExtractorPluginsForProductConcrete($priceProductTransfers);

        return $priceProductTransfers;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return int|null
     */
    public function findPriceProductId($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer)
    {
        /** @var int|null $idPriceProduct */
        $idPriceProduct = $this->priceProductQueryContainer
            ->queryPriceEntityForProductConcrete($sku, $priceProductCriteriaTransfer)
            ->select([SpyPriceProductTableMap::COL_ID_PRICE_PRODUCT])
            ->findOne();

        if (!$idPriceProduct) {
            return null;
        }

        return (int)$idPriceProduct;
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePricesWithoutPriceExtraction(
        int $idProductConcrete,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): array {
        if (!$priceProductCriteriaTransfer) {
            $priceProductCriteriaTransfer = new PriceProductCriteriaTransfer();
        }

        $priceProductStoreEntities = $this->priceProductRepository->findProductConcretePricesByIdAndCriteria(
            $idProductConcrete,
            $priceProductCriteriaTransfer
        );
        $priceProductTransfers = $this->priceProductMapper->mapPriceProductStoreEntitiesToPriceProductTransfers(
            $priceProductStoreEntities
        );
        $priceProductTransfers = $this->priceProductExpander->expandPriceProductTransfers($priceProductTransfers);

        return $priceProductTransfers;
    }

    /**
     * @param string[] $skus
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getProductConcretePricesByConcreteSkusAndCriteria(array $skus, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): array
    {
        $priceProductTransfers = $this->priceProductRepository->getProductConcretePricesByConcreteSkusAndCriteria($skus, $priceProductCriteriaTransfer);
        $priceProductTransfers = $this->executePriceProductExternalProviderPlugins($priceProductTransfers, $skus, $priceProductCriteriaTransfer);
        $priceProductTransfers = $this->priceProductExpander->expandPriceProductTransfers($priceProductTransfers);
        $priceProductTransfers = $this->pluginExecutor->executePriceExtractorPluginsForProductConcrete($priceProductTransfers);

        return $priceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteWithPrices(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())->setPriceDimension(
            (new PriceProductDimensionTransfer())
                ->setType(PriceProductConfig::PRICE_DIMENSION_DEFAULT)
        );

        $priceProductTransfers = $this->findProductConcretePricesById(
            $productConcreteTransfer->getIdProductConcrete(),
            $priceProductCriteriaTransfer
        );

        if ($priceProductTransfers) {
            $productConcreteTransfer->setPrices(new ArrayObject($priceProductTransfers));
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[][]
     */
    protected function indexPriceProductTransferByProductSku(array $priceProductTransfers): array
    {
        $indexedPriceProductTransfers = [];
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $indexedPriceProductTransfers[$priceProductTransfer->getSkuProduct()][] = $priceProductTransfer;
        }

        return $indexedPriceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param string[] $skus
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function executePriceProductExternalProviderPlugins(
        array $priceProductTransfers,
        array $skus,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): array {
        foreach ($this->priceProductExternalProviderPlugins as $priceProductExternalProviderPlugin) {
            $priceProductTransfers = array_merge($priceProductTransfers, $priceProductExternalProviderPlugin->providePriceProductTransfers(
                $skus,
                $priceProductCriteriaTransfer
            ));
        }

        return $priceProductTransfers;
    }
}
