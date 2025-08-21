<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Business\Storage;

use Generated\Shared\Transfer\ProductAbstractStorageTransfer;
use Generated\Shared\Transfer\RawProductAttributesTransfer;
use Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage;
use Spryker\Zed\ProductStorage\Business\Attribute\AttributeMapInterface;
use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface;
use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToStoreFacadeInterface;
use Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface;
use Spryker\Zed\ProductStorage\Persistence\ProductStorageRepositoryInterface;
use Spryker\Zed\Propel\Persistence\BatchProcessor\ActiveRecordBatchProcessorTrait;

class ProductAbstractStorageWriter implements ProductAbstractStorageWriterInterface
{
    use ActiveRecordBatchProcessorTrait;

    /**
     * @var string
     */
    public const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var string
     */
    public const COL_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';

    /**
     * @var string
     */
    public const COL_FK_LOCALE = 'fk_locale';

    /**
     * @var string
     */
    public const PRODUCT_ABSTRACT_LOCALIZED_ENTITY = 'PRODUCT_ABSTRACT_LOCALIZED_ENTITY';

    /**
     * @var string
     */
    public const PRODUCT_ABSTRACT_STORAGE_ENTITY = 'PRODUCT_ABSTRACT_STORAGE_ENTITY';

    /**
     * @var string
     */
    public const LOCALE_NAME = 'LOCALE_NAME';

    /**
     * @var string
     */
    public const STORE_NAME = 'STORE_NAME';

    /**
     * @var string
     */
    protected const COL_PRODUCT_COUNT = 'productCount';

    /**
     * @var \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductStorage\Business\Attribute\AttributeMapInterface
     */
    protected $attributeMap;

    /**
     * @var \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\ProductStorage\Persistence\ProductStorageRepositoryInterface
     */
    protected ProductStorageRepositoryInterface $productStorageRepository;

    /**
     * @deprecated Use {@link \Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()} instead.
     *
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @var array Array keys are super attribute keys, values are "true" constants.
     */
    protected $superAttributeKeyBuffer = [];

    /**
     * @var array<\Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductAbstractStorageExpanderPluginInterface>
     */
    protected $productAbstractStorageExpanderPlugins = [];

    /**
     * @var array<\Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductAbstractStorageCollectionFilterPluginInterface>
     */
    protected $productAbstractStorageCollectionFilterPlugins = [];

    /**
     * @var array<int, bool>
     */
    protected static array $activeConcretesInAbstractMap = [];

    /**
     * @param \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface $productFacade
     * @param \Spryker\Zed\ProductStorage\Business\Attribute\AttributeMapInterface $attributeMap
     * @param \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductStorage\Persistence\ProductStorageRepositoryInterface $productStorageRepository
     * @param bool $isSendingToQueue
     * @param array<\Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductAbstractStorageExpanderPluginInterface> $productAbstractStorageExpanderPlugins
     * @param array<\Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductAbstractStorageCollectionFilterPluginInterface> $productAbstractStorageCollectionFilterPlugins
     */
    public function __construct(
        ProductStorageToProductInterface $productFacade,
        AttributeMapInterface $attributeMap,
        ProductStorageQueryContainerInterface $queryContainer,
        ProductStorageToStoreFacadeInterface $storeFacade,
        ProductStorageRepositoryInterface $productStorageRepository,
        $isSendingToQueue,
        array $productAbstractStorageExpanderPlugins,
        array $productAbstractStorageCollectionFilterPlugins
    ) {
        $this->productFacade = $productFacade;
        $this->storeFacade = $storeFacade;
        $this->attributeMap = $attributeMap;
        $this->queryContainer = $queryContainer;
        $this->isSendingToQueue = $isSendingToQueue;
        $this->productAbstractStorageExpanderPlugins = $productAbstractStorageExpanderPlugins;
        $this->productAbstractStorageCollectionFilterPlugins = $productAbstractStorageCollectionFilterPlugins;
        $this->productStorageRepository = $productStorageRepository;
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $productAbstractLocalizedEntities = $this->productStorageRepository->getProductAbstractsByIds($productAbstractIds);
        $productAbstractStorageEntities = $this->findProductAbstractStorageEntities($productAbstractIds);

        if (!$productAbstractLocalizedEntities) {
            $this->deleteProductAbstractStorageEntities($productAbstractStorageEntities);

            return;
        }

        $this->storeData($productAbstractLocalizedEntities, $productAbstractStorageEntities);
        $this->commit();
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $spyProductStorageEntities = $this->findProductAbstractStorageEntities($productAbstractIds);

        $this->deleteProductAbstractStorageEntities($spyProductStorageEntities);
        $this->commit();
    }

    /**
     * @param array<\Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage> $productAbstractStorageEntities
     *
     * @return void
     */
    protected function deleteProductAbstractStorageEntities(array $productAbstractStorageEntities)
    {
        foreach ($productAbstractStorageEntities as $productAbstractStorageEntity) {
            $this->remove($productAbstractStorageEntity);
        }
    }

    /**
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage $productAbstractStorageEntity
     *
     * @return void
     */
    protected function deleteProductAbstractStorageEntity(SpyProductAbstractStorage $productAbstractStorageEntity)
    {
        if (!$productAbstractStorageEntity->isNew()) {
            $this->remove($productAbstractStorageEntity);
        }
    }

    /**
     * @param array $productAbstractLocalizedEntities
     * @param array<\Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage> $productAbstractStorageEntities
     *
     * @return void
     */
    protected function storeData(array $productAbstractLocalizedEntities, array $productAbstractStorageEntities)
    {
        $pairedEntities = $this->pairProductAbstractLocalizedEntitiesWithProductAbstractStorageEntities(
            $productAbstractLocalizedEntities,
            $productAbstractStorageEntities,
        );

        $attributeMapBulk = $this->attributeMap->generateAttributeMapBulk(
            array_column($productAbstractLocalizedEntities, static::COL_FK_PRODUCT_ABSTRACT),
            array_column($productAbstractLocalizedEntities, static::COL_FK_LOCALE),
        );

        $productAbstractStorageTransfers = $this->mapProductAbstractLocalizedEntitiesToProductAbstractStorageTransfers(
            $productAbstractLocalizedEntities,
            $attributeMapBulk,
        );
        $productAbstractStorageTransfers = $this->executeProductAbstractStorageFilterPlugins($productAbstractStorageTransfers);
        $indexedProductAbstractStorageTransfers = $this->indexProductAbstractStorageTransfersByIdProductAbstract($productAbstractStorageTransfers);
        $idProductAbstracts = [];

        foreach ($pairedEntities as $pair) {
            $productAbstractLocalizedEntity = $pair[static::PRODUCT_ABSTRACT_LOCALIZED_ENTITY];

            if ($productAbstractLocalizedEntity === null) {
                continue;
            }

            $idProductAbstracts[] = $productAbstractLocalizedEntity[static::COL_FK_PRODUCT_ABSTRACT];
        }

        $concreteProductCountMap = $this->productStorageRepository->getProductConcretesCountByIdProductAbstracts($idProductAbstracts);

        foreach ($pairedEntities as $pair) {
            $productAbstractLocalizedEntity = $pair[static::PRODUCT_ABSTRACT_LOCALIZED_ENTITY];
            $productAbstractStorageEntity = $pair[static::PRODUCT_ABSTRACT_STORAGE_ENTITY];

            if ($productAbstractLocalizedEntity === null || !$this->isActive($productAbstractLocalizedEntity, $concreteProductCountMap)) {
                $this->deleteProductAbstractStorageEntity($productAbstractStorageEntity);

                continue;
            }

            $idProductAbstract = $productAbstractLocalizedEntity[static::COL_FK_PRODUCT_ABSTRACT];
            $productAbstractStorageTransfer = $indexedProductAbstractStorageTransfers[$idProductAbstract] ?? null;

            if ($productAbstractStorageTransfer === null) {
                $this->deleteProductAbstractStorageEntity($productAbstractStorageEntity);

                continue;
            }

            $this->storeProductAbstractStorageEntity(
                $productAbstractLocalizedEntity,
                $productAbstractStorageEntity,
                $pair[static::STORE_NAME],
                $pair[static::LOCALE_NAME],
                $attributeMapBulk,
            );
        }
    }

    /**
     * - Returns a paired array with all provided entities.
     * - ProductAbstractLocalizedEntities without ProductAbstractStorageEntity are paired with a newly created ProductAbstractStorageEntity.
     * - ProductAbstractStorageEntity without ProductAbstractLocalizedEntities (left outs) are paired with NULL.
     * - ProductAbstractLocalizedEntities are paired multiple times per store.
     *
     * @param array<array<string, mixed>> $productAbstractLocalizedEntities
     * @param array<\Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage> $productAbstractStorageEntities
     *
     * @return array
     */
    protected function pairProductAbstractLocalizedEntitiesWithProductAbstractStorageEntities(
        array $productAbstractLocalizedEntities,
        array $productAbstractStorageEntities
    ) {
        $mappedProductAbstractStorageEntities = $this->mapProductAbstractStorageEntities($productAbstractStorageEntities);

        $pairs = [];
        foreach ($productAbstractLocalizedEntities as $productAbstractLocalizedEntity) {
            [$pairs, $mappedProductAbstractStorageEntities] = $this->pairProductAbstractLocalizedEntitiesWithProductAbstractStorageEntitiesByStoresAndLocale(
                $productAbstractLocalizedEntity['SpyProductAbstract'][static::COL_ID_PRODUCT_ABSTRACT],
                $productAbstractLocalizedEntity['Locale']['locale_name'],
                $productAbstractLocalizedEntity['SpyProductAbstract']['SpyProductAbstractStores'],
                $productAbstractLocalizedEntity,
                $mappedProductAbstractStorageEntities,
                $pairs,
            );
        }

        $pairs = $this->pairRemainingProductAbstractStorageEntities($mappedProductAbstractStorageEntities, $pairs);

        return $pairs;
    }

    /**
     * @param int $idProduct
     * @param string $localeName
     * @param array $productAbstractStoreEntities
     * @param array $productAbstractLocalizedEntity
     * @param array $mappedProductAbstractStorageEntities
     * @param array $pairs
     *
     * @return array
     */
    protected function pairProductAbstractLocalizedEntitiesWithProductAbstractStorageEntitiesByStoresAndLocale(
        $idProduct,
        $localeName,
        array $productAbstractStoreEntities,
        array $productAbstractLocalizedEntity,
        array $mappedProductAbstractStorageEntities,
        array $pairs
    ) {
        foreach ($productAbstractStoreEntities as $productAbstractStoreEntity) {
            $storeName = $productAbstractStoreEntity['SpyStore']['name'];

            $productAbstractStorageEntity = $mappedProductAbstractStorageEntities[$idProduct][$storeName][$localeName] ??
                new SpyProductAbstractStorage();

            unset($mappedProductAbstractStorageEntities[$idProduct][$storeName][$localeName]);

            if (!$this->isValidStoreLocale($storeName, $localeName)) {
                continue;
            }

            $pairs[] = [
                static::PRODUCT_ABSTRACT_LOCALIZED_ENTITY => $productAbstractLocalizedEntity,
                static::PRODUCT_ABSTRACT_STORAGE_ENTITY => $productAbstractStorageEntity,
                static::LOCALE_NAME => $localeName,
                static::STORE_NAME => $storeName,
            ];
        }

        return [$pairs, $mappedProductAbstractStorageEntities];
    }

    /**
     * @param string $storeName
     * @param string $localeName
     *
     * @return bool
     */
    protected function isValidStoreLocale(string $storeName, string $localeName): bool
    {
        return in_array($localeName, $this->storeFacade->getStoreByName($storeName)->getAvailableLocaleIsoCodes());
    }

    /**
     * @param array $mappedProductAbstractStorageEntities
     * @param array $pairs
     *
     * @return array
     */
    protected function pairRemainingProductAbstractStorageEntities(array $mappedProductAbstractStorageEntities, array $pairs)
    {
        array_walk_recursive($mappedProductAbstractStorageEntities, function (SpyProductAbstractStorage $productAbstractStorageEntity) use (&$pairs) {
            $pairs[] = [
                static::PRODUCT_ABSTRACT_LOCALIZED_ENTITY => null,
                static::PRODUCT_ABSTRACT_STORAGE_ENTITY => $productAbstractStorageEntity,
                static::LOCALE_NAME => $productAbstractStorageEntity->getLocale(),
                static::STORE_NAME => $productAbstractStorageEntity->getStore(),
            ];
        });

        return $pairs;
    }

    /**
     * @param array $productAbstractLocalizedEntity
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage $spyProductStorageEntity
     * @param string $storeName
     * @param string $localeName
     * @param array<string, \Generated\Shared\Transfer\AttributeMapStorageTransfer> $attributeMapBulk
     *
     * @return void
     */
    protected function storeProductAbstractStorageEntity(
        array $productAbstractLocalizedEntity,
        SpyProductAbstractStorage $spyProductStorageEntity,
        $storeName,
        $localeName,
        array $attributeMapBulk = []
    ) {
        $productAbstractStorageTransfer = $this->mapToProductAbstractStorageTransfer(
            $productAbstractLocalizedEntity,
            new ProductAbstractStorageTransfer(),
            $attributeMapBulk,
        );

        $productAbstractStorageTransfer = $this->executeProductAbstractStorageExpanderPlugins($productAbstractStorageTransfer);

        $spyProductStorageEntity
            ->setFkProductAbstract($productAbstractLocalizedEntity['SpyProductAbstract'][static::COL_ID_PRODUCT_ABSTRACT])
            ->setData($productAbstractStorageTransfer->toArray())
            ->setStore($storeName)
            ->setLocale($localeName)
            ->setIsSendingToQueue($this->isSendingToQueue);

        $this->persist($spyProductStorageEntity);
    }

    /**
     * @param array $productAbstractLocalizedEntity
     * @param array<array<string, int>> $productConcreteCountMap
     *
     * @return bool
     */
    protected function isActive(array $productAbstractLocalizedEntity, array $productConcreteCountMap)
    {
        $idProductAbstract = $productAbstractLocalizedEntity[static::COL_FK_PRODUCT_ABSTRACT];

        foreach ($productConcreteCountMap as $item) {
            if ($item[static::COL_FK_PRODUCT_ABSTRACT] === $idProductAbstract) {
                return $item[static::COL_PRODUCT_COUNT] > 0;
            }
        }

        return false;
    }

    /**
     * @param array $productAbstractLocalizedEntities
     * @param array $attributeMapBulk
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractStorageTransfer>
     */
    protected function mapProductAbstractLocalizedEntitiesToProductAbstractStorageTransfers(
        array $productAbstractLocalizedEntities,
        array $attributeMapBulk = []
    ): array {
        $productAbstractStorageTransfers = [];

        foreach ($productAbstractLocalizedEntities as $productAbstractLocalizedEntity) {
            $productAbstractStorageTransfers[] = $this->mapToProductAbstractStorageTransfer(
                $productAbstractLocalizedEntity,
                new ProductAbstractStorageTransfer(),
                $attributeMapBulk,
            );
        }

        return $productAbstractStorageTransfers;
    }

    /**
     * @param array $productAbstractLocalizedEntity
     * @param \Generated\Shared\Transfer\ProductAbstractStorageTransfer $productAbstractStorageTransfer
     * @param array<string, \Generated\Shared\Transfer\AttributeMapStorageTransfer> $attributeMapBulk
     *
     * @return \Generated\Shared\Transfer\ProductAbstractStorageTransfer
     */
    protected function mapToProductAbstractStorageTransfer(
        array $productAbstractLocalizedEntity,
        ProductAbstractStorageTransfer $productAbstractStorageTransfer,
        array $attributeMapBulk = []
    ) {
        $attributes = $this->getProductAbstractAttributes($productAbstractLocalizedEntity);
        $attributeMap = $this->attributeMap->getConcreteProductsFromBulk(
            $productAbstractLocalizedEntity[static::COL_FK_PRODUCT_ABSTRACT],
            $productAbstractLocalizedEntity['Locale']['id_locale'],
            $attributeMapBulk,
        );
        $productAbstractEntity = $productAbstractLocalizedEntity['SpyProductAbstract'];

        unset($productAbstractLocalizedEntity['attributes']);
        unset($productAbstractEntity['attributes']);

        return $productAbstractStorageTransfer
            ->fromArray($productAbstractLocalizedEntity, true)
            ->fromArray($productAbstractEntity, true)
            ->setAttributes($attributes)
            ->setAttributeMap($attributeMap)
            ->setSuperAttributesDefinition($this->getSuperAttributeKeys($attributes));
    }

    /**
     * @param array $productAbstractLocalizedEntity
     *
     * @return array
     */
    protected function getProductAbstractAttributes(array $productAbstractLocalizedEntity)
    {
        $productAbstractDecodedAttributes = $this->productFacade->decodeProductAttributes(
            $productAbstractLocalizedEntity['SpyProductAbstract']['attributes'],
        );
        $productAbstractLocalizedDecodedAttributes = $this->productFacade->decodeProductAttributes(
            $productAbstractLocalizedEntity['attributes'],
        );

        $rawProductAttributesTransfer = (new RawProductAttributesTransfer())
            ->setAbstractAttributes($productAbstractDecodedAttributes)
            ->setAbstractLocalizedAttributes($productAbstractLocalizedDecodedAttributes);

        $attributes = $this->productFacade->combineRawProductAttributes($rawProductAttributesTransfer);

        $attributes = array_filter($attributes, function ($attributeKey) {
            return (bool)$attributeKey;
        }, ARRAY_FILTER_USE_KEY);

        return $attributes;
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function getSuperAttributeKeys(array $attributes)
    {
        if (!$this->superAttributeKeyBuffer) {
            $this->loadSuperAttributes();
        }

        return $this->filterSuperAttributeKeys($attributes);
    }

    /**
     * @return void
     */
    protected function loadSuperAttributes()
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Product\Persistence\SpyProductAttributeKey> $superAttributes */
        $superAttributes = $this->queryContainer
            ->queryProductAttributeKey()
            ->find();

        if ($superAttributes->getData() === []) {
            $this->superAttributeKeyBuffer[] = null;

            return;
        }

        foreach ($superAttributes as $attribute) {
            $this->superAttributeKeyBuffer[$attribute->getKey()] = true;
        }
    }

    /**
     * @param array $attributes Array keys are attribute keys.
     *
     * @return array<string>
     */
    protected function filterSuperAttributeKeys(array $attributes)
    {
        $superAttributes = array_intersect_key($attributes, $this->superAttributeKeyBuffer);

        return array_keys($superAttributes);
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage>
     */
    protected function findProductAbstractStorageEntities(array $productAbstractIds)
    {
        return $this->queryContainer->queryProductAbstractStorageByIds($productAbstractIds)->find()->getArrayCopy();
    }

    /**
     * @param array<\Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage> $productAbstractStorageEntities
     *
     * @return array
     */
    protected function mapProductAbstractStorageEntities(array $productAbstractStorageEntities)
    {
        $mappedProductAbstractStorageEntities = [];
        foreach ($productAbstractStorageEntities as $entity) {
            $mappedProductAbstractStorageEntities[$entity->getFkProductAbstract()][$entity->getStore()][$entity->getLocale()] = $entity;
        }

        return $mappedProductAbstractStorageEntities;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductAbstractStorageTransfer> $productAbstractStorageTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ProductAbstractStorageTransfer>
     */
    protected function indexProductAbstractStorageTransfersByIdProductAbstract(array $productAbstractStorageTransfers): array
    {
        $indexedProductAbstractStorageTransfers = [];

        foreach ($productAbstractStorageTransfers as $productAbstractStorageTransfer) {
            $idProductAbstract = $productAbstractStorageTransfer->getIdProductAbstractOrFail();

            $indexedProductAbstractStorageTransfers[$idProductAbstract] = $productAbstractStorageTransfer;
        }

        return $indexedProductAbstractStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractStorageTransfer $productAbstractStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractStorageTransfer
     */
    protected function executeProductAbstractStorageExpanderPlugins(
        ProductAbstractStorageTransfer $productAbstractStorageTransfer
    ): ProductAbstractStorageTransfer {
        foreach ($this->productAbstractStorageExpanderPlugins as $productAbstractStorageExpanderPlugin) {
            $productAbstractStorageTransfer = $productAbstractStorageExpanderPlugin->expand($productAbstractStorageTransfer);
        }

        return $productAbstractStorageTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductAbstractStorageTransfer> $productAbstractStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractStorageTransfer>
     */
    protected function executeProductAbstractStorageFilterPlugins(array $productAbstractStorageTransfers): array
    {
        foreach ($this->productAbstractStorageCollectionFilterPlugins as $productAbstractStorageFilterPlugin) {
            $productAbstractStorageTransfers = $productAbstractStorageFilterPlugin->filter($productAbstractStorageTransfers);
        }

        return $productAbstractStorageTransfers;
    }
}
