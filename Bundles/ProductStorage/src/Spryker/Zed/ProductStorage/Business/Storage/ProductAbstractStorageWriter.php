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

class ProductAbstractStorageWriter implements ProductAbstractStorageWriterInterface
{
    public const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    public const COL_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';
    public const COL_FK_LOCALE = 'fk_locale';

    public const PRODUCT_ABSTRACT_LOCALIZED_ENTITY = 'PRODUCT_ABSTRACT_LOCALIZED_ENTITY';
    public const PRODUCT_ABSTRACT_STORAGE_ENTITY = 'PRODUCT_ABSTRACT_STORAGE_ENTITY';
    public const LOCALE_NAME = 'LOCALE_NAME';
    public const STORE_NAME = 'STORE_NAME';

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
     * @deprecated Use `\Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()` instead.
     *
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @var array Array keys are super attribute keys, values are "true" constants.
     */
    protected $superAttributeKeyBuffer = [];

    /**
     * @param \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface $productFacade
     * @param \Spryker\Zed\ProductStorage\Business\Attribute\AttributeMapInterface $attributeMap
     * @param \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToStoreFacadeInterface $storeFacade
     * @param bool $isSendingToQueue
     */
    public function __construct(
        ProductStorageToProductInterface $productFacade,
        AttributeMapInterface $attributeMap,
        ProductStorageQueryContainerInterface $queryContainer,
        ProductStorageToStoreFacadeInterface $storeFacade,
        $isSendingToQueue
    ) {
        $this->productFacade = $productFacade;
        $this->storeFacade = $storeFacade;
        $this->attributeMap = $attributeMap;
        $this->queryContainer = $queryContainer;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $productAbstractLocalizedEntities = $this->findProductAbstractLocalizedEntities($productAbstractIds);
        $productAbstractStorageEntities = $this->findProductAbstractStorageEntities($productAbstractIds);

        if (!$productAbstractLocalizedEntities) {
            $this->deleteProductAbstractStorageEntities($productAbstractStorageEntities);

            return;
        }

        $this->storeData($productAbstractLocalizedEntities, $productAbstractStorageEntities);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $spyProductStorageEntities = $this->findProductAbstractStorageEntities($productAbstractIds);

        $this->deleteProductAbstractStorageEntities($spyProductStorageEntities);
    }

    /**
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage[] $productAbstractStorageEntities
     *
     * @return void
     */
    protected function deleteProductAbstractStorageEntities(array $productAbstractStorageEntities)
    {
        foreach ($productAbstractStorageEntities as $productAbstractStorageEntity) {
            $productAbstractStorageEntity->delete();
        }
    }

    /**
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage $productAbstractStorage
     *
     * @return void
     */
    protected function deleteProductAbstractStorageEntity(SpyProductAbstractStorage $productAbstractStorage)
    {
        if (!$productAbstractStorage->isNew()) {
            $productAbstractStorage->delete();
        }
    }

    /**
     * @param array $productAbstractLocalizedEntities
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage[] $productAbstractStorageEntities
     *
     * @return void
     */
    protected function storeData(array $productAbstractLocalizedEntities, array $productAbstractStorageEntities)
    {
        $pairedEntities = $this->pairProductAbstractLocalizedEntitiesWithProductAbstractStorageEntities(
            $productAbstractLocalizedEntities,
            $productAbstractStorageEntities
        );

        $attributeMapBulk = $this->attributeMap->generateAttributeMapBulk(
            array_column($productAbstractLocalizedEntities, static::COL_FK_PRODUCT_ABSTRACT),
            array_column($productAbstractLocalizedEntities, static::COL_FK_LOCALE)
        );

        foreach ($pairedEntities as $pair) {
            $productAbstractLocalizedEntity = $pair[static::PRODUCT_ABSTRACT_LOCALIZED_ENTITY];
            $productAbstractStorageEntity = $pair[static::PRODUCT_ABSTRACT_STORAGE_ENTITY];

            if ($productAbstractLocalizedEntity === null || !$this->isActive($productAbstractLocalizedEntity)) {
                $this->deleteProductAbstractStorageEntity($productAbstractStorageEntity);

                continue;
            }

            $this->storeProductAbstractStorageEntity(
                $productAbstractLocalizedEntity,
                $productAbstractStorageEntity,
                $pair[static::STORE_NAME],
                $pair[static::LOCALE_NAME],
                $attributeMapBulk
            );
        }
    }

    /**
     * - Returns a paired array with all provided entities.
     * - ProductAbstractLocalizedEntities without ProductAbstractStorageEntity are paired with a newly created ProductAbstractStorageEntity.
     * - ProductAbstractStorageEntity without ProductAbstractLocalizedEntities (left outs) are paired with NULL.
     * - ProductAbstractLocalizedEntities are paired multiple times per store.
     *
     * @param array $productAbstractLocalizedEntities
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage[] $productAbstractStorageEntities
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
                $pairs
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

            $productAbstractStorageEntity = isset($mappedProductAbstractStorageEntities[$idProduct][$storeName][$localeName]) ?
                $mappedProductAbstractStorageEntities[$idProduct][$storeName][$localeName] :
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
     * @param array $attributeMapBulk
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
            $attributeMapBulk
        );

        $spyProductStorageEntity
            ->setFkProductAbstract($productAbstractLocalizedEntity['SpyProductAbstract'][static::COL_ID_PRODUCT_ABSTRACT])
            ->setData($productAbstractStorageTransfer->toArray())
            ->setStore($storeName)
            ->setLocale($localeName)
            ->setIsSendingToQueue($this->isSendingToQueue)
            ->save();
    }

    /**
     * @param array $productAbstractLocalizedEntity
     *
     * @return bool
     */
    protected function isActive(array $productAbstractLocalizedEntity)
    {
        foreach ($productAbstractLocalizedEntity['SpyProductAbstract']['SpyProducts'] as $productEntity) {
            if ($productEntity['is_active']) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $productAbstractLocalizedEntity
     * @param \Generated\Shared\Transfer\ProductAbstractStorageTransfer $productAbstractStorageTransfer
     * @param array $attributeMapBulk
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
            $attributeMapBulk
        );
        $productAbstractEntity = $productAbstractLocalizedEntity['SpyProductAbstract'];
        unset($productAbstractLocalizedEntity['attributes']);
        unset($productAbstractEntity['attributes']);

        $productAbstractStorageTransfer
            ->fromArray($productAbstractLocalizedEntity, true)
            ->fromArray($productAbstractEntity, true)
            ->setAttributes($attributes)
            ->setAttributeMap($attributeMap)
            ->setSuperAttributesDefinition($this->getSuperAttributeKeys($attributes));

        return $productAbstractStorageTransfer;
    }

    /**
     * @param array $productAbstractLocalizedEntity
     *
     * @return array
     */
    protected function getProductAbstractAttributes(array $productAbstractLocalizedEntity)
    {
        $productAbstractDecodedAttributes = $this->productFacade->decodeProductAttributes(
            $productAbstractLocalizedEntity['SpyProductAbstract']['attributes']
        );
        $productAbstractLocalizedDecodedAttributes = $this->productFacade->decodeProductAttributes(
            $productAbstractLocalizedEntity['attributes']
        );

        $rawProductAttributesTransfer = (new RawProductAttributesTransfer())
            ->setAbstractAttributes($productAbstractDecodedAttributes)
            ->setAbstractLocalizedAttributes($productAbstractLocalizedDecodedAttributes);

        $attributes = $this->productFacade->combineRawProductAttributes($rawProductAttributesTransfer);

        $attributes = array_filter($attributes, function ($attributeKey) {
            return !empty($attributeKey);
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
        if (empty($this->superAttributeKeyBuffer)) {
            $this->loadSuperAttributes();
        }

        return $this->filterSuperAttributeKeys($attributes);
    }

    /**
     * @return void
     */
    protected function loadSuperAttributes()
    {
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
     * @return string[]
     */
    protected function filterSuperAttributeKeys(array $attributes)
    {
        $superAttributes = array_intersect_key($attributes, $this->superAttributeKeyBuffer);

        return array_keys($superAttributes);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLocalizedEntities(array $productAbstractIds)
    {
        return $this->queryContainer->queryProductAbstractByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage[]
     */
    protected function findProductAbstractStorageEntities(array $productAbstractIds)
    {
        return $this->queryContainer->queryProductAbstractStorageByIds($productAbstractIds)->find()->getArrayCopy();
    }

    /**
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage[] $productAbstractStorageEntities
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
}
