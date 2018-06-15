<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Spryker\Zed\PriceProduct\Business\Model\PriceDecision\PriceProductMatcherInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface;
use Spryker\Zed\PriceProduct\PriceProductConfig;

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
     * @var \Spryker\Zed\PriceProduct\Business\Model\PriceDecision\PriceProductMatcherInterface
     */
    protected $priceProductMatcher;

    /**
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface $priceProductQueryContainer
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductMapperInterface $priceProductMapper
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface $priceProductRepository
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceDecision\PriceProductMatcherInterface $priceProductMatcher
     */
    public function __construct(
        PriceProductQueryContainerInterface $priceProductQueryContainer,
        PriceProductMapperInterface $priceProductMapper,
        PriceProductToStoreFacadeInterface $storeFacade,
        PriceProductRepositoryInterface $priceProductRepository,
        PriceProductMatcherInterface $priceProductMatcher
    ) {
        $this->priceProductQueryContainer = $priceProductQueryContainer;
        $this->priceProductMapper = $priceProductMapper;
        $this->storeFacade = $storeFacade;
        $this->priceProductRepository = $priceProductRepository;
        $this->priceProductMatcher = $priceProductMatcher;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return bool
     */
    public function hasPriceForProductConcrete($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer)
    {
        $moneyValueTransfer = $this->findPriceForProductConcrete($sku, $priceProductCriteriaTransfer);
        if (!$moneyValueTransfer) {
            return false;
        }

        if ($priceProductCriteriaTransfer->getPriceMode() === $this->priceProductMapper->getNetPriceModeIdentifier()) {
            return $moneyValueTransfer->getNetAmount() !== null;
        }

        return $moneyValueTransfer->getGrossAmount() !== null;
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

        $priceProductCriteriaTrasfer = new PriceProductCriteriaTransfer();
        $priceProductCriteriaTrasfer->fromArray($priceProductDimensionTransfer->toArray(), true);

        $priceProductCriteriaTrasfer->setIdStore($idStore)
            ->setPriceDimension($priceProductDimensionTransfer->getType());

        $priceProductStoreTransferCollection = $this->priceProductRepository
            ->findProductConcretePricesBySkuAndCriteria($sku, $priceProductCriteriaTrasfer);

        return $this->priceProductMapper->mapPriceProductStoreEntityTransfersToPriceProduct(
            $priceProductStoreTransferCollection,
            $priceProductCriteriaTrasfer
        );
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePricesById(
        $idProductConcrete,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ) {
        if (!$priceProductCriteriaTransfer) {
            $priceProductCriteriaTransfer = new PriceProductCriteriaTransfer();
            $priceProductDimension = (new PriceProductDimensionTransfer())
                ->setType(PriceProductConfig::PRICE_DIMENSION_DEFAULT);
            $priceProductCriteriaTransfer->setPriceDimension($priceProductDimension);
        }

        $priceProductStoreTransferCollection = $this->priceProductRepository->findProductConcretePricesByIdAndCriteria(
            $idProductConcrete,
            $priceProductCriteriaTransfer
        );

        return $this->priceProductMapper->mapPriceProductStoreEntityTransfersToPriceProduct(
            $priceProductStoreTransferCollection,
            $priceProductCriteriaTransfer
        );
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer|null
     */
    public function findPriceForProductConcrete($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer)
    {
        $priceProductStoreEntityTransferCollection = $this->priceProductRepository
            ->findProductConcretePricesBySkuAndCriteria($sku, $priceProductCriteriaTransfer);

        return $this->priceProductMatcher->matchPriceValue(
            $priceProductStoreEntityTransferCollection,
            $priceProductCriteriaTransfer
        );
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
