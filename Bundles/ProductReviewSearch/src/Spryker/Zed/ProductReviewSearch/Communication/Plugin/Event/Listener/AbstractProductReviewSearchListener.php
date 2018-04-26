<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Communication\Plugin\Event\Listener;

use Generated\Shared\Search\ProductReviewIndexMap;
use Generated\Shared\Transfer\ProductReviewSearchTransfer;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Orm\Zed\ProductReview\Persistence\SpyProductReview;
use Orm\Zed\ProductReviewSearch\Persistence\SpyProductReviewSearch;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductReviewSearch\Communication\ProductReviewSearchCommunicationFactory getFactory()
 */
abstract class AbstractProductReviewSearchListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * @param array $productReviewIds
     *
     * @return void
     */
    protected function publish(array $productReviewIds)
    {
        $productReviewEntities = $this->getQueryContainer()->queryProductReviewsByIdProductReviews($productReviewIds)->find()->getData();
        $productReviewSearchEntitiesByProductReviewIds = $this->findProductReviewSearchEntitiesByProductReviewIds($productReviewIds);

        if (!$productReviewEntities) {
            $this->deleteSearchData($productReviewSearchEntitiesByProductReviewIds);
        }

        $this->storeData($productReviewEntities, $productReviewSearchEntitiesByProductReviewIds);
    }

    /**
     * @param \Orm\Zed\ProductReviewSearch\Persistence\SpyProductReviewSearch[] $productReviewSearchEntities
     *
     * @return void
     */
    protected function deleteSearchData(array $productReviewSearchEntities)
    {
        foreach ($productReviewSearchEntities as $productReviewSearchEntity) {
            $productReviewSearchEntity->delete();
        }
    }

    /**
     * @param \Orm\Zed\ProductReview\Persistence\SpyProductReview[] $productReviewEntities
     * @param array $spyProductReviewSearchEntities
     *
     * @return void
     */
    protected function storeData(array $productReviewEntities, array $spyProductReviewSearchEntities)
    {
        foreach ($productReviewEntities as $productReviewEntity) {
            $idProductReview = $productReviewEntity->getIdProductReview();
            if (isset($spyProductReviewSearchEntities[$idProductReview])) {
                $this->storeDataSet($productReviewEntity, $spyProductReviewSearchEntities[$idProductReview]);
            } else {
                $this->storeDataSet($productReviewEntity, null);
            }
        }
    }

    /**
     * @param \Orm\Zed\ProductReview\Persistence\SpyProductReview $productReviewEntity
     * @param \Orm\Zed\ProductReviewSearch\Persistence\SpyProductReviewSearch|null $spyProductReviewSearchEntity
     *
     * @return void
     */
    protected function storeDataSet(SpyProductReview $productReviewEntity, ?SpyProductReviewSearch $spyProductReviewSearchEntity = null)
    {
        if ($spyProductReviewSearchEntity === null) {
            $spyProductReviewSearchEntity = new SpyProductReviewSearch();
        }

        if ($productReviewEntity->getStatus() !== SpyProductReviewTableMap::COL_STATUS_APPROVED) {
            if (!$spyProductReviewSearchEntity->isNew()) {
                $spyProductReviewSearchEntity->delete();
            }

            return;
        }

        $result = $this->mapToSearchData($productReviewEntity);

        $spyProductReviewSearchEntity->setFkProductReview($productReviewEntity->getIdProductReview());
        $spyProductReviewSearchEntity->setData($result);
        $spyProductReviewSearchEntity->setStructuredData($this->getFactory()->getUtilEncoding()->encodeJson($productReviewEntity->toArray()));
        $spyProductReviewSearchEntity->setStore($this->getStore()->getStoreName());
        $spyProductReviewSearchEntity->save();
    }

    /**
     * @param \Orm\Zed\ProductReview\Persistence\SpyProductReview $productReviewEntity
     *
     * @return array
     */
    protected function mapToSearchData(SpyProductReview $productReviewEntity)
    {
        return [
            ProductReviewIndexMap::STORE => $this->getStore()->getStoreName(),
            ProductReviewIndexMap::ID_PRODUCT_ABSTRACT => $productReviewEntity->getFkProductAbstract(),
            ProductReviewIndexMap::RATING => $productReviewEntity->getRating(),
            ProductReviewIndexMap::SEARCH_RESULT_DATA => $this->getSearchResultData($productReviewEntity),
            ProductReviewIndexMap::CREATED_AT => $productReviewEntity->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @param array $productReviewIds
     *
     * @return array
     */
    protected function findProductReviewSearchEntitiesByProductReviewIds(array $productReviewIds)
    {
        $productReviewSearchEntities = $this->getQueryContainer()->queryProductReviewSearchByIds($productReviewIds)->find();
        $productReviewSearchReviewEntitiesById = [];
        foreach ($productReviewSearchEntities as $productReviewReviewSearchEntity) {
            $productReviewSearchReviewEntitiesById[$productReviewReviewSearchEntity->getFkProductReview()] = $productReviewReviewSearchEntity;
        }

        return $productReviewSearchReviewEntitiesById;
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getFactory()->getStore();
    }

    /**
     * @param \Orm\Zed\ProductReview\Persistence\SpyProductReview $spyProductReview
     *
     * @return array
     */
    protected function getSearchResultData(SpyProductReview $spyProductReview)
    {
        $productReviewTransfer = new ProductReviewSearchTransfer();
        $productReviewTransfer->fromArray($spyProductReview->toArray(), true);

        return $productReviewTransfer->modifiedToArray();
    }
}
