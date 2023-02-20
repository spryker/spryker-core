<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;

interface AggregationExtractorFactoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\SearchHttp\AggregationExtractor\AggregationExtractorInterface
     */
    public function create(FacetConfigTransfer $facetConfigTransfer): AggregationExtractorInterface;
}
