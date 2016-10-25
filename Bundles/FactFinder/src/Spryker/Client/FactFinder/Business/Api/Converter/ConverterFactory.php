<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Business\Api\Converter;

use FACTFinder\Adapter\ProductCampaign as FFProductCampaignAdapter;
use FACTFinder\Adapter\Recommendation as FFRecommendationAdapter;
use FACTFinder\Adapter\Search as FFSearchAdapter;
use FACTFinder\Adapter\SimilarRecords as FFSimilarRecordsAdapter;
use FACTFinder\Adapter\Suggest as FFSuggestAdapter;
use FACTFinder\Adapter\TagCloud as FFTagCloudAdapter;
use FACTFinder\Adapter\Tracking as FFTrackingAdapter;
use Spryker\Client\FactFinder\Business\Api\Converter\Data\AdvisorQuestionConverter;
use Spryker\Client\FactFinder\Business\Api\Converter\Data\ItemConverter;
use Spryker\Client\FactFinder\Business\Api\Converter\Data\PagingConverter;
use Spryker\Client\FactFinder\Business\Api\Converter\Data\RecordConverter;

class ConverterFactory
{

    /**
     * @param \FACTFinder\Adapter\Search $searchAdapter
     *
     * @return \Spryker\Client\FactFinder\Business\Api\Converter\SearchResponseConverter
     */
    public function createSearchResponseConverter(FFSearchAdapter $searchAdapter)
    {
        return new SearchResponseConverter(
            $searchAdapter,
            $this->createDataPagingConverter(),
            $this->createDataItemConverter(),
            $this->createDataRecordConverter(),
            $this->createDataAdvisorQuestionConverter()
        );
    }

    /**
     * @param \FACTFinder\Adapter\Recommendation $recommendationAdapter
     *
     * @return \Spryker\Client\FactFinder\Business\Api\Converter\RecommendationResponseConverter
     */
    public function createRecommendationResponseConverter(FFRecommendationAdapter $recommendationAdapter)
    {
        return new RecommendationResponseConverter($recommendationAdapter);
    }

    /**
     * @param \FACTFinder\Adapter\Suggest $suggestAdapter
     *
     * @return \Spryker\Client\FactFinder\Business\Api\Converter\SuggestResponseConverter
     */
    public function createSuggestResponseConverter(FFSuggestAdapter $suggestAdapter)
    {
        return new SuggestResponseConverter($suggestAdapter);
    }

    /**
     * @param \FACTFinder\Adapter\TagCloud $tagCloudAdapter
     *
     * @return \Spryker\Client\FactFinder\Business\Api\Converter\TagCloudResponseConverter
     */
    public function createTagCloudResponseConverter(FFTagCloudAdapter $tagCloudAdapter)
    {
        return new TagCloudResponseConverter($tagCloudAdapter);
    }

    /**
     * @param \FACTFinder\Adapter\Tracking $trackingAdapter
     *
     * @return \Spryker\Client\FactFinder\Business\Api\Converter\TrackingResponseConverter
     */
    public function createTrackingResponseConverter(FFTrackingAdapter $trackingAdapter)
    {
        return new TrackingResponseConverter($trackingAdapter);
    }

    /**
     * @param \FACTFinder\Adapter\SimilarRecords $similarRecordsAdapter
     *
     * @return \Spryker\Client\FactFinder\Business\Api\Converter\SimilarRecordsResponseConverter
     */
    public function createSimilarRecordsResponseConverter(FFSimilarRecordsAdapter $similarRecordsAdapter)
    {
        return new SimilarRecordsResponseConverter($similarRecordsAdapter);
    }

    /**
     * @param \FACTFinder\Adapter\ProductCampaign $productCampaignAdapter
     *
     * @return \Spryker\Client\FactFinder\Business\Api\Converter\ProductCampaignResponseConverter
     */
    public function createProductCampaignResponseConverter(FFProductCampaignAdapter $productCampaignAdapter)
    {
        return new ProductCampaignResponseConverter($productCampaignAdapter);
    }

    /**
     * @return \Spryker\Client\FactFinder\Business\Api\Converter\Data\ItemConverter
     */
    public function createDataItemConverter()
    {
        return new ItemConverter();
    }

    /**
     * @return \Spryker\Client\FactFinder\Business\Api\Converter\Data\PagingConverter
     */
    public function createDataPagingConverter()
    {
        return new PagingConverter(
            $this->createDataItemConverter()
        );
    }

    /**
     * @return \Spryker\Client\FactFinder\Business\Api\Converter\Data\RecordConverter
     */
    public function createDataRecordConverter()
    {
        return new RecordConverter();
    }

    /**
     * @return \Spryker\Client\FactFinder\Business\Api\Converter\Data\AdvisorQuestionConverter
     */
    public function createDataAdvisorQuestionConverter()
    {
        return new AdvisorQuestionConverter(
            $this->createDataItemConverter()
        );
    }

}
