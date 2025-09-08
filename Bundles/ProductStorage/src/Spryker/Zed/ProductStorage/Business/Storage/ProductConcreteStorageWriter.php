<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Business\Storage;

use Generated\Shared\Transfer\ProductConcreteStorageTransfer;
use Generated\Shared\Transfer\RawProductAttributesTransfer;
use Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage;
use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface;
use Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface;
use Spryker\Zed\Propel\Persistence\BatchProcessor\ActiveRecordBatchProcessorTrait;

class ProductConcreteStorageWriter implements ProductConcreteStorageWriterInterface
{
    use ActiveRecordBatchProcessorTrait;

    /**
     * @var string
     */
    public const COL_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';

    /**
     * @var string
     */
    public const COL_FK_PRODUCT = 'fk_product';

    /**
     * @var string
     */
    public const CONCRETE_DESCRIPTION = 'description';

    /**
     * @var string
     */
    public const ABSTRACT_DESCRIPTION = 'abstract_description';

    /**
     * @var string
     */
    public const ABSTRACT_ATTRIBUTES = 'abstract_attributes';

    /**
     * @var string
     */
    public const CONCRETE_ATTRIBUTES = 'attributes';

    /**
     * @var string
     */
    public const PRODUCT_CONCRETE_LOCALIZED_ENTITY = 'PRODUCT_CONCRETE_LOCALIZED_ENTITY';

    /**
     * @var string
     */
    public const PRODUCT_CONCRETE_STORAGE_ENTITY = 'PRODUCT_CONCRETE_STORAGE_ENTITY';

    /**
     * @var string
     */
    public const LOCALE_NAME = 'LOCALE_NAME';

    /**
     * @var \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface
     */
    protected $queryContainer;

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
     * @var array<\Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductConcreteStorageCollectionExpanderPluginInterface>
     */
    protected $productConcreteStorageCollectionExpanderPlugins;

    /**
     * @var array<\Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductConcreteStorageCollectionFilterPluginInterface>
     */
    protected $productConcreteStorageCollectionFilterPlugins;

    /**
     * @param \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface $productFacade
     * @param \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     * @param array<\Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductConcreteStorageCollectionExpanderPluginInterface> $productConcreteStorageCollectionExpanderPlugins
     * @param array<\Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductConcreteStorageCollectionFilterPluginInterface> $productConcreteStorageCollectionFilterPlugins
     */
    public function __construct(
        ProductStorageToProductInterface $productFacade,
        ProductStorageQueryContainerInterface $queryContainer,
        $isSendingToQueue,
        array $productConcreteStorageCollectionExpanderPlugins,
        array $productConcreteStorageCollectionFilterPlugins
    ) {
        $this->productFacade = $productFacade;
        $this->queryContainer = $queryContainer;
        $this->isSendingToQueue = $isSendingToQueue;
        $this->productConcreteStorageCollectionExpanderPlugins = $productConcreteStorageCollectionExpanderPlugins;
        $this->productConcreteStorageCollectionFilterPlugins = $productConcreteStorageCollectionFilterPlugins;
    }

    /**
     * @param array<int> $productIds
     *
     * @return void
     */
    public function publish(array $productIds)
    {
        $productConcreteLocalizedEntities = $this->findProductConcreteLocalizedEntities($productIds);
        $productConcreteStorageEntities = $this->findProductConcreteStorageEntities($productIds);

        if (!$productConcreteLocalizedEntities) {
            $this->deleteProductConcreteStorageEntities($productConcreteStorageEntities);

            return;
        }

        $this->storeData($productConcreteLocalizedEntities, $productConcreteStorageEntities);
        $this->commit();
    }

    /**
     * @param array<int> $productIds
     *
     * @return void
     */
    public function unpublish(array $productIds)
    {
        $productConcreteStorageEntities = $this->findProductConcreteStorageEntities($productIds);

        $this->deleteProductConcreteStorageEntities($productConcreteStorageEntities);
        $this->commit();
    }

    /**
     * @param array<\Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage> $productConcreteStorageEntities
     *
     * @return void
     */
    protected function deleteProductConcreteStorageEntities(array $productConcreteStorageEntities)
    {
        foreach ($productConcreteStorageEntities as $productConcreteStorageEntity) {
            $this->remove($productConcreteStorageEntity);
        }
    }

    /**
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage $productConcreteStorageEntity
     *
     * @return void
     */
    protected function deletedProductConcreteSorageEntity(SpyProductConcreteStorage $productConcreteStorageEntity)
    {
        if (!$productConcreteStorageEntity->isNew()) {
            $this->remove($productConcreteStorageEntity);
        }
    }

    /**
     * @param array $productConcreteLocalizedEntities
     * @param array<\Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage> $productConcreteStorageEntities
     *
     * @return void
     */
    protected function storeData(array $productConcreteLocalizedEntities, array $productConcreteStorageEntities)
    {
        $pairedEntities = $this->pairProductConcreteLocalizedEntitiesWithProductConcreteStorageEntities(
            $productConcreteLocalizedEntities,
            $productConcreteStorageEntities,
        );

        $productConcreteStorageTransfers = $this->getProductConcreteStorageTransfers($pairedEntities);
        $productConcreteStorageTransfers = $this->expandProductConcreteStorageCollection($productConcreteStorageTransfers);

        $filteredProductConcreteStorageTransfers = $this->executeProductConcreteStorageCollectionFilterPlugins(
            $productConcreteStorageTransfers,
        );
        $productConcreteStorageTransfersIndexedByIdProductConcrete = $this->getProductConcreteStorageTransfersIndexedByIdProductConcrete(
            $filteredProductConcreteStorageTransfers,
        );

        foreach ($pairedEntities as $index => $pair) {
            $productConcreteLocalizedEntity = $pair[static::PRODUCT_CONCRETE_LOCALIZED_ENTITY];
            $productConcreteStorageEntity = $pair[static::PRODUCT_CONCRETE_STORAGE_ENTITY];

            if ($productConcreteLocalizedEntity === null || !$this->isActive($productConcreteLocalizedEntity)) {
                $this->deletedProductConcreteSorageEntity($productConcreteStorageEntity);

                continue;
            }

            $idProduct = $productConcreteLocalizedEntity[static::COL_FK_PRODUCT];
            $productConcreteStorageTransfer = $productConcreteStorageTransfersIndexedByIdProductConcrete[$idProduct] ?? null;

            if ($productConcreteStorageTransfer === null) {
                $this->deletedProductConcreteSorageEntity($productConcreteStorageEntity);

                continue;
            }

            $this->storeProductConcreteStorageEntity(
                $productConcreteStorageTransfers[$index],
                $productConcreteStorageEntity,
                $pair[static::LOCALE_NAME],
            );
        }
    }

    /**
     * - Returns a paired array with all provided entities.
     * - ProductConcreteLocalizedEntities without ProductConcreteStorageEntity are paired with a newly created ProductConcreteStorageEntity.
     * - ProductConcreteStorageEntities without ProductConcreteLocalizedEntity (left outs) are paired with NULL.
     *
     * @param array<array<string, mixed>> $productConcreteLocalizedEntities
     * @param array<\Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage> $productConcreteStorageEntities
     *
     * @return array
     */
    protected function pairProductConcreteLocalizedEntitiesWithProductConcreteStorageEntities(
        array $productConcreteLocalizedEntities,
        array $productConcreteStorageEntities
    ) {
        $mappedProductConcreteStorageEntities = $this->mapProductConcreteStorageEntities($productConcreteStorageEntities);

        $pairs = [];
        foreach ($productConcreteLocalizedEntities as $productConcreteLocalizedEntity) {
            [$pairs, $mappedProductConcreteStorageEntities] = $this->pairProductConcreteLocalizedEntityWithProductConcreteStorageEntitiesByStoresAndLocale(
                $productConcreteLocalizedEntity[static::COL_FK_PRODUCT],
                $productConcreteLocalizedEntity['Locale']['locale_name'],
                $productConcreteLocalizedEntity,
                $mappedProductConcreteStorageEntities,
                $pairs,
            );
        }

        $pairs = $this->pairRemainingProductConcreteStorageEntities($mappedProductConcreteStorageEntities, $pairs);

        return $pairs;
    }

    /**
     * @param int $idProduct
     * @param string $localeName
     * @param array $productConcreteLocalizedEntity
     * @param array $mappedProductConcreteStorageEntities
     * @param array $pairs
     *
     * @return array
     */
    protected function pairProductConcreteLocalizedEntityWithProductConcreteStorageEntitiesByStoresAndLocale(
        $idProduct,
        $localeName,
        $productConcreteLocalizedEntity,
        array $mappedProductConcreteStorageEntities,
        array $pairs
    ) {
        $productConcreteStorageEntity = $mappedProductConcreteStorageEntities[$idProduct][$localeName] ??
            new SpyProductConcreteStorage();

        $pairs[] = [
            static::PRODUCT_CONCRETE_LOCALIZED_ENTITY => $productConcreteLocalizedEntity,
            static::PRODUCT_CONCRETE_STORAGE_ENTITY => $productConcreteStorageEntity,
            static::LOCALE_NAME => $localeName,
        ];

        unset($mappedProductConcreteStorageEntities[$idProduct][$localeName]);

        return [$pairs, $mappedProductConcreteStorageEntities];
    }

    /**
     * @param array $mappedProductConcreteStorageEntities
     * @param array $pairs
     *
     * @return array
     */
    protected function pairRemainingProductConcreteStorageEntities(array $mappedProductConcreteStorageEntities, array $pairs)
    {
        array_walk_recursive($mappedProductConcreteStorageEntities, function (SpyProductConcreteStorage $productConcreteStorageEntity) use (&$pairs) {
            $pairs[] = [
                static::PRODUCT_CONCRETE_LOCALIZED_ENTITY => null,
                static::PRODUCT_CONCRETE_STORAGE_ENTITY => $productConcreteStorageEntity,
                static::LOCALE_NAME => $productConcreteStorageEntity->getLocale(),
            ];
        });

        return $pairs;
    }

    /**
     * @param array $productConcreteLocalizedEntity
     *
     * @return bool
     */
    protected function isActive(array $productConcreteLocalizedEntity)
    {
        return (bool)$productConcreteLocalizedEntity['SpyProduct']['is_active'];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteStorageTransfer $productConcreteStorageTransfer
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage $productConcreteStorageEntity
     * @param string $localeName
     *
     * @return void
     */
    protected function storeProductConcreteStorageEntity(
        ProductConcreteStorageTransfer $productConcreteStorageTransfer,
        SpyProductConcreteStorage $productConcreteStorageEntity,
        $localeName
    ) {
        $productConcreteStorageEntity
            ->setFkProduct($productConcreteStorageTransfer->getIdProductConcrete())
            ->setData($productConcreteStorageTransfer->toArray())
            ->setLocale($localeName)
            ->setIsSendingToQueue($this->isSendingToQueue);

        $this->persist($productConcreteStorageEntity);
    }

    /**
     * @param array $productConcreteLocalizedEntity
     *
     * @return \Generated\Shared\Transfer\ProductConcreteStorageTransfer
     */
    protected function mapToProductConcreteStorageTransfer(array $productConcreteLocalizedEntity)
    {
        $attributes = $this->getConcreteAttributes($productConcreteLocalizedEntity);

        $spyProductConcreteEntityArray = $productConcreteLocalizedEntity['SpyProduct'];
        unset($productConcreteLocalizedEntity['attributes']);
        unset($spyProductConcreteEntityArray['attributes']);

        $productStorageTransfer = (new ProductConcreteStorageTransfer())
            ->fromArray($productConcreteLocalizedEntity, true)
            ->fromArray($spyProductConcreteEntityArray, true)
            ->setIdProductConcrete($productConcreteLocalizedEntity[static::COL_FK_PRODUCT])
            ->setIdProductAbstract($spyProductConcreteEntityArray[static::COL_FK_PRODUCT_ABSTRACT])
            ->setDescription($this->getDescription($productConcreteLocalizedEntity))
            ->setAttributes($attributes)
            ->setSuperAttributesDefinition($this->getSuperAttributeKeys($attributes));

        return $productStorageTransfer;
    }

    /**
     * @param array $productConcreteLocalizedEntity
     *
     * @return array
     */
    protected function getConcreteAttributes(array $productConcreteLocalizedEntity)
    {
        $abstractAttributes = $this->productFacade->decodeProductAttributes($productConcreteLocalizedEntity['SpyProduct']['SpyProductAbstract']['attributes']);
        $concreteAttributes = $this->productFacade->decodeProductAttributes($productConcreteLocalizedEntity['SpyProduct']['attributes']);

        $abstractLocalizedAttributes = $this->productFacade->decodeProductAttributes($productConcreteLocalizedEntity[static::ABSTRACT_ATTRIBUTES]);
        $concreteLocalizedAttributes = $this->productFacade->decodeProductAttributes($productConcreteLocalizedEntity[static::CONCRETE_ATTRIBUTES]);

        $rawProductAttributesTransfer = (new RawProductAttributesTransfer())
            ->setAbstractAttributes($abstractAttributes)
            ->setAbstractLocalizedAttributes($abstractLocalizedAttributes)
            ->setConcreteAttributes($concreteAttributes)
            ->setConcreteLocalizedAttributes($concreteLocalizedAttributes);

        return $this->productFacade->combineRawProductAttributes($rawProductAttributesTransfer);
    }

    /**
     * @param array $productConcreteLocalizedEntity
     *
     * @return string
     */
    protected function getDescription(array $productConcreteLocalizedEntity)
    {
        $description = trim($productConcreteLocalizedEntity[static::CONCRETE_DESCRIPTION]);
        if (!$description) {
            $description = trim($productConcreteLocalizedEntity[static::ABSTRACT_DESCRIPTION]);
        }

        return $description;
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

        if (!$superAttributes->getData()) {
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
     * @param array<int> $productConcreteIds
     *
     * @return array
     */
    protected function findProductConcreteLocalizedEntities(array $productConcreteIds)
    {
        return $this->queryContainer->queryProductConcreteByIds($productConcreteIds)->find()->getData();
    }

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array
     */
    protected function findProductConcreteStorageEntities(array $productConcreteIds)
    {
        return $this->queryContainer->queryProductConcreteStorageByIds($productConcreteIds)->find()->getArrayCopy();
    }

    /**
     * @param array<\Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage> $productConcreteStorageEntities
     *
     * @return array
     */
    protected function mapProductConcreteStorageEntities(array $productConcreteStorageEntities)
    {
        $mappedProductConcreteStorageEntities = [];
        foreach ($productConcreteStorageEntities as $entity) {
            $mappedProductConcreteStorageEntities[$entity->getFkProduct()][$entity->getLocale()] = $entity;
        }

        return $mappedProductConcreteStorageEntities;
    }

    /**
     * @param array $pairedEntities
     *
     * @return array<string, \Generated\Shared\Transfer\ProductConcreteStorageTransfer>
     */
    protected function getProductConcreteStorageTransfers(
        array $pairedEntities
    ): array {
        $productConcreteStorageTransfers = [];

        foreach ($pairedEntities as $index => $pair) {
            $productConcreteLocalizedEntity = $pair[static::PRODUCT_CONCRETE_LOCALIZED_ENTITY];

            $productConcreteStorageTransfers[$index] = $this->mapToProductConcreteStorageTransfer($productConcreteLocalizedEntity);
        }

        return $productConcreteStorageTransfers;
    }

    /**
     * @param array $productConcreteStorageCollection
     *
     * @return array<string, \Generated\Shared\Transfer\ProductConcreteStorageTransfer>
     */
    protected function expandProductConcreteStorageCollection(
        array $productConcreteStorageCollection
    ) {
        foreach ($this->productConcreteStorageCollectionExpanderPlugins as $concreteStorageCollectionExpanderPlugin) {
            $productConcreteStorageCollection = $concreteStorageCollectionExpanderPlugin->expand($productConcreteStorageCollection);
        }

        return $productConcreteStorageCollection;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer> $productConcreteStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer>
     */
    protected function executeProductConcreteStorageCollectionFilterPlugins(array $productConcreteStorageTransfers): array
    {
        foreach ($this->productConcreteStorageCollectionFilterPlugins as $productConcreteStorageCollectionFilterPlugin) {
            $productConcreteStorageTransfers = $productConcreteStorageCollectionFilterPlugin->filter($productConcreteStorageTransfers);
        }

        return $productConcreteStorageTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer> $productConcreteStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer>
     */
    protected function getProductConcreteStorageTransfersIndexedByIdProductConcrete(
        array $productConcreteStorageTransfers
    ): array {
        $indexedProductConcreteStorageTransfers = [];
        foreach ($productConcreteStorageTransfers as $productConcreteStorageTransfer) {
            $idProductConcrete = $productConcreteStorageTransfer->getIdProductConcreteOrFail();
            $indexedProductConcreteStorageTransfers[$idProductConcrete] = $productConcreteStorageTransfer;
        }

        return $indexedProductConcreteStorageTransfers;
    }
}
