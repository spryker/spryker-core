<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewCollector\Business\Collector\Search;

use Generated\Shared\Search\ProductReviewIndexMap;
use Generated\Shared\Transfer\ProductReviewTransfer;
use Generated\Shared\Transfer\SearchCollectorConfigurationTransfer;
use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\ProductReview\ProductReviewConfig;
use Spryker\Zed\Collector\Business\Collector\Search\AbstractConfigurableSearchPropelCollector;
use Spryker\Zed\Collector\CollectorConfig;
use Spryker\Zed\ProductReviewCollector\Persistence\Search\Propel\ProductReviewSearchCollectorQuery;

class ProductReviewCollector extends AbstractConfigurableSearchPropelCollector
{

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface $utilDataReaderService
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(UtilDataReaderServiceInterface $utilDataReaderService, Store $store)
    {
        parent::__construct($utilDataReaderService);

        $this->store = $store;
    }

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return ProductReviewConfig::RESOURCE_TYPE_PRODUCT_REVIEW;
    }

    /**
     * @return \Generated\Shared\Transfer\SearchCollectorConfigurationTransfer
     */
    protected function getCollectorConfiguration()
    {
        $searchCollectorConfigurationTransfer = new SearchCollectorConfigurationTransfer();
        $searchCollectorConfigurationTransfer->setTypeName(ProductReviewConfig::ELASTICSEARCH_INDEX_TYPE_NAME);

        return $searchCollectorConfigurationTransfer;
    }

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        $result = [
            ProductReviewIndexMap::STORE => $this->store->getStoreName(),
            ProductReviewIndexMap::LOCALE => $this->locale->getLocaleName(),
            ProductReviewIndexMap::ID_PRODUCT_ABSTRACT => $collectItemData[ProductReviewSearchCollectorQuery::FIELD_FK_PRODUCT_ABSTRACT],
            ProductReviewIndexMap::RATING => $collectItemData[ProductReviewSearchCollectorQuery::FIELD_RATING],
            ProductReviewIndexMap::SEARCH_RESULT_DATA => $this->getSearchResultData($collectItemData),
        ];

        $result = $this->addExtraCollectorFields($result, $collectItemData);

        return $result;
    }

    /**
     * @param array $result
     * @param array $collectItemData
     *
     * @return array
     */
    protected function addExtraCollectorFields(array $result, array $collectItemData)
    {
        $result[CollectorConfig::COLLECTOR_TOUCH_ID] = $collectItemData[CollectorConfig::COLLECTOR_TOUCH_ID];
        $result[CollectorConfig::COLLECTOR_RESOURCE_ID] = $collectItemData[CollectorConfig::COLLECTOR_RESOURCE_ID];
        $result[CollectorConfig::COLLECTOR_SEARCH_KEY] = $collectItemData[CollectorConfig::COLLECTOR_SEARCH_KEY];

        return $result;
    }

    /**
     * @param array $collectItemData
     *
     * @return array
     */
    protected function getSearchResultData(array $collectItemData)
    {
        $productReviewTransfer = new ProductReviewTransfer();
        $productReviewTransfer->fromArray($collectItemData, true);

        return $productReviewTransfer->modifiedToArray();
    }

}
