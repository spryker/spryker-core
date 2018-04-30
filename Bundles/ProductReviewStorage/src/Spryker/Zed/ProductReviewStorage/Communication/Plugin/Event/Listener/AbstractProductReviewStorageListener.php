<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\ProductReviewStorageTransfer;
use Orm\Zed\ProductReviewStorage\Persistence\SpyProductAbstractReviewStorage;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductReviewStorage\Persistence\ProductReviewStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductReviewStorage\Communication\ProductReviewStorageCommunicationFactory getFactory()
 */
abstract class AbstractProductReviewStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    protected function publish(array $productAbstractIds)
    {
        $productReviewEntities = $this->getQueryContainer()->queryProductReviewsByIdProductAbstracts($productAbstractIds)->find()->toArray();
        $productReviewStorageEntities = $this->findProductReviewStorageEntitiesByProductAbstractIds($productAbstractIds);

        if (!$productReviewEntities) {
            $this->deleteStorageData($productReviewStorageEntities);
        }

        $this->storeData($productReviewEntities, $productReviewStorageEntities);
    }

    /**
     * @param array $productReviewStorageEntities
     *
     * @return void
     */
    protected function deleteStorageData(array $productReviewStorageEntities)
    {
        foreach ($productReviewStorageEntities as $productReviewStorageEntity) {
            $productReviewStorageEntity->delete();
        }
    }

    /**
     * @param array $productReviewEntities
     * @param array $spyProductAbstractReviewStorageEntities
     *
     * @return void
     */
    protected function storeData(array $productReviewEntities, array $spyProductAbstractReviewStorageEntities)
    {
        foreach ($productReviewEntities as $productReviewEntity) {
            $idProduct = $productReviewEntity['idProductAbstract'];
            if (isset($spyProductAbstractReviewStorageEntities[$idProduct])) {
                $this->storeDataSet($productReviewEntity, $spyProductAbstractReviewStorageEntities[$idProduct]);
            } else {
                $this->storeDataSet($productReviewEntity);
            }
        }
    }

    /**
     * @param array $productReview
     * @param \Orm\Zed\ProductReviewStorage\Persistence\SpyProductAbstractReviewStorage|null $spyProductAbstractReviewStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(array $productReview, ?SpyProductAbstractReviewStorage $spyProductAbstractReviewStorageEntity = null)
    {
        if ($spyProductAbstractReviewStorageEntity === null) {
            $spyProductAbstractReviewStorageEntity = new SpyProductAbstractReviewStorage();
        }

        $productReviewStorageTransfer = (new ProductReviewStorageTransfer())->fromArray($productReview);
        $productReviewStorageTransfer->setAverageRating(round($productReviewStorageTransfer->getAverageRating(), 1));
        $spyProductAbstractReviewStorageEntity->setFkProductAbstract($productReview['idProductAbstract']);
        $spyProductAbstractReviewStorageEntity->setData($productReviewStorageTransfer->modifiedToArray());
        $spyProductAbstractReviewStorageEntity->setStore($this->getStoreName());
        $spyProductAbstractReviewStorageEntity->save();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductReviewStorageEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        $productAbstractReviewStorageEntities = $this->getQueryContainer()->queryProductAbstractReviewStorageByIds($productAbstractIds)->find();
        $productAbstractStorageReviewEntitiesById = [];
        foreach ($productAbstractReviewStorageEntities as $productAbstractReviewStorageEntity) {
            $productAbstractStorageReviewEntitiesById[$productAbstractReviewStorageEntity->getFkProductAbstract()] = $productAbstractReviewStorageEntity;
        }

        return $productAbstractStorageReviewEntitiesById;
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->getFactory()->getStore()->getStoreName();
    }
}
