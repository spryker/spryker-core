<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage\Business\Storage;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer;
use Generated\Shared\Transfer\ProductOptionGroupStorageTransfer;
use Generated\Shared\Transfer\ProductOptionValueStorageTransfer;
use Generated\Shared\Transfer\ProductOptionValueStorePricesRequestTransfer;
use Orm\Zed\ProductOptionStorage\Persistence\SpyProductAbstractOptionStorage;
use Spryker\Zed\ProductOptionStorage\Dependency\Facade\ProductOptionStorageToProductOptionFacadeInterface;
use Spryker\Zed\ProductOptionStorage\Dependency\Facade\ProductOptionStorageToStoreFacadeInterface;
use Spryker\Zed\ProductOptionStorage\Persistence\ProductOptionStorageQueryContainerInterface;

class ProductOptionStorageWriter implements ProductOptionStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductOptionStorage\Dependency\Facade\ProductOptionStorageToProductOptionFacadeInterface
     */
    protected $productOptionFacade;

    /**
     * @var \Spryker\Zed\ProductOptionStorage\Dependency\Facade\ProductOptionStorageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\ProductOptionStorage\Persistence\ProductOptionStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductOptionStorage\Business\Storage\ProductOptionStorageReaderInterface
     */
    protected $productOptionStorageReader;

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var bool
     */
    protected $isSendingToQueue;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer[]
     */
    protected $stores = [];

    /**
     * @param \Spryker\Zed\ProductOptionStorage\Business\Storage\ProductOptionStorageReaderInterface $productOptionStorageReader
     * @param \Spryker\Zed\ProductOptionStorage\Dependency\Facade\ProductOptionStorageToProductOptionFacadeInterface $productOptionFacade
     * @param \Spryker\Zed\ProductOptionStorage\Dependency\Facade\ProductOptionStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductOptionStorage\Persistence\ProductOptionStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     */
    public function __construct(
        ProductOptionStorageReaderInterface $productOptionStorageReader,
        ProductOptionStorageToProductOptionFacadeInterface $productOptionFacade,
        ProductOptionStorageToStoreFacadeInterface $storeFacade,
        ProductOptionStorageQueryContainerInterface $queryContainer,
        $isSendingToQueue
    ) {
        $this->productOptionStorageReader = $productOptionStorageReader;
        $this->productOptionFacade = $productOptionFacade;
        $this->storeFacade = $storeFacade;
        $this->queryContainer = $queryContainer;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $this->stores = $this->storeFacade->getAllStores();
        $productOptionEntities = $this->findProductOptionAbstractEntities($productAbstractIds);
        $productOptions = [];
        foreach ($productOptionEntities as $productOptionEntity) {
            $productOptions[$productOptionEntity['fk_product_abstract']][] = $productOptionEntity;
        }

        $productAbstractOptionStorageEntities = $this->findProductStorageOptionEntitiesByProductAbstractIds($productAbstractIds);
        $productAbstractIdsWithDeactivatedGroups = $this->productOptionStorageReader->getProductAbstractIdsWithDeactivatedGroups($productAbstractIds);

        if ($productAbstractIdsWithDeactivatedGroups) {
            $deletableProductAbstractOptionStorageEntities = $this->filterProductAbstractOptionStorageEntitiesByProductAbstractIds(
                $productAbstractOptionStorageEntities,
                $productAbstractIdsWithDeactivatedGroups
            );

            $this->deleteProductAbstractOptionStorageEntities($deletableProductAbstractOptionStorageEntities);
        }

        $this->storeData($productAbstractOptionStorageEntities, $productOptions);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $productAbstractOptionStorageEntities = $this->findProductStorageOptionEntitiesByProductAbstractIds($productAbstractIds);
        foreach ($productAbstractOptionStorageEntities as $productAbstractOptionStorageEntityArray) {
            foreach ($productAbstractOptionStorageEntityArray as $productAbstractOptionStorageEntity) {
                $productAbstractOptionStorageEntity->delete();
            }
        }
    }

    /**
     * @param \Orm\Zed\ProductOptionStorage\Persistence\SpyProductAbstractOptionStorage[] $deletableProductAbstractOptionStorageEntities
     *
     * @return void
     */
    protected function deleteProductAbstractOptionStorageEntities(array $deletableProductAbstractOptionStorageEntities): void
    {
        foreach ($deletableProductAbstractOptionStorageEntities as $productAbstractOptionStorageEntity) {
            $productAbstractOptionStorageEntity->delete();
        }
    }

    /**
     * @param \Orm\Zed\ProductOptionStorage\Persistence\SpyProductAbstractOptionStorage[][] $productAbstractOptionStorageEntities
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductOptionStorage\Persistence\SpyProductAbstractOptionStorage[]
     */
    protected function filterProductAbstractOptionStorageEntitiesByProductAbstractIds(array $productAbstractOptionStorageEntities, array $productAbstractIds): array
    {
        $filteredProductAbstractOptionStorageEntities = [];

        foreach ($productAbstractOptionStorageEntities as $productAbstractOptionStorageEntityArray) {
            foreach ($productAbstractOptionStorageEntityArray as $productAbstractOptionStorageEntity) {
                if (in_array($productAbstractOptionStorageEntity->getFkProductAbstract(), $productAbstractIds, true)) {
                    $filteredProductAbstractOptionStorageEntities[] = $productAbstractOptionStorageEntity;
                }
            }
        }

        return $filteredProductAbstractOptionStorageEntities;
    }

    /**
     * @param \Orm\Zed\ProductOptionStorage\Persistence\SpyProductAbstractOptionStorage[][] $productAbstractOptionStorageEntities
     *
     * @return void
     */
    protected function deleteStorageData(array $productAbstractOptionStorageEntities): void
    {
        foreach ($productAbstractOptionStorageEntities as $productAbstractOptionStorageEntityArray) {
            foreach ($productAbstractOptionStorageEntityArray as $productAbstractOptionStorageEntity) {
                $productAbstractOptionStorageEntity->delete();
            }
        }
    }

    /**
     * @param array $spyProductAbstractOptionStorageEntities
     * @param array $productAbstractWithOptions
     *
     * @return void
     */
    protected function storeData(array $spyProductAbstractOptionStorageEntities, array $productAbstractWithOptions): void
    {
        foreach ($productAbstractWithOptions as $idProductAbstract => $productOption) {
            if (isset($spyProductAbstractOptionStorageEntities[$idProductAbstract])) {
                $this->storeDataSet($idProductAbstract, $productOption, $spyProductAbstractOptionStorageEntities[$idProductAbstract]);

                continue;
            }

            $this->storeDataSet($idProductAbstract, $productOption);
        }
    }

    /**
     * @param int $idProductAbstract
     * @param array $productOptions
     * @param \Orm\Zed\ProductOptionStorage\Persistence\SpyProductAbstractOptionStorage[] $productAbstractOptionStorageEntities
     *
     * @return void
     */
    protected function storeDataSet($idProductAbstract, array $productOptions, array $productAbstractOptionStorageEntities = []): void
    {
        $storePrices = [];
        foreach ($this->stores as $store) {
            $productAbstractOptionStorageTransfers = $this->getProductOptionGroupStorageTransfers($productOptions, $store->getIdStore());
            if (!empty($productAbstractOptionStorageTransfers->getArrayCopy())) {
                $storePrices[$store->getName()] = $productAbstractOptionStorageTransfers;
            }
        }

        foreach ($storePrices as $store => $productOptionGroupStorageTransfers) {
            if (isset($productAbstractOptionStorageEntities[$store])) {
                $spyProductAbstractOptionStorageEntity = $productAbstractOptionStorageEntities[$store];
                unset($productAbstractOptionStorageEntities[$store]);
            } else {
                $spyProductAbstractOptionStorageEntity = new SpyProductAbstractOptionStorage();
            }

            $productAbstractOptionStorageTransfer = new ProductAbstractOptionStorageTransfer();
            $productAbstractOptionStorageTransfer->setIdProductAbstract($idProductAbstract);
            $productAbstractOptionStorageTransfer->setProductOptionGroups($productOptionGroupStorageTransfers);

            $spyProductAbstractOptionStorageEntity->setFkProductAbstract($idProductAbstract);
            $spyProductAbstractOptionStorageEntity->setData($productAbstractOptionStorageTransfer->toArray());
            $spyProductAbstractOptionStorageEntity->setStore($store);
            $spyProductAbstractOptionStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
            $spyProductAbstractOptionStorageEntity->save();
        }

        $this->deleteStorageData($productAbstractOptionStorageEntities);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    protected function findProductOptionAbstractEntities(array $productAbstractIds): array
    {
        return $this->queryContainer->queryProductOptionsByProductAbstractIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLocalizedEntities(array $productAbstractIds): array
    {
        return $this->queryContainer->queryProductAbstractLocalizedByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductOptionStorage\Persistence\SpyProductAbstractOptionStorage[][]
     */
    protected function findProductStorageOptionEntitiesByProductAbstractIds(array $productAbstractIds): array
    {
        $productAbstractOptionStorageEntities = $this->queryContainer->queryProductAbstractOptionStorageByIds($productAbstractIds)->find();
        $productAbstractOptionStorageEntitiesByIdAndStore = [];
        foreach ($productAbstractOptionStorageEntities as $productAbstractOptionStorageEntity) {
            $productAbstractOptionStorageEntitiesByIdAndStore[$productAbstractOptionStorageEntity->getFkProductAbstract()][$productAbstractOptionStorageEntity->getStore()] = $productAbstractOptionStorageEntity;
        }

        return $productAbstractOptionStorageEntitiesByIdAndStore;
    }

    /**
     * @param array $productOptions
     * @param int $idStore
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductOptionGroupStorageTransfer[]
     */
    protected function getProductOptionGroupStorageTransfers(array $productOptions, $idStore): ArrayObject
    {
        $productOptionGroupStorageTransfers = new ArrayObject();
        foreach ($productOptions as $productOption) {
            $productOptionGroupStorageTransfer = new ProductOptionGroupStorageTransfer();
            $productOptionGroupStorageTransfer->setName($productOption['SpyProductOptionGroup']['name']);
            $hasPriceValues = false;
            foreach ($productOption['SpyProductOptionGroup']['SpyProductOptionValues'] as $productOptionValue) {
                $prices = $this->getPrices($productOptionValue['ProductOptionValuePrices'], $idStore);
                if (!empty($prices)) {
                    $productOptionGroupStorageTransfer->addProductOptionValue((new ProductOptionValueStorageTransfer())->setIdProductOptionValue($productOptionValue['id_product_option_value'])
                        ->setSku($productOptionValue['sku'])
                        ->setPrices($prices)
                        ->setValue($productOptionValue['value']));

                    $hasPriceValues = true;
                }
            }
            if ($hasPriceValues) {
                $productOptionGroupStorageTransfers[] = $productOptionGroupStorageTransfer;
            }
        }

        return $productOptionGroupStorageTransfers;
    }

    /**
     * @param array $prices
     * @param int $idStore
     *
     * @return array
     */
    protected function getPrices(array $prices, $idStore): array
    {
        $moneyValueCollection = $this->transformPriceEntityCollectionToMoneyValueTransferCollection($prices);
        $moneyValueCollectionWithSpecificStore = new ArrayObject();
        foreach ($moneyValueCollection as $item) {
            if ($item['fkStore'] === $idStore) {
                $moneyValueCollectionWithSpecificStore->append($item);
            }
        }

        $priceResponse = $this->productOptionFacade->getAllProductOptionValuePrices(
            (new ProductOptionValueStorePricesRequestTransfer())->setPrices($moneyValueCollectionWithSpecificStore)
        );

        return $priceResponse->getStorePrices();
    }

    /**
     * @param array $prices
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[]
     */
    protected function transformPriceEntityCollectionToMoneyValueTransferCollection(array $prices): ArrayObject
    {
        $moneyValueCollection = new ArrayObject();
        foreach ($prices as $price) {
            $moneyValueCollection->append(
                (new MoneyValueTransfer())
                    ->fromArray($price, true)
                    ->setNetAmount($price['net_price'])
                    ->setGrossAmount($price['gross_price'])
            );
        }

        return $moneyValueCollection;
    }
}
