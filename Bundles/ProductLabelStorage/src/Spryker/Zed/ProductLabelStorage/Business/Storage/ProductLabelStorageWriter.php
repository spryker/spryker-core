<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business\Storage;

use Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductAbstractLabelStorage;
use Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface;

class ProductLabelStorageWriter implements ProductLabelStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @deprecated Use `\Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()` instead.
     *
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     */
    public function __construct(ProductLabelStorageQueryContainerInterface $queryContainer, $isSendingToQueue)
    {
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
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $spyProductAbstractLabelStorageEntities = $this->findProductAbstractLabelStorageEntitiesByProductAbstractIds($productAbstractIds);
        foreach ($spyProductAbstractLabelStorageEntities as $spyProductAbstractLabelStorageEntity) {
            $spyProductAbstractLabelStorageEntity->delete();
        }
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

                continue;
            }

            $this->storeDataSet($productAbstractId, $productLabelsIds);
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
        $spyProductAbstractLabelStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
        $spyProductAbstractLabelStorageEntity->save();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabel[]
     */
    protected function findProductLabelAbstractEntities(array $productAbstractIds)
    {
        return $this->queryContainer->queryProductLabelProductAbstractByProductAbstractIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLocalizedEntities(array $productAbstractIds)
    {
        return $this->queryContainer->queryProductAbstractLocalizedByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLabelStorageEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        $productAbstractLabelStorageEntities = $this->queryContainer->queryProductAbstractLabelStorageByIds($productAbstractIds)->find();
        $productAbstractStorageLabelEntitiesById = [];
        foreach ($productAbstractLabelStorageEntities as $productAbstractLabelStorageEntity) {
            $productAbstractStorageLabelEntitiesById[$productAbstractLabelStorageEntity->getFkProductAbstract()] = $productAbstractLabelStorageEntity;
        }

        return $productAbstractStorageLabelEntitiesById;
    }
}
