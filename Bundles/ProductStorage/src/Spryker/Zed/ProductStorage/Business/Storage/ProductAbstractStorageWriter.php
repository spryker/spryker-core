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
use Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface;

class ProductAbstractStorageWriter implements ProductAbstractStorageWriterInterface
{
    const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    const COL_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';

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
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @var array
     */
    protected $superAttributes = [];

    /**
     * @param \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface $productFacade
     * @param \Spryker\Zed\ProductStorage\Business\Attribute\AttributeMapInterface $attributeMap
     * @param \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     */
    public function __construct(
        ProductStorageToProductInterface $productFacade,
        AttributeMapInterface $attributeMap,
        ProductStorageQueryContainerInterface $queryContainer,
        $isSendingToQueue
    ) {
        $this->productFacade = $productFacade;
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
        $spyProductAbstractLocalizedEntities = $this->findProductAbstractLocalizedEntities($productAbstractIds);
        $spyProductAbstractStorageEntities = $this->findProductStorageEntitiesByProductAbstractIds($productAbstractIds);

        if (!$spyProductAbstractLocalizedEntities) {
            $this->deleteStorageData($spyProductAbstractStorageEntities);
        }

        $this->storeData($spyProductAbstractLocalizedEntities, $spyProductAbstractStorageEntities);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $spyProductStorageEntities = $this->findProductStorageEntitiesByProductAbstractIds($productAbstractIds);
        $this->deleteStorageData($spyProductStorageEntities);
    }

    /**
     * @param array $spyProductAbstractStorageEntities
     *
     * @return void
     */
    protected function deleteStorageData(array $spyProductAbstractStorageEntities)
    {
        foreach ($spyProductAbstractStorageEntities as $spyProductAbstractStoreEntities) {
            foreach ($spyProductAbstractStoreEntities as $spyProductStorageLocalizedEntities) {
                foreach ($spyProductStorageLocalizedEntities as $spyProductAbstractStorageEntity) {
                    $spyProductAbstractStorageEntity->delete();
                }
            }
        }
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes[][] $spyProductAbstractLocalizedEntities
     * @param array $spyProductAbstractStorageEntities
     *
     * @return void
     */
    protected function storeData(array $spyProductAbstractLocalizedEntities, array $spyProductAbstractStorageEntities)
    {
        $toRemoveEntities = $spyProductAbstractStorageEntities;
        foreach ($spyProductAbstractLocalizedEntities as $spyProductAbstractLocalizedEntity) {
            $idProduct = $spyProductAbstractLocalizedEntity['SpyProductAbstract'][static::COL_ID_PRODUCT_ABSTRACT];
            $localeName = $spyProductAbstractLocalizedEntity['Locale']['locale_name'];

            foreach ($spyProductAbstractLocalizedEntity['SpyProductAbstract']['SpyProductAbstractStores'] as $spyProductAbstractStore) {
                $storeName = $spyProductAbstractStore['SpyStore']['name'];

                if (isset($spyProductAbstractStorageEntities[$idProduct][$storeName][$localeName])) {
                    $this->storeDataSet($storeName, $spyProductAbstractLocalizedEntity, $spyProductAbstractStorageEntities[$idProduct][$storeName][$localeName]);
                } else {
                    $this->storeDataSet($storeName, $spyProductAbstractLocalizedEntity);
                }
                unset($toRemoveEntities[$idProduct][$storeName][$localeName]);
            }

            if (!isset($toRemoveEntities[$idProduct])) {
                continue;
            }

            foreach ($toRemoveEntities[$idProduct] as $storeName => $localeEntities) {
                foreach ($localeEntities as $thisLocaleName => $spyProductAbstractStorageEntity) {
                    if ($localeName !== $thisLocaleName) {
                        continue;
                    }

                    $spyProductAbstractStorageEntity->delete();
                    unset($toRemoveEntities[$idProduct][$storeName][$localeName]);
                }
            }
        }
    }

    /**
     * @param string $storeName
     * @param array $spyProductAbstractLocalizedEntity
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage|null $spyProductStorageEntity
     *
     * @return void
     */
    protected function storeDataSet($storeName, array $spyProductAbstractLocalizedEntity, SpyProductAbstractStorage $spyProductStorageEntity = null)
    {
        if (!$this->isActive($spyProductAbstractLocalizedEntity)) {
            $spyProductStorageEntity && $spyProductStorageEntity->delete();

            return;
        }

        if ($spyProductStorageEntity === null) {
            $spyProductStorageEntity = new SpyProductAbstractStorage();
        }

        $productAbstractStorageTransfer = $this->mapToProductAbstractStorageTransfer(
            $spyProductAbstractLocalizedEntity,
            new ProductAbstractStorageTransfer()
        );
        $spyProductStorageEntity->setFkProductAbstract($spyProductAbstractLocalizedEntity['SpyProductAbstract'][static::COL_ID_PRODUCT_ABSTRACT]);
        $spyProductStorageEntity->setData($productAbstractStorageTransfer->toArray());
        $spyProductStorageEntity->setStore($storeName);
        $spyProductStorageEntity->setLocale($spyProductAbstractLocalizedEntity['Locale']['locale_name']);
        $spyProductStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
        $spyProductStorageEntity->save();
    }

    /**
     * @param array $spyProductAbstractLocalizedEntity
     *
     * @return bool
     */
    protected function isActive(array $spyProductAbstractLocalizedEntity)
    {
        foreach ($spyProductAbstractLocalizedEntity['SpyProductAbstract']['SpyProducts'] as $spyProduct) {
            if ($spyProduct['is_active']) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $spyProductAbstractLocalizedEntity
     * @param \Generated\Shared\Transfer\ProductAbstractStorageTransfer|null $productStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractStorageTransfer
     */
    protected function mapToProductAbstractStorageTransfer(array $spyProductAbstractLocalizedEntity, ProductAbstractStorageTransfer $productStorageTransfer = null)
    {
        $attributes = $this->getAbstractAttributes($spyProductAbstractLocalizedEntity);
        $attributeMap = $this->attributeMap->generateAttributeMap(
            $spyProductAbstractLocalizedEntity[static::COL_FK_PRODUCT_ABSTRACT],
            $spyProductAbstractLocalizedEntity['Locale']['id_locale']
        );
        $spyProductAbstractEntityArray = $spyProductAbstractLocalizedEntity['SpyProductAbstract'];
        unset($spyProductAbstractLocalizedEntity['attributes']);
        unset($spyProductAbstractEntityArray['attributes']);

        if ($productStorageTransfer === null) {
            $productStorageTransfer = new ProductAbstractStorageTransfer();
        }
        $productStorageTransfer->fromArray($spyProductAbstractLocalizedEntity, true);
        $productStorageTransfer->fromArray($spyProductAbstractEntityArray, true);
        $productStorageTransfer->setAttributes($attributes);
        $productStorageTransfer->setAttributeMap($attributeMap);
        $productStorageTransfer->setSuperAttributesDefinition($this->getVariantSuperAttributes($attributes));

        return $productStorageTransfer;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function getAbstractAttributes(array $data)
    {
        $abstractAttributesData = $this->productFacade->decodeProductAttributes($data['SpyProductAbstract']['attributes']);
        $abstractLocalizedAttributesData = $this->productFacade->decodeProductAttributes($data['attributes']);

        $rawProductAttributesTransfer = new RawProductAttributesTransfer();
        $rawProductAttributesTransfer
            ->setAbstractAttributes($abstractAttributesData)
            ->setAbstractLocalizedAttributes($abstractLocalizedAttributesData);

        $attributes = $this->productFacade->combineRawProductAttributes($rawProductAttributesTransfer);

        $attributes = array_filter($attributes, function ($key) {
            return !empty($key);
        }, ARRAY_FILTER_USE_KEY);

        return $attributes;
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function getVariantSuperAttributes(array $attributes)
    {
        if (empty($this->superAttributes)) {
            $superAttributes = $this->queryContainer
                ->queryProductAttributeKey()
                ->find();

            foreach ($superAttributes as $attribute) {
                $this->superAttributes[$attribute->getKey()] = true;
            }
        }

        return $this->filterVariantSuperAttributes($attributes);
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function filterVariantSuperAttributes(array $attributes)
    {
        $variantSuperAttributes = array_filter($attributes, function ($key) {
            return isset($this->superAttributes[$key]);
        }, ARRAY_FILTER_USE_KEY);

        return array_keys($variantSuperAttributes);
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
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductStorageEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        $productAbstractStorageEntities = $this->queryContainer->queryProductAbstractStorageByIds($productAbstractIds)->find();
        $productAbstractStorageEntitiesByIdAndLocale = [];
        foreach ($productAbstractStorageEntities as $productAbstractStorageEntity) {
            $productAbstractStorageEntitiesByIdAndLocale[$productAbstractStorageEntity->getFkProductAbstract()][$productAbstractStorageEntity->getStore()][$productAbstractStorageEntity->getLocale()] = $productAbstractStorageEntity;
        }

        return $productAbstractStorageEntitiesByIdAndLocale;
    }
}
