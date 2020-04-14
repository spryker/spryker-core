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

class ProductConcreteStorageWriter implements ProductConcreteStorageWriterInterface
{
    public const COL_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';
    public const COL_FK_PRODUCT = 'fk_product';
    public const CONCRETE_DESCRIPTION = 'description';
    public const ABSTRACT_DESCRIPTION = 'abstract_description';
    public const ABSTRACT_ATTRIBUTES = 'abstract_attributes';
    public const CONCRETE_ATTRIBUTES = 'attributes';

    public const PRODUCT_CONCRETE_LOCALIZED_ENTITY = 'PRODUCT_CONCRETE_LOCALIZED_ENTITY';
    public const PRODUCT_CONCRETE_STORAGE_ENTITY = 'PRODUCT_CONCRETE_STORAGE_ENTITY';
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
     * @param \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     */
    public function __construct(
        ProductStorageToProductInterface $productFacade,
        ProductStorageQueryContainerInterface $queryContainer,
        $isSendingToQueue
    ) {
        $this->productFacade = $productFacade;
        $this->queryContainer = $queryContainer;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function publish(array $productConcreteIds)
    {
        $productConcreteLocalizedEntities = $this->findProductConcreteLocalizedEntities($productConcreteIds);
        $productConcreteStorageEntities = $this->findProductConcreteStorageEntities($productConcreteIds);

        if (!$productConcreteLocalizedEntities) {
            $this->deleteProductConcreteStorageEntities($productConcreteStorageEntities);

            return;
        }

        $this->storeData($productConcreteLocalizedEntities, $productConcreteStorageEntities);
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function unpublish(array $productConcreteIds)
    {
        $productConcreteStorageEntities = $this->findProductConcreteStorageEntities($productConcreteIds);

        $this->deleteProductConcreteStorageEntities($productConcreteStorageEntities);
    }

    /**
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage[] $productConcreteStorageEntities
     *
     * @return void
     */
    protected function deleteProductConcreteStorageEntities(array $productConcreteStorageEntities)
    {
        foreach ($productConcreteStorageEntities as $productConcreteStorageEntity) {
            $productConcreteStorageEntity->delete();
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
            $productConcreteStorageEntity->delete();
        }
    }

    /**
     * @param array $productConcreteLocalizedEntities
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage[] $productConcreteStorageEntities
     *
     * @return void
     */
    protected function storeData(array $productConcreteLocalizedEntities, array $productConcreteStorageEntities)
    {
        $pairedEntities = $this->pairProductConcreteLocalizedEntitiesWithProductConcreteStorageEntities(
            $productConcreteLocalizedEntities,
            $productConcreteStorageEntities
        );

        foreach ($pairedEntities as $pair) {
            $productConcreteLocalizedEntity = $pair[static::PRODUCT_CONCRETE_LOCALIZED_ENTITY];
            $productConcreteStorageEntity = $pair[static::PRODUCT_CONCRETE_STORAGE_ENTITY];

            if ($productConcreteLocalizedEntity === null || !$this->isActive($productConcreteLocalizedEntity)) {
                $this->deletedProductConcreteSorageEntity($productConcreteStorageEntity);

                continue;
            }

            $this->storeProductConcreteStorageEntity(
                $productConcreteLocalizedEntity,
                $productConcreteStorageEntity,
                $pair[static::LOCALE_NAME]
            );
        }
    }

    /**
     * - Returns a paired array with all provided entities.
     * - ProductConcreteLocalizedEntities without ProductConcreteStorageEntity are paired with a newly created ProductConcreteStorageEntity.
     * - ProductConcreteStorageEntities without ProductConcreteLocalizedEntity (left outs) are paired with NULL.
     *
     * @param array $productConcreteLocalizedEntities
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage[] $productConcreteStorageEntities
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
                $pairs
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
        $productConcreteStorageEntity = isset($mappedProductConcreteStorageEntities[$idProduct][$localeName]) ?
            $mappedProductConcreteStorageEntities[$idProduct][$localeName] :
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
     * @param array $productConcreteLocalizedEntity
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage $productConcreteStorageEntity
     * @param string $localeName
     *
     * @return void
     */
    protected function storeProductConcreteStorageEntity(
        array $productConcreteLocalizedEntity,
        SpyProductConcreteStorage $productConcreteStorageEntity,
        $localeName
    ) {
        $productConcreteStorageTransfer = $this->mapToProductConcreteStorageTransfer($productConcreteLocalizedEntity);

        $productConcreteStorageEntity
            ->setFkProduct($productConcreteLocalizedEntity[static::COL_FK_PRODUCT])
            ->setData($productConcreteStorageTransfer->toArray())
            ->setLocale($localeName)
            ->setIsSendingToQueue($this->isSendingToQueue)
            ->save();
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

        if (empty($superAttributes->getData())) {
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
     * @param int[] $productConcreteIds
     *
     * @return array
     */
    protected function findProductConcreteLocalizedEntities(array $productConcreteIds)
    {
        return $this->queryContainer->queryProductConcreteByIds($productConcreteIds)->find()->getData();
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return array
     */
    protected function findProductConcreteStorageEntities(array $productConcreteIds)
    {
        return $this->queryContainer->queryProductConcreteStorageByIds($productConcreteIds)->find()->getArrayCopy();
    }

    /**
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage[] $productConcreteStorageEntities
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
}
