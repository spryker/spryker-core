<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Converter;

use FACTFinder\Adapter\Recommendation as FFRecommendationAdapter;
use FACTFinder\Adapter\Search as FFSearchAdapter;
use FACTFinder\Adapter\Suggest as FFSuggestAdapter;
use FACTFinder\Adapter\TagCloud as FFTagCloudAdapter;

class ConverterFactory
{

    /**
     * @param \FACTFinder\Adapter\Search $searchAdapter
     * 
     * @return \Spryker\Zed\FactFinder\Business\Api\Converter\SearchResponseConverter
     */
    public function createSearchResponseConverter(FFSearchAdapter $searchAdapter)
    {
        return new SearchResponseConverter($searchAdapter);
    }

    /**
     * @param \FACTFinder\Adapter\Recommendation $recommendationAdapter
     *
     * @return \Spryker\Zed\FactFinder\Business\Api\Converter\RecommendationResponseConverter
     */
    public function createRecommendationResponseConverter(FFRecommendationAdapter $recommendationAdapter)
    {
        return new RecommendationResponseConverter($recommendationAdapter);
    }

    /**
     * @param \FACTFinder\Adapter\Suggest $suggestAdapter
     *
     * @return \Spryker\Zed\FactFinder\Business\Api\Converter\SuggestResponseConverter
     */
    public function createSuggestResponseConverter(FFSuggestAdapter $suggestAdapter)
    {
        return new SuggestResponseConverter($suggestAdapter);
    }

    /**
     * @param \FACTFinder\Adapter\TagCloud $tagCloudAdapter
     *
     * @return \Spryker\Zed\FactFinder\Business\Api\Converter\TagCloudResponseConverter
     */
    public function createTagCloudResponseConverter(FFTagCloudAdapter $tagCloudAdapter)
    {
        return new TagCloudResponseConverter($tagCloudAdapter);
    }

}
