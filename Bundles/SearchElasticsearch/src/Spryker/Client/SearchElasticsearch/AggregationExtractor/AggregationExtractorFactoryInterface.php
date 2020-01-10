<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;

interface AggregationExtractorFactoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\SearchElasticsearch\AggregationExtractor\AggregationExtractorInterface
     */
    public function create(FacetConfigTransfer $facetConfigTransfer): AggregationExtractorInterface;
}
