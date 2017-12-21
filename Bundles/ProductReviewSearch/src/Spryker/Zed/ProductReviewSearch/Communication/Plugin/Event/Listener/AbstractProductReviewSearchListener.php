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

        $this->storeData($productReviewEntities, $productReviewSearchEntitiesByProductReviewIds);
    }

    /**
     * @param SpyProductReview[] $productReviewEntities
     * @param array $spyProductReviewSearchEntities
     *
     * @return void
     */
    protected function storeData(array $productReviewEntities, array $spyProductReviewSearchEntities)
    {
        $localeNames = $this->getStore()->getLocales();

        foreach ($productReviewEntities as $productReviewEntity) {
            foreach ($localeNames as $localeName) {
                $idProduct = $productReviewEntity->getIdProductReview();
                if (isset($spyProductReviewSearchEntities[$idProduct][$localeName]))  {
                    $this->storeDataSet($productReviewEntity, $spyProductReviewSearchEntities[$idProduct][$localeName], $localeName);
                } else {
                    $this->storeDataSet($productReviewEntity, null, $localeName);
                }
            }
        }
    }

    /**
     * @param SpyProductReview $productReviewEntity
     * @param SpyProductReviewSearch|null $spyProductReviewSearchEntity
     * @param string $localeName
     *
     * @return void
     */
    protected function storeDataSet(SpyProductReview $productReviewEntity, SpyProductReviewSearch $spyProductReviewSearchEntity = null, $localeName)
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

        $result = $this->mapToSearchData($productReviewEntity, $localeName);

        $spyProductReviewSearchEntity->setFkProductReview($productReviewEntity->getIdProductReview());
        $spyProductReviewSearchEntity->setData($result);
        $spyProductReviewSearchEntity->setStructuredData($this->getFactory()->getUtilEncoding()->encodeJson($productReviewEntity->toArray()));
        $spyProductReviewSearchEntity->setLocale($localeName);
        $spyProductReviewSearchEntity->setStore($this->getStore()->getStoreName());
        $spyProductReviewSearchEntity->save();
    }

    /**
     * @param SpyProductReview $productReviewEntity
     * @param string $localeName
     *
     * @return array
     */
    protected function mapToSearchData(SpyProductReview $productReviewEntity, $localeName)
    {
        return [
            ProductReviewIndexMap::STORE => $this->getStore()->getStoreName(),
            ProductReviewIndexMap::LOCALE => $localeName,
            ProductReviewIndexMap::ID_PRODUCT_ABSTRACT => $productReviewEntity->getFkProductAbstract(),
            ProductReviewIndexMap::RATING => $productReviewEntity->getRating(),
            ProductReviewIndexMap::SEARCH_RESULT_DATA => $this->getSearchResultData($productReviewEntity),
            ProductReviewIndexMap::CREATED_AT => $productReviewEntity->getCreatedAt()->format('Y-m-d H:i:s')
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
        $productReviewSearchReviewEntitiesByIdAndLocale = [];
        foreach ($productReviewSearchEntities as $productReviewReviewSearchEntity) {
            $productReviewSearchReviewEntitiesByIdAndLocale[$productReviewReviewSearchEntity->getFkProductReview()][$productReviewReviewSearchEntity->getLocale()] = $productReviewReviewSearchEntity;
        }

        return $productReviewSearchReviewEntitiesByIdAndLocale;
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getFactory()->getStore();
    }

    /**
     * @param SpyProductReview $spyProductReview
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
