<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Spryker\Service\PriceProduct\PriceProductServiceInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface;

class PriceProductConcreteReader implements PriceProductConcreteReaderInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;
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
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface $priceProductQueryContainer
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductMapperInterface $priceProductMapper
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface $priceProductRepository
     * @param \Spryker\Service\PriceProduct\PriceProductServiceInterface $priceProductService
     */
    public function __construct(
        PriceProductQueryContainerInterface $priceProductQueryContainer,
        PriceProductMapperInterface $priceProductMapper,
        PriceProductToStoreFacadeInterface $storeFacade,
        PriceProductRepositoryInterface $priceProductRepository,
        PriceProductServiceInterface $priceProductService
    ) {
        $this->priceProductQueryContainer = $priceProductQueryContainer;
        $this->priceProductMapper = $priceProductMapper;
        $this->storeFacade = $storeFacade;
        $this->priceProductRepository = $priceProductRepository;
        $this->priceProductService = $priceProductService;
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
        $sku,
        PriceProductDimensionTransfer $priceProductDimensionTransfer
    ) {
        $idStore = $this->storeFacade->getCurrentStore()->getIdStore();

        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
            ->setIdStore($idStore)
            ->setPriceDimension($priceProductDimensionTransfer);

        $priceProductStoreEntities = $this->priceProductRepository
            ->findProductConcretePricesBySkuAndCriteria($sku, $priceProductCriteriaTransfer);

        return $this->priceProductMapper->mapPriceProductStoreEntitiesToPriceProductTransfers(
            $priceProductStoreEntities,
            $priceProductCriteriaTransfer
        );
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePricesById(
        $idProductConcrete,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ) {
        if (!$priceProductCriteriaTransfer) {
            $priceProductCriteriaTransfer = new PriceProductCriteriaTransfer();
        }

        $priceProductStoreEntities = $this->priceProductRepository->findProductConcretePricesByIdAndCriteria(
            $idProductConcrete,
            $priceProductCriteriaTransfer
        );

        return $this->priceProductMapper->mapPriceProductStoreEntitiesToPriceProductTransfers(
            $priceProductStoreEntities,
            $priceProductCriteriaTransfer
        );
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function findPriceForProductConcrete($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ?PriceProductTransfer
    {
        $priceProductStoreEntities = $this->priceProductRepository
            ->findProductConcretePricesBySkuAndCriteria($sku, $priceProductCriteriaTransfer);

        $priceProductTransfers = $this->priceProductMapper->mapPriceProductStoreEntitiesToPriceProductTransfers(
            $priceProductStoreEntities,
            $priceProductCriteriaTransfer
        );

        return $this->priceProductService->resolveProductPriceByPriceProductCriteria($priceProductTransfers, $priceProductCriteriaTransfer);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return int|null
     */
    public function findPriceProductId($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer)
    {
        $idPriceProduct = $this->priceProductQueryContainer
            ->queryPriceEntityForProductConcrete($sku, $priceProductCriteriaTransfer)
            ->select([SpyPriceProductTableMap::COL_ID_PRICE_PRODUCT])
            ->findOne();

        if (!$idPriceProduct) {
            return null;
        }

        return (int)$idPriceProduct;
    }
}
