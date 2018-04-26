<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\ProductConcreteStorageTransfer;
use Generated\Shared\Transfer\RawProductAttributesTransfer;
use Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductStorage\Communication\ProductStorageCommunicationFactory getFactory()
 */
class AbstractProductConcreteStorageListener extends AbstractPlugin
{
    const COL_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';
    const COL_FK_PRODUCT = 'fk_product';
    const CONCRETE_DESCRIPTION = 'description';
    const ABSTRACT_DESCRIPTION = 'abstract_description';
    const ABSTRACT_ATTRIBUTES = 'abstract_attributes';
    const CONCRETE_ATTRIBUTES = 'attributes';

    /**
     * @var array
     */
    protected $superAttributes = [];

    /**
     * @param array $productIds
     *
     * @return void
     */
    protected function publish(array $productIds)
    {
        $spyProductConcreteLocalizedEntities = $this->findProductLocalizedEntities($productIds);
        $spyProductConcreteStorageEntities = $this->findProductStorageEntitiesByProductConcreteIds($productIds);

        if (!$spyProductConcreteLocalizedEntities) {
            $this->deleteStorageData($spyProductConcreteStorageEntities);
        }

        $this->storeData($spyProductConcreteLocalizedEntities, $spyProductConcreteStorageEntities);
    }

    /**
     * @param array $productIds
     *
     * @return void
     */
    protected function unpublish(array $productIds)
    {
        $spyProductStorageEntities = $this->findProductStorageEntitiesByProductConcreteIds($productIds);
        $this->deleteStorageData($spyProductStorageEntities);
    }

    /**
     * @param array $spyProductConcreteStorageEntities
     *
     * @return void
     */
    protected function deleteStorageData(array $spyProductConcreteStorageEntities)
    {
        foreach ($spyProductConcreteStorageEntities as $spyProductConcreteStorageLocalizedEntities) {
            foreach ($spyProductConcreteStorageLocalizedEntities as $spyProductConcreteStorageEntity) {
                $spyProductConcreteStorageEntity->delete();
            }
        }
    }

    /**
     * @param array $spyProductConcreteLocalizedEntities
     * @param array $spyProductConcreteStorageEntities
     *
     * @return void
     */
    protected function storeData(array $spyProductConcreteLocalizedEntities, array $spyProductConcreteStorageEntities)
    {
        foreach ($spyProductConcreteLocalizedEntities as $spyProductConcreteLocalizedEntity) {
            $idProduct = $spyProductConcreteLocalizedEntity[static::COL_FK_PRODUCT];
            $localeName = $spyProductConcreteLocalizedEntity['Locale']['locale_name'];
            if (isset($spyProductConcreteStorageEntities[$idProduct][$localeName])) {
                $this->storeDataSet($spyProductConcreteLocalizedEntity, $spyProductConcreteStorageEntities[$idProduct][$localeName]);
            } else {
                $this->storeDataSet($spyProductConcreteLocalizedEntity);
            }
        }
    }

    /**
     * @param array $spyProductConcreteLocalizedEntity
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage|null $spyProductStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(array $spyProductConcreteLocalizedEntity, ?SpyProductConcreteStorage $spyProductStorageEntity = null)
    {
        if ($spyProductStorageEntity === null) {
            $spyProductStorageEntity = new SpyProductConcreteStorage();
        }

        if (!$spyProductConcreteLocalizedEntity['SpyProduct']['is_active']) {
            if (!$spyProductStorageEntity->isNew()) {
                $spyProductStorageEntity->delete();
            }

            return;
        }

        $productConcreteStorageTransfer = $this->mapToProductConcreteStorageTransfer($spyProductConcreteLocalizedEntity);
        $spyProductStorageEntity->setFkProduct($spyProductConcreteLocalizedEntity[static::COL_FK_PRODUCT]);
        $spyProductStorageEntity->setData($productConcreteStorageTransfer->toArray());
        $spyProductStorageEntity->setStore($this->getStoreName());
        $spyProductStorageEntity->setLocale($spyProductConcreteLocalizedEntity['Locale']['locale_name']);
        $spyProductStorageEntity->save();
    }

    /**
     * @param array $spyProductConcreteLocalizedEntity
     *
     * @return \Generated\Shared\Transfer\ProductConcreteStorageTransfer
     */
    protected function mapToProductConcreteStorageTransfer(array $spyProductConcreteLocalizedEntity)
    {
        $attributes = $this->getConcreteAttributes($spyProductConcreteLocalizedEntity);

        $spyProductConcreteEntityArray = $spyProductConcreteLocalizedEntity['SpyProduct'];
        unset($spyProductConcreteLocalizedEntity['attributes']);
        unset($spyProductConcreteEntityArray['attributes']);

        $productStorageTransfer = new ProductConcreteStorageTransfer();
        $productStorageTransfer->fromArray($spyProductConcreteLocalizedEntity, true);
        $productStorageTransfer->fromArray($spyProductConcreteEntityArray, true);
        $productStorageTransfer->setIdProductConcrete($spyProductConcreteLocalizedEntity[static::COL_FK_PRODUCT]);
        $productStorageTransfer->setIdProductAbstract($spyProductConcreteEntityArray[static::COL_FK_PRODUCT_ABSTRACT]);
        $productStorageTransfer->setDescription($this->getDescription($spyProductConcreteLocalizedEntity));
        $productStorageTransfer->setAttributes($attributes);
        $productStorageTransfer->setSuperAttributesDefinition($this->getVariantSuperAttributes($attributes));

        return $productStorageTransfer;
    }

    /**
     * @param array $spyProductConcreteLocalizedEntity
     *
     * @return array
     */
    protected function getConcreteAttributes(array $spyProductConcreteLocalizedEntity)
    {
        $abstractAttributesData = $this->getFactory()->getProductFacade()->decodeProductAttributes($spyProductConcreteLocalizedEntity['SpyProduct']['SpyProductAbstract']['attributes']);
        $concreteAttributesData = $this->getFactory()->getProductFacade()->decodeProductAttributes($spyProductConcreteLocalizedEntity['SpyProduct']['attributes']);

        $abstractLocalizedAttributesData = $this->getFactory()->getProductFacade()->decodeProductAttributes($spyProductConcreteLocalizedEntity[self::ABSTRACT_ATTRIBUTES]);
        $concreteLocalizedAttributesData = $this->getFactory()->getProductFacade()->decodeProductAttributes($spyProductConcreteLocalizedEntity[self::CONCRETE_ATTRIBUTES]);

        $rawProductAttributesTransfer = new RawProductAttributesTransfer();
        $rawProductAttributesTransfer
            ->setAbstractAttributes($abstractAttributesData)
            ->setAbstractLocalizedAttributes($abstractLocalizedAttributesData)
            ->setConcreteAttributes($concreteAttributesData)
            ->setConcreteLocalizedAttributes($concreteLocalizedAttributesData);

        return $this->getFactory()->getProductFacade()->combineRawProductAttributes($rawProductAttributesTransfer);
    }

    /**
     * @param array $collectItemData
     *
     * @return string
     */
    protected function getDescription(array $collectItemData)
    {
        $description = trim($collectItemData[self::CONCRETE_DESCRIPTION]);
        if (!$description) {
            $description = trim($collectItemData[self::ABSTRACT_DESCRIPTION]);
        }

        return $description;
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function getVariantSuperAttributes(array $attributes)
    {
        if (empty($this->superAttributes)) {
            $superAttributes = $this->getQueryContainer()
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
     * @param array $productIds
     *
     * @return array
     */
    protected function findProductLocalizedEntities(array $productIds)
    {
        return $this->getQueryContainer()->queryProductConcreteByIds($productIds)->find()->getData();
    }

    /**
     * @param array $productConcreteIds
     *
     * @return array
     */
    protected function findProductStorageEntitiesByProductConcreteIds(array $productConcreteIds)
    {
        $productConcreteStorageEntities = $this->getQueryContainer()->queryProductConcreteStorageByIds($productConcreteIds)->find();
        $productConcreteStorageEntitiesByIdAndLocale = [];
        foreach ($productConcreteStorageEntities as $productConcreteStorageEntity) {
            $productConcreteStorageEntitiesByIdAndLocale[$productConcreteStorageEntity->getFkProduct()][$productConcreteStorageEntity->getLocale()] = $productConcreteStorageEntity;
        }

        return $productConcreteStorageEntitiesByIdAndLocale;
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->getFactory()->getStore()->getStoreName();
    }
}
