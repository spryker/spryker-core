<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\ProductAbstractGroupStorageTransfer;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes;
use Orm\Zed\ProductGroupStorage\Persistence\SpyProductAbstractGroupStorage;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductGroupStorage\Persistence\ProductGroupStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductGroupStorage\Communication\ProductGroupStorageCommunicationFactory getFactory()
 */
class AbstractProductAbstractGroupStorageListener extends AbstractPlugin
{
    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    protected function publish(array $productAbstractIds)
    {
        $groupedProductAbstractIds = $this->getGroupedProductAbstractIdsByGroupIds($productAbstractIds);
        $allProductAbstractIds = $this->getProductAbstractIds($groupedProductAbstractIds, $productAbstractIds);
        if (empty($allProductAbstractIds)) {
            return;
        }

        $spyProductAbstractLocalizedAttributeEntities = $this->findProductAbstractLocalizedWithEntities($allProductAbstractIds);
        $spyProductAbstractGroupStorageEntities = $this->findProductAbstractGroupStorageEntitiesByProductAbstractIds($allProductAbstractIds);

        $this->storeData($spyProductAbstractLocalizedAttributeEntities, $spyProductAbstractGroupStorageEntities, $groupedProductAbstractIds);
    }

    /**
     * @param array $spyProductAbstractLocalizedEntities
     * @param array $spyProductAbstractGroupStorageEntities
     * @param array $groupedProductAbstractIds
     *
     * @return void
     */
    protected function storeData(array $spyProductAbstractLocalizedEntities, array $spyProductAbstractGroupStorageEntities, array $groupedProductAbstractIds)
    {
        foreach ($spyProductAbstractLocalizedEntities as $spyProductAbstractLocalizedEntity) {
            $idProduct = $spyProductAbstractLocalizedEntity->getFkProductAbstract();
            $localeName = $spyProductAbstractLocalizedEntity->getLocale()->getLocaleName();
            if (isset($spyProductAbstractGroupStorageEntities[$idProduct][$localeName])) {
                $this->storeDataSet($spyProductAbstractLocalizedEntity, $groupedProductAbstractIds, $spyProductAbstractGroupStorageEntities[$idProduct][$localeName]);
            } else {
                $this->storeDataSet($spyProductAbstractLocalizedEntity, $groupedProductAbstractIds);
            }
        }
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity
     * @param array $groupedProductAbstractIds
     * @param \Orm\Zed\ProductGroupStorage\Persistence\SpyProductAbstractGroupStorage|null $spyProductStorageGroupEntity
     *
     * @return void
     */
    protected function storeDataSet(SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity, array $groupedProductAbstractIds, SpyProductAbstractGroupStorage $spyProductStorageGroupEntity = null)
    {
        if ($spyProductStorageGroupEntity === null) {
            $spyProductStorageGroupEntity = new SpyProductAbstractGroupStorage();
        }

        $allGroupedProductAbstractIds = $this->mergeRelatedProductAbstractIds($spyProductAbstractLocalizedEntity, $groupedProductAbstractIds);
        if (empty($allGroupedProductAbstractIds)) {
            if (!$spyProductStorageGroupEntity->isNew()) {
                $spyProductStorageGroupEntity->delete();
            }

            return;
        }

        $orderedAllGroupedProductAbstractIds = $this->moveSubjectProductToFirstPosition($spyProductAbstractLocalizedEntity->getFkProductAbstract(), array_unique($allGroupedProductAbstractIds));
        $productAbstractGroupStorageTransfer = $this->getProductAbstractGroupStorageTransfer($spyProductAbstractLocalizedEntity, $orderedAllGroupedProductAbstractIds);

        $spyProductStorageGroupEntity->setFkProductAbstract($spyProductAbstractLocalizedEntity->getFkProductAbstract());
        $spyProductStorageGroupEntity->setData($productAbstractGroupStorageTransfer->toArray());
        $spyProductStorageGroupEntity->setStore($this->getStoreName());
        $spyProductStorageGroupEntity->setLocale($spyProductAbstractLocalizedEntity->getLocale()->getLocaleName());
        $spyProductStorageGroupEntity->save();
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity
     * @param array $orderedAllGroupedProductAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractGroupStorageTransfer
     */
    protected function getProductAbstractGroupStorageTransfer(SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity, array $orderedAllGroupedProductAbstractIds)
    {
        $productAbstractGroupStorageTransfer = new ProductAbstractGroupStorageTransfer();
        $productAbstractGroupStorageTransfer->setIdProductAbstract($spyProductAbstractLocalizedEntity->getFkProductAbstract());
        $productAbstractGroupStorageTransfer->setGroupProductAbstractIds($orderedAllGroupedProductAbstractIds);

        return $productAbstractGroupStorageTransfer;
    }

    /**
     * @param array $productAbstractIds
     * @param array $groupedProductAbstractIds
     *
     * @return array
     */
    protected function getGroupedProductAbstractIdsByGroupIds(array $productAbstractIds, array $groupedProductAbstractIds = [])
    {
        $productGroupIds = $this->findProductGroupAbstractEntitiesByProductAbstractIds($productAbstractIds);
        $spyProductAbstractGroupEntities = $this->findProductGroupAbstractEntitiesByProductGroupIds(array_keys($productGroupIds));
        $allProductAbstractIds = [];
        foreach ($spyProductAbstractGroupEntities as $spyProductAbstractGroupEntity) {
            $groupedProductAbstractIds[$spyProductAbstractGroupEntity->getFkProductGroup()][] = $spyProductAbstractGroupEntity->getFkProductAbstract();
            $allProductAbstractIds[] = $spyProductAbstractGroupEntity->getFkProductAbstract();
        }

        $spyProductAbstractLocalizedAttributeEntities = $this->findProductAbstractLocalizedWithGroupEntities($allProductAbstractIds);
        foreach ($spyProductAbstractLocalizedAttributeEntities as $spyProductAbstractLocalizedAttributeEntity) {
            $groupedProductAbstractIds = $this->getGroupedProductAbstractIds($spyProductAbstractLocalizedAttributeEntity, $groupedProductAbstractIds);
        }

        return $groupedProductAbstractIds;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedAttributeEntity
     * @param array $groupedProductAbstractIds
     *
     * @return array
     */
    protected function getGroupedProductAbstractIds(SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedAttributeEntity, array $groupedProductAbstractIds)
    {
        foreach ($spyProductAbstractLocalizedAttributeEntity->getSpyProductAbstract()->getSpyProductAbstractGroups() as $spyProductAbstractGroup) {
            $idProductGroup = $spyProductAbstractGroup->getFkProductGroup();
            if (!isset($groupedProductAbstractIds[$idProductGroup])) {
                $result = $this->getGroupedProductAbstractIdsByGroupIds([$spyProductAbstractGroup->getFkProductAbstract()], $groupedProductAbstractIds);
                $groupedProductAbstractIds[$idProductGroup] = $result[$idProductGroup];
            }
        }

        return $groupedProductAbstractIds;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity
     * @param array $groupedProductAbstractIds
     *
     * @return array
     */
    protected function mergeRelatedProductAbstractIds(SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity, array $groupedProductAbstractIds)
    {
        $allGroupedProductAbstractIds = [];
        foreach ($groupedProductAbstractIds as $items) {
            if (in_array($spyProductAbstractLocalizedEntity->getFkProductAbstract(), $items)) {
                $allGroupedProductAbstractIds = array_merge($allGroupedProductAbstractIds, $items);
            }
        }

        return $allGroupedProductAbstractIds;
    }

    /**
     * @param array $groupedProductAbstractIds
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function getProductAbstractIds(array $groupedProductAbstractIds, array $productAbstractIds)
    {
        $allProductAbstractId = [];
        foreach ($groupedProductAbstractIds as $groupedProductAbstractId) {
            $allProductAbstractId = array_merge($allProductAbstractId, $groupedProductAbstractId);
        }

        return array_unique(array_merge($allProductAbstractId, $productAbstractIds));
    }

    /**
     * @param int $idProductAbstract
     * @param array $idProductAbstracts
     *
     * @return array
     */
    protected function moveSubjectProductToFirstPosition($idProductAbstract, array $idProductAbstracts)
    {
        $currentProductIndex = array_search($idProductAbstract, $idProductAbstracts);

        if ($currentProductIndex !== false) {
            unset($idProductAbstracts[$currentProductIndex]);
            array_unshift($idProductAbstracts, $idProductAbstract);
        }

        return $idProductAbstracts;
    }

    /**
     * @param array $productGroupIds
     *
     * @return \Orm\Zed\ProductGroup\Persistence\Base\SpyProductAbstractGroup[]
     */
    protected function findProductGroupAbstractEntitiesByProductGroupIds(array $productGroupIds)
    {
        return $this->getQueryContainer()->queryProductAbstractGroupByGroupIds($productGroupIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductGroup\Persistence\Base\SpyProductAbstractGroup[]
     */
    protected function findProductGroupAbstractEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        return $this->getQueryContainer()->queryProductAbstractGroupByProductAbstractIds($productAbstractIds)->find()->toKeyIndex('fkProductGroup');
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLocalizedWithGroupEntities(array $productAbstractIds)
    {
        return $this->getQueryContainer()->queryProductAbstractLocalizedWithGroupByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLocalizedWithEntities(array $productAbstractIds)
    {
        return $this->getQueryContainer()->queryProductAbstractLocalizedByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractGroupStorageEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        $productAbstractGroupStorageEntities = $this->getQueryContainer()->queryProductAbstractGroupStorageByIds($productAbstractIds)->find();
        $productAbstractStorageEntitiesByIdAndLocale = [];
        foreach ($productAbstractGroupStorageEntities as $productAbstractGroupStorageEntity) {
            $productAbstractStorageEntitiesByIdAndLocale[$productAbstractGroupStorageEntity->getFkProductAbstract()][$productAbstractGroupStorageEntity->getLocale()] = $productAbstractGroupStorageEntity;
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
