<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Business\Search;

use Generated\Shared\Search\ProductReviewIndexMap;
use Generated\Shared\Transfer\DataMappingContextTransfer;
use Generated\Shared\Transfer\ProductReviewSearchTransfer;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Orm\Zed\ProductReview\Persistence\SpyProductReview;
use Orm\Zed\ProductReviewSearch\Persistence\SpyProductReviewSearch;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\ProductReviewSearch\Dependency\Facade\ProductReviewSearchToSearchFacadeInterface;
use Spryker\Zed\ProductReviewSearch\Dependency\Service\ProductReviewSearchToUtilEncodingInterface;
use Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchQueryContainerInterface;

class ProductReviewSearchWriter implements ProductReviewSearchWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductReviewSearch\Dependency\Service\ProductReviewSearchToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @var \Spryker\Zed\ProductReviewSearch\Dependency\Facade\ProductReviewSearchToSearchFacadeInterface
     */
    protected $searchFacade;

    /**
     * @param \Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductReviewSearch\Dependency\Service\ProductReviewSearchToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Zed\ProductReviewSearch\Dependency\Facade\ProductReviewSearchToSearchFacadeInterface $searchFacade
     * @param bool $isSendingToQueue
     */
    public function __construct(
        ProductReviewSearchQueryContainerInterface $queryContainer,
        ProductReviewSearchToUtilEncodingInterface $utilEncodingService,
        Store $store,
        ProductReviewSearchToSearchFacadeInterface $searchFacade,
        $isSendingToQueue
    ) {
        $this->queryContainer = $queryContainer;
        $this->utilEncodingService = $utilEncodingService;
        $this->store = $store;
        $this->searchFacade = $searchFacade;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array $productReviewIds
     *
     * @return void
     */
    public function publish(array $productReviewIds)
    {
        $productReviewEntities = $this->queryContainer->queryProductReviewsByIdProductReviews($productReviewIds)->find()->getData();
        $productReviewSearchEntitiesByProductReviewIds = $this->findProductReviewSearchEntitiesByProductReviewIds($productReviewIds);

        if (!$productReviewEntities) {
            $this->deleteSearchData($productReviewSearchEntitiesByProductReviewIds);
        }

        $this->storeData($productReviewEntities, $productReviewSearchEntitiesByProductReviewIds);
    }

    /**
     * @param array $productReviewIds
     *
     * @return void
     */
    public function unpublish(array $productReviewIds)
    {
        $productReviewSearchEntities = $this->findProductReviewSearchEntitiesByProductReviewIds($productReviewIds);
        foreach ($productReviewSearchEntities as $productReviewSearchEntity) {
            $productReviewSearchEntity->delete();
        }
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

                continue;
            }

            $this->storeDataSet($productReviewEntity, null);
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
        $spyProductReviewSearchEntity->setStructuredData($this->utilEncodingService->encodeJson($productReviewEntity->toArray()));
        $spyProductReviewSearchEntity->setIsSendingToQueue($this->isSendingToQueue);
        $spyProductReviewSearchEntity->save();
    }

    /**
     * @param \Orm\Zed\ProductReview\Persistence\SpyProductReview $productReviewEntity
     *
     * @return array
     */
    protected function mapToSearchData(SpyProductReview $productReviewEntity)
    {
        if (method_exists($this->searchFacade, 'mapRawDataToSearchData')) {
            return $this->mapRawDataToSearchData($productReviewEntity);
        }

        return [
            ProductReviewIndexMap::STORE => $this->store->getStoreName(),
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
        $productReviewSearchEntities = $this->queryContainer->queryProductReviewSearchByIds($productReviewIds)->find();
        $productReviewSearchReviewEntitiesById = [];
        foreach ($productReviewSearchEntities as $productReviewReviewSearchEntity) {
            $productReviewSearchReviewEntitiesById[$productReviewReviewSearchEntity->getFkProductReview()] = $productReviewReviewSearchEntity;
        }

        return $productReviewSearchReviewEntitiesById;
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

    /**
     * @param \Orm\Zed\ProductReview\Persistence\SpyProductReview $productReviewEntity
     *
     * @return array
     */
    protected function mapRawDataToSearchData(SpyProductReview $productReviewEntity)
    {
        $dataMappingContextTransfer = new DataMappingContextTransfer();
        $dataMappingContextTransfer->setResourceName('product_review');

        return $this->searchFacade->mapRawDataToSearchData($productReviewEntity->toArray(), $dataMappingContextTransfer);
    }
}
