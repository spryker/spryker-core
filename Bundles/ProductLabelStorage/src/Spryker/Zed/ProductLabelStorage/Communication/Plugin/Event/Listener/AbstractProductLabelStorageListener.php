<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductAbstractLabelStorage;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabelStorage\Communication\ProductLabelStorageCommunicationFactory getFactory()
 */
class AbstractProductLabelStorageListener extends AbstractPlugin
{
    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    protected function publish(array $productAbstractIds)
    {
        $productLabels = $this->findProductLabelAbstractEntities($productAbstractIds);
        $groupedLabelsByProductAbstractId = [];
        foreach ($productLabels as $productLabel) {
            $groupedLabelsByProductAbstractId[$productLabel['fk_product_abstract']][] = $productLabel['fk_product_label'];
        }

        $spyProductAbstractLocalizedAttributeEntities = $this->findProductAbstractLocalizedEntities($productAbstractIds);
        $spyProductAbstractLabelStorageEntities = $this->findProductAbstractLabelStorageEntitiesByProductAbstractIds($productAbstractIds);

        $foundProductAbstractIds = [];
        foreach ($spyProductAbstractLocalizedAttributeEntities as $spyProductAbstractLocalizedAttributeEntity) {
            $foundProductAbstractIds[] = $spyProductAbstractLocalizedAttributeEntity->getFkProductAbstract();
        }

        $uniqueProductAbstractIds = array_unique($foundProductAbstractIds);
        $this->storeData($uniqueProductAbstractIds, $spyProductAbstractLabelStorageEntities, $groupedLabelsByProductAbstractId);
    }

    /**
     * @param array $uniqueProductAbstractIds
     * @param array $spyProductAbstractLabelStorageEntities
     * @param array $productLabelsIds
     *
     * @return void
     */
    protected function storeData(array $uniqueProductAbstractIds, array $spyProductAbstractLabelStorageEntities, array $productLabelsIds)
    {
        foreach ($uniqueProductAbstractIds as $productAbstractId) {
            if (isset($spyProductAbstractLabelStorageEntities[$productAbstractId])) {
                $this->storeDataSet($productAbstractId, $productLabelsIds, $spyProductAbstractLabelStorageEntities[$productAbstractId]);
            } else {
                $this->storeDataSet($productAbstractId, $productLabelsIds);
            }
        }
    }

    /**
     * @param int $productAbstractId
     * @param array $productLabelsIds
     * @param \Orm\Zed\ProductLabelStorage\Persistence\SpyProductAbstractLabelStorage|null $spyProductAbstractLabelStorageEntity
     *
     * @return void
     */
    protected function storeDataSet($productAbstractId, array $productLabelsIds, ?SpyProductAbstractLabelStorage $spyProductAbstractLabelStorageEntity = null)
    {
        if ($spyProductAbstractLabelStorageEntity === null) {
            $spyProductAbstractLabelStorageEntity = new SpyProductAbstractLabelStorage();
        }

        if (empty($productLabelsIds[$productAbstractId])) {
            if (!$spyProductAbstractLabelStorageEntity->isNew()) {
                $spyProductAbstractLabelStorageEntity->delete();
            }

            return;
        }

        $productAbstractLabelStorageTransfer = new ProductAbstractLabelStorageTransfer();
        $productAbstractLabelStorageTransfer->setIdProductAbstract($productAbstractId);
        $productAbstractLabelStorageTransfer->setProductLabelIds($productLabelsIds[$productAbstractId]);

        $spyProductAbstractLabelStorageEntity->setFkProductAbstract($productAbstractId);
        $spyProductAbstractLabelStorageEntity->setData($productAbstractLabelStorageTransfer->toArray());
        $spyProductAbstractLabelStorageEntity->setStore($this->getStoreName());
        $spyProductAbstractLabelStorageEntity->save();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabel[]
     */
    protected function findProductLabelAbstractEntities(array $productAbstractIds)
    {
        return $this->getQueryContainer()->queryProductLabelProductAbstractByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLocalizedEntities(array $productAbstractIds)
    {
        return $this->getQueryContainer()->queryProductAbstractLocalizedByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLabelStorageEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        $productAbstractLabelStorageEntities = $this->getQueryContainer()->queryProductAbstractLabelStorageByIds($productAbstractIds)->find();
        $productAbstractStorageLabelEntitiesById = [];
        foreach ($productAbstractLabelStorageEntities as $productAbstractLabelStorageEntity) {
            $productAbstractStorageLabelEntitiesById[$productAbstractLabelStorageEntity->getFkProductAbstract()] = $productAbstractLabelStorageEntity;
        }

        return $productAbstractStorageLabelEntitiesById;
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->getFactory()->getStore()->getStoreName();
    }
}
