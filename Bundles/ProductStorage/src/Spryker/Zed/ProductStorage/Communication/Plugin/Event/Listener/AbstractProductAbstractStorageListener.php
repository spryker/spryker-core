<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\ProductAbstractStorageTransfer;
use Generated\Shared\Transfer\RawProductAttributesTransfer;
use Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductStorage\Communication\ProductStorageCommunicationFactory getFactory()
 */
class AbstractProductAbstractStorageListener extends AbstractPlugin
{
    const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    const COL_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';

    /**
     * @var array
     */
    protected $superAttributes = [];

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    protected function publish(array $productAbstractIds)
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
    protected function unpublish(array $productAbstractIds)
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
        foreach ($spyProductAbstractStorageEntities as $spyProductStorageLocalizedEntities) {
            foreach ($spyProductStorageLocalizedEntities as $spyProductAbstractStorageEntity) {
                $spyProductAbstractStorageEntity->delete();
            }
        }
    }

    /**
     * @param array $spyProductAbstractLocalizedEntities
     * @param array $spyProductAbstractStorageEntities
     *
     * @return void
     */
    protected function storeData(array $spyProductAbstractLocalizedEntities, array $spyProductAbstractStorageEntities)
    {
        foreach ($spyProductAbstractLocalizedEntities as $spyProductAbstractLocalizedEntity) {
            $idProduct = $spyProductAbstractLocalizedEntity['SpyProductAbstract'][static::COL_ID_PRODUCT_ABSTRACT];
            $localeName = $spyProductAbstractLocalizedEntity['Locale']['locale_name'];
            if (isset($spyProductAbstractStorageEntities[$idProduct][$localeName])) {
                $this->storeDataSet($spyProductAbstractLocalizedEntity, $spyProductAbstractStorageEntities[$idProduct][$localeName]);
            } else {
                $this->storeDataSet($spyProductAbstractLocalizedEntity);
            }
        }
    }

    /**
     * @param array $spyProductAbstractLocalizedEntity
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage|null $spyProductStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(array $spyProductAbstractLocalizedEntity, ?SpyProductAbstractStorage $spyProductStorageEntity = null)
    {
        $productAbstractStorageTransfer = new ProductAbstractStorageTransfer();
        if ($spyProductStorageEntity === null) {
            $spyProductStorageEntity = new SpyProductAbstractStorage();
        }

        if (!$this->isActive($spyProductAbstractLocalizedEntity)) {
            if (!$spyProductStorageEntity->isNew()) {
                $spyProductStorageEntity->delete();
            }

            return;
        }

        $productAbstractStorageTransfer = $this->mapToProductAbstractStorageTransfer($spyProductAbstractLocalizedEntity, $productAbstractStorageTransfer);
        $spyProductStorageEntity->setFkProductAbstract($spyProductAbstractLocalizedEntity['SpyProductAbstract'][static::COL_ID_PRODUCT_ABSTRACT]);
        $spyProductStorageEntity->setData($productAbstractStorageTransfer->toArray());
        $spyProductStorageEntity->setStore($this->getStoreName());
        $spyProductStorageEntity->setLocale($spyProductAbstractLocalizedEntity['Locale']['locale_name']);
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
    protected function mapToProductAbstractStorageTransfer(array $spyProductAbstractLocalizedEntity, ?ProductAbstractStorageTransfer $productStorageTransfer = null)
    {
        $attributes = $this->getAbstractAttributes($spyProductAbstractLocalizedEntity);
        $attributeMap = $this->getFactory()->createAttributeMapHelper()->generateAttributeMap(
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
        $abstractAttributesData = $this->getFactory()->getProductFacade()->decodeProductAttributes($data['SpyProductAbstract']['attributes']);
        $abstractLocalizedAttributesData = $this->getFactory()->getProductFacade()->decodeProductAttributes($data['attributes']);

        $rawProductAttributesTransfer = new RawProductAttributesTransfer();
        $rawProductAttributesTransfer
            ->setAbstractAttributes($abstractAttributesData)
            ->setAbstractLocalizedAttributes($abstractLocalizedAttributesData);

        $attributes = $this->getFactory()->getProductFacade()->combineRawProductAttributes($rawProductAttributesTransfer);

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
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLocalizedEntities(array $productAbstractIds)
    {
        return $this->getQueryContainer()->queryProductAbstractByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductStorageEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        $productAbstractStorageEntities = $this->getQueryContainer()->queryProductAbstractStorageByIds($productAbstractIds)->find();
        $productAbstractStorageEntitiesByIdAndLocale = [];
        foreach ($productAbstractStorageEntities as $productAbstractStorageEntity) {
            $productAbstractStorageEntitiesByIdAndLocale[$productAbstractStorageEntity->getFkProductAbstract()][$productAbstractStorageEntity->getLocale()] = $productAbstractStorageEntity;
        }

        return $productAbstractStorageEntitiesByIdAndLocale;
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->getFactory()->getStore()->getStoreName();
    }
}
