<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferStorage\Business\PriceProductOfferStorage;

use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductOfferStorageTransfer;
use Orm\Zed\Currency\Persistence\Map\SpyCurrencyTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProductOffer\Persistence\Map\SpyPriceProductOfferTableMap;
use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOfferQuery;
use Orm\Zed\PriceProductOfferStorage\Persistence\SpyProductConcreteProductOfferPriceStorageQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\PriceProductOfferStorage\Dependency\Facade\PriceProductOfferStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\PriceProductOfferStorage\Dependency\Facade\PriceProductOfferStorageToEventFacadeInterface;
use Spryker\Zed\PriceProductOfferStorage\Dependency\Facade\PriceProductOfferStorageToPriceProductOfferFacadeInterface;

class PriceProductOfferStorageWriter implements PriceProductOfferStorageWriterInterface
{
    /**
     * @var string
     */
    protected const ID_PRICE_PRODUCT_OFFER = 'id_price_product_offer';

    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    /**
     * @var string
     */
    protected const COL_ID_PRODUCT_NAME = 'IdProduct';

    /**
     * @var \Spryker\Zed\PriceProductOfferStorage\Dependency\Facade\PriceProductOfferStorageToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @var \Spryker\Zed\PriceProductOfferStorage\Dependency\Facade\PriceProductOfferStorageToPriceProductOfferFacadeInterface
     */
    protected $priceProductOfferFacade;

    /**
     * @var \Spryker\Zed\PriceProductOfferStorage\Dependency\Facade\PriceProductOfferStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @param \Spryker\Zed\PriceProductOfferStorage\Dependency\Facade\PriceProductOfferStorageToEventFacadeInterface $eventFacade
     * @param \Spryker\Zed\PriceProductOfferStorage\Dependency\Facade\PriceProductOfferStorageToPriceProductOfferFacadeInterface $priceProductOfferFacade
     * @param \Spryker\Zed\PriceProductOfferStorage\Dependency\Facade\PriceProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     */
    public function __construct(
        PriceProductOfferStorageToEventFacadeInterface $eventFacade,
        PriceProductOfferStorageToPriceProductOfferFacadeInterface $priceProductOfferFacade,
        PriceProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
    ) {
        $this->eventFacade = $eventFacade;
        $this->priceProductOfferFacade = $priceProductOfferFacade;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
    }

    /**
     * @param array<int> $priceProductOfferIds
     *
     * @return void
     */
    public function publish(array $priceProductOfferIds): void
    {
        $productSkus = $this->getProductSkusByPriceProductOfferIds($priceProductOfferIds);
        $priceProductOffers = $this->getProductOfferDataByProductSkus($productSkus);

        $this->savePriceProductOfferStorage($priceProductOffers);
    }

    /**
     * @param array<int> $priceProductOfferIdsWithOfferIds
     *
     * @return void
     */
    public function unpublish(array $priceProductOfferIdsWithOfferIds): void
    {
        $priceProductOfferIds = array_keys($priceProductOfferIdsWithOfferIds);
        $productOfferIds = array_values($priceProductOfferIdsWithOfferIds);
        $productIds = $this->getProductIdsByProductOfferIds($productOfferIds);
        $productConcreteProductOfferPriceStorageEntities = $this->getProductConcreteProductOfferPriceStorageEntities($productIds);

        foreach ($productConcreteProductOfferPriceStorageEntities as $productConcreteProductOfferPriceStorageEntity) {
            $offerPrices = $productConcreteProductOfferPriceStorageEntity->getData();
            foreach ($offerPrices as $key => $offerPrice) {
                if (in_array($offerPrice[static::ID_PRICE_PRODUCT_OFFER], $priceProductOfferIds)) {
                    unset($offerPrices[$key]);
                }
            }
            if (!$offerPrices) {
                $productConcreteProductOfferPriceStorageEntity->delete();

                continue;
            }
            $productConcreteProductOfferPriceStorageEntity->setData(array_values($offerPrices));
            $productConcreteProductOfferPriceStorageEntity->save();
        }
    }

    /**
     * @param array<int> $productIds
     *
     * @return void
     */
    public function publishByProductIds(array $productIds): void
    {
        $productEntities = $this->getProductEntitiesByProductIds($productIds);
        $publishData = [];
        $unpublishData = [];
        foreach ($productEntities as $productEntity) {
            if ($productEntity->isActive()) {
                $publishData[$productEntity->getSku()] = $productEntity->getIdProduct();

                continue;
            }
            $unpublishData[$productEntity->getSku()] = $productEntity->getIdProduct();
        }
        if ($unpublishData) {
            $this->unpublishByProductIds($unpublishData);
        }
        if ($publishData) {
            $priceProductOffers = $this->getProductOfferDataByProductSkus(array_keys($publishData));
            $this->savePriceProductOfferStorage($priceProductOffers);
        }
    }

    /**
     * @param array<int> $productIds
     *
     * @return void
     */
    public function unpublishByProductIds(array $productIds): void
    {
        $priceProductOfferStorageEntities = SpyProductConcreteProductOfferPriceStorageQuery::create()
            ->filterByFkProduct_In($productIds)
            ->find();

        foreach ($priceProductOfferStorageEntities as $priceProductOfferStorageEntity) {
            $priceProductOfferStorageEntity->delete();
        }
    }

    /**
     * @param array<int> $priceProductOfferIds
     *
     * @return array
     */
    protected function getProductSkusByPriceProductOfferIds(array $priceProductOfferIds): array
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $productSkus */
        $productSkus = SpyPriceProductOfferQuery::create()
            ->filterByIdPriceProductOffer_In($priceProductOfferIds)
            ->joinWithSpyProductOffer()
            ->select([SpyProductOfferTableMap::COL_CONCRETE_SKU])
            ->distinct()
            ->find();

        return $productSkus->toArray();
    }

    /**
     * @param array<string> $productSkus
     *
     * @return array
     */
    protected function getProductOfferDataByProductSkus(array $productSkus): array
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $productOfferData */
        $productOfferData = SpyProductOfferQuery::create()
            ->useSpyPriceProductOfferQuery()
                ->useSpyPriceProductStoreQuery()
                    ->joinWithCurrency()
                    ->joinWithStore()
                    ->usePriceProductQuery()
                        ->joinWithPriceType()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->addJoin(
                SpyProductOfferTableMap::COL_CONCRETE_SKU,
                SpyProductTableMap::COL_SKU,
                Criteria::INNER_JOIN,
            )
            ->filterByConcreteSku_In($productSkus)
            ->addAnd(
                SpyProductTableMap::COL_IS_ACTIVE,
                1,
                Criteria::EQUAL,
            )
            ->select([
                SpyPriceProductOfferTableMap::COL_ID_PRICE_PRODUCT_OFFER,
                SpyProductOfferTableMap::COL_CONCRETE_SKU,
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE,
                SpyCurrencyTableMap::COL_CODE,
                SpyStoreTableMap::COL_NAME,
                SpyPriceTypeTableMap::COL_NAME,
                SpyPriceProductStoreTableMap::COL_GROSS_PRICE,
                SpyPriceProductStoreTableMap::COL_NET_PRICE,
                SpyPriceProductStoreTableMap::COL_PRICE_DATA,
            ])
            ->withColumn(SpyProductTableMap::COL_ID_PRODUCT, static::COL_ID_PRODUCT_NAME)
            ->find();

        return $productOfferData->toArray();
    }

    /**
     * @param array<int> $productIds
     *
     * @return \Propel\Runtime\Collection\Collection<\Orm\Zed\Product\Persistence\SpyProduct>
     */
    protected function getProductEntitiesByProductIds(array $productIds): Collection
    {
        $productEntities = SpyProductQuery::create()
            ->filterByIdProduct_In($productIds)
            ->find();

        return $productEntities;
    }

    /**
     * @param array $priceProductOffers
     *
     * @return void
     */
    protected function savePriceProductOfferStorage(array $priceProductOffers): void
    {
        $groupedProductOffersByStoreAndProductSku = [];
        $productSkuToIdMap = [];
        foreach ($priceProductOffers as $productOffer) {
            $concreteSku = $productOffer[SpyProductOfferTableMap::COL_CONCRETE_SKU];
            $priceProductOfferStorageTransfer = $this->createPriceProductOfferStorageTransfer($productOffer);
            $groupedProductOffersByStoreAndProductSku[$productOffer[SpyStoreTableMap::COL_NAME]][$concreteSku][] = $priceProductOfferStorageTransfer->modifiedToArray();
            $productSkuToIdMap[$concreteSku] = $productOffer[static::COL_ID_PRODUCT_NAME];
        }

        /** @var string $storeName */
        foreach ($groupedProductOffersByStoreAndProductSku as $storeName => $groupedProductOffersByProductSku) {
            foreach ($groupedProductOffersByProductSku as $productSku => $priceProductOffers) {
                $productConcreteProductOfferPriceStorageEntity = SpyProductConcreteProductOfferPriceStorageQuery::create()
                    ->filterByFkProduct($productSkuToIdMap[$productSku])
                    ->filterByStore($storeName)
                    ->findOneOrCreate();
                $productConcreteProductOfferPriceStorageEntity->setData($priceProductOffers);

                $productConcreteProductOfferPriceStorageEntity->save();
            }
        }
    }

    /**
     * @param array $productOffer
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferStorageTransfer
     */
    protected function createPriceProductOfferStorageTransfer(array $productOffer): PriceProductOfferStorageTransfer
    {
        $priceProductOfferStorageTransfer = new PriceProductOfferStorageTransfer();
        $priceProductOfferStorageTransfer->setIdPriceProductOffer($productOffer[SpyPriceProductOfferTableMap::COL_ID_PRICE_PRODUCT_OFFER]);
        $priceProductOfferStorageTransfer->setProductOfferReference($productOffer[SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE]);
        $priceProductOfferStorageTransfer->setPriceType($productOffer[SpyPriceTypeTableMap::COL_NAME]);
        $priceProductOfferStorageTransfer->setCurrency($productOffer[SpyCurrencyTableMap::COL_CODE]);
        $priceProductOfferStorageTransfer->setNetPrice($productOffer[SpyPriceProductStoreTableMap::COL_NET_PRICE]);
        $priceProductOfferStorageTransfer->setGrossPrice($productOffer[SpyPriceProductStoreTableMap::COL_GROSS_PRICE]);
        $priceProductOfferStorageTransfer->setPriceData($productOffer[SpyPriceProductStoreTableMap::COL_PRICE_DATA]);

        return $priceProductOfferStorageTransfer;
    }

    /**
     * @param array<int> $productIds
     *
     * @return \Propel\Runtime\Collection\Collection<\Orm\Zed\PriceProductOfferStorage\Persistence\SpyProductConcreteProductOfferPriceStorage>
     */
    protected function getProductConcreteProductOfferPriceStorageEntities(array $productIds): Collection
    {
        return SpyProductConcreteProductOfferPriceStorageQuery::create()
            ->filterByFkProduct_In($productIds)
            ->find();
    }

    /**
     * @param array<int> $productOfferIds
     *
     * @return array<int>
     */
    protected function getProductIdsByProductOfferIds(array $productOfferIds): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $productOfferSkus */
        $productOfferSkus = SpyProductOfferQuery::create()
            ->filterByIdProductOffer_In($productOfferIds)
            ->select(SpyProductOfferTableMap::COL_CONCRETE_SKU)
            ->distinct()
            ->find();

        /** @var \Propel\Runtime\Collection\ArrayCollection $productIdsByProductOfferIds */
        $productIdsByProductOfferIds = SpyProductQuery::create()
            ->filterBySku_In($productOfferSkus->toArray())
            ->select(SpyProductTableMap::COL_ID_PRODUCT)
            ->find();

        return $productIdsByProductOfferIds->toArray();
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByPriceProductStoreEvents(array $eventEntityTransfers): void
    {
        $priceProductStoreIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $priceProductTransfers = $this->priceProductOfferFacade->getProductOfferPrices(
            (new PriceProductOfferCriteriaTransfer())->setPriceProductStoreIds($priceProductStoreIds),
        );

        $priceProductOfferIds = [];
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceProductOfferIds[] = $priceProductTransfer->getPriceDimensionOrFail()->getIdPriceProductOfferOrFail();
        }

        $this->publish($priceProductOfferIds);
    }
}
