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
use Generated\Shared\Transfer\ProductOptionTransfer;
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
     * @deprecated Use {@link \Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()} instead.
     *
     * @var bool
     */
    protected $isSendingToQueue;

    /**
     * @var array<\Generated\Shared\Transfer\StoreTransfer>
     */
    protected $stores = [];

    /**
     * @var array<\Spryker\Zed\ProductOptionStorageExtension\Dependency\Plugin\ProductOptionCollectionFilterPluginInterface>
     */
    protected $productOptionCollectionFilterPlugins = [];

    /**
     * @param \Spryker\Zed\ProductOptionStorage\Dependency\Facade\ProductOptionStorageToProductOptionFacadeInterface $productOptionFacade
     * @param \Spryker\Zed\ProductOptionStorage\Dependency\Facade\ProductOptionStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductOptionStorage\Persistence\ProductOptionStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     * @param array<\Spryker\Zed\ProductOptionStorageExtension\Dependency\Plugin\ProductOptionCollectionFilterPluginInterface> $productOptionCollectionFilterPlugins
     */
    public function __construct(
        ProductOptionStorageToProductOptionFacadeInterface $productOptionFacade,
        ProductOptionStorageToStoreFacadeInterface $storeFacade,
        ProductOptionStorageQueryContainerInterface $queryContainer,
        $isSendingToQueue,
        $productOptionCollectionFilterPlugins = []
    ) {
        $this->productOptionFacade = $productOptionFacade;
        $this->storeFacade = $storeFacade;
        $this->queryContainer = $queryContainer;
        $this->isSendingToQueue = $isSendingToQueue;
        $this->productOptionCollectionFilterPlugins = $productOptionCollectionFilterPlugins;
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $this->stores = $this->storeFacade->getAllStores();
        $productOptionEntities = $this->findProductOptionAbstractEntities($productAbstractIds);
        $producOptionTransfers = $this->mapProductOptionEntitiesToProductOptionTransfers($productOptionEntities);
        $filteredProducOptionTransfers = $this->executeProductOptionCollectionFilterPlugins($producOptionTransfers);
        $productOptions = $this->excludeFilteredProductOptions($productOptionEntities, $filteredProducOptionTransfers);

        $productAbstractOptionStorageEntities = $this->findProductStorageOptionEntitiesByProductAbstractIds($productAbstractIds);
        $productAbstractIdsToRemove = $this->getProductAbstractIdsToRemove(
            array_keys($productOptions),
            array_keys($productAbstractOptionStorageEntities),
        );

        if ($productAbstractIdsToRemove) {
            $deletableProductAbstractOptionStorageEntities = $this->filterProductAbstractOptionStorageEntitiesByProductAbstractIds(
                $productAbstractOptionStorageEntities,
                $productAbstractIdsToRemove,
            );

            $this->deleteProductAbstractOptionStorageEntities($deletableProductAbstractOptionStorageEntities);
        }

        $this->storeData($productAbstractOptionStorageEntities, $productOptions);
    }

    /**
     * @param array<int> $productAbstractIds
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
     * @param array<\Orm\Zed\ProductOptionStorage\Persistence\SpyProductAbstractOptionStorage> $deletableProductAbstractOptionStorageEntities
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
     * @param array<\Orm\Zed\ProductOptionStorage\Persistence\SpyProductAbstractOptionStorage[]> $productAbstractOptionStorageEntities
     * @param array<int> $productAbstractIds
     *
     * @return array<\Orm\Zed\ProductOptionStorage\Persistence\SpyProductAbstractOptionStorage>
     */
    protected function filterProductAbstractOptionStorageEntitiesByProductAbstractIds(
        array $productAbstractOptionStorageEntities,
        array $productAbstractIds
    ): array {
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
     * @param array<\Orm\Zed\ProductOptionStorage\Persistence\SpyProductAbstractOptionStorage[]> $productAbstractOptionStorageEntities
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
     * @param array<\Orm\Zed\ProductOptionStorage\Persistence\SpyProductAbstractOptionStorage[]> $productAbstractOptionStorageEntities
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
     * @param array<int> $productAbstractIds
     *
     * @return array
     */
    protected function findProductOptionAbstractEntities(array $productAbstractIds): array
    {
        return $this->queryContainer->queryProductOptionsByProductAbstractIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLocalizedEntities(array $productAbstractIds): array
    {
        return $this->queryContainer->queryProductAbstractLocalizedByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Orm\Zed\ProductOptionStorage\Persistence\SpyProductAbstractOptionStorage[]>
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
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductOptionGroupStorageTransfer>
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
            (new ProductOptionValueStorePricesRequestTransfer())->setPrices($moneyValueCollectionWithSpecificStore),
        );

        return $priceResponse->getStorePrices();
    }

    /**
     * @param array $prices
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\MoneyValueTransfer>
     */
    protected function transformPriceEntityCollectionToMoneyValueTransferCollection(array $prices): ArrayObject
    {
        $moneyValueCollection = new ArrayObject();
        foreach ($prices as $price) {
            $moneyValueCollection->append(
                (new MoneyValueTransfer())
                    ->fromArray($price, true)
                    ->setNetAmount($price['net_price'])
                    ->setGrossAmount($price['gross_price']),
            );
        }

        return $moneyValueCollection;
    }

    /**
     * @param array<int> $storedProductAbstractIds
     * @param array<int> $productAbstractIdsFromStorage
     *
     * @return array<int>
     */
    protected function getProductAbstractIdsToRemove(
        array $storedProductAbstractIds,
        array $productAbstractIdsFromStorage
    ): array {
        return array_diff($productAbstractIdsFromStorage, $storedProductAbstractIds);
    }

    /**
     * @param array $productOptionEntities
     * @param array<\Generated\Shared\Transfer\ProductOptionTransfer> $producOptionTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductOptionTransfer>
     */
    protected function mapProductOptionEntitiesToProductOptionTransfers(
        array $productOptionEntities,
        array $producOptionTransfers = []
    ): array {
        foreach ($productOptionEntities as $productOptionEntity) {
            $producOptionTransfers[] = (new ProductOptionTransfer())
                ->setIdGroup($productOptionEntity['fk_product_option_group']);
        }

        return $producOptionTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductOptionTransfer> $producOptionTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductOptionTransfer>
     */
    protected function executeProductOptionCollectionFilterPlugins(array $producOptionTransfers): array
    {
        foreach ($this->productOptionCollectionFilterPlugins as $productOptionCollectionFilterPlugin) {
            $producOptionTransfers = $productOptionCollectionFilterPlugin->filter($producOptionTransfers);
        }

        return $producOptionTransfers;
    }

    /**
     * @param array $productOptionEntities
     * @param array<\Generated\Shared\Transfer\ProductOptionTransfer> $filteredProducOptionTransfers
     *
     * @return array
     */
    protected function excludeFilteredProductOptions(
        array $productOptionEntities,
        array $filteredProducOptionTransfers
    ): array {
        $productOptions = [];

        foreach ($productOptionEntities as $productOptionEntity) {
            foreach ($filteredProducOptionTransfers as $filteredProducOptionTransfer) {
                if ($filteredProducOptionTransfer->getIdGroup() === $productOptionEntity['fk_product_option_group']) {
                    $productOptions[$productOptionEntity['fk_product_abstract']][] = $productOptionEntity;

                    break;
                }
            }
        }

        return $productOptions;
    }
}
