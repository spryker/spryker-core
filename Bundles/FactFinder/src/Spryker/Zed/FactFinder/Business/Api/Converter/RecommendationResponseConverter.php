<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Converter;

use FACTFinder\Adapter\Recommendation as FFRecommendationAdapter;
use Generated\Shared\Transfer\FFRecommendationResponseTransfer;

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
     * @return \Generated\Shared\Transfer\FFRecommendationResponseTransfer
     */
    public function convert()
    {
        $responseTransfer = new FFRecommendationResponseTransfer();
//        $responseTransfer->set();

        return $responseTransfer;
    }

}
