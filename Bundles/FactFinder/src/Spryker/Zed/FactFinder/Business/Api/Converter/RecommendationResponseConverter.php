<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Converter;

use FACTFinder\Adapter\Recommendation as FFRecommendationAdapter;
use Generated\Shared\Transfer\FfRecommendationResponseTransfer;

class RecommendationResponseConverter extends BaseConverter
{

    /**
     * @var \FACTFinder\Adapter\Recommendation
     */
    protected $recommendationAdapter;

    /**
     * @param \FACTFinder\Adapter\Recommendation $recommendationAdapter
     */
    public function __construct(FFRecommendationAdapter $recommendationAdapter)
    {
        $this->recommendationAdapter = $recommendationAdapter;
    }

    /**
     * @return \Generated\Shared\Transfer\FfRecommendationResponseTransfer
     */
    public function convert()
    {
        $responseTransfer = new FfRecommendationResponseTransfer();
//        $responseTransfer->set();

        return $responseTransfer;
    }

}
