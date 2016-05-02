<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;

class CategoryExtractor implements AggregationExtractorInterface
{

    /**
     * @var \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected $facetConfigTransfer;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer)
    {
        $this->facetConfigTransfer = $facetConfigTransfer;
    }

    /**
     * @param array $aggregations
     * @param array $requestParameters
     *
     * @return array
     */
    public function extractDataFromAggregations(array $aggregations, array $requestParameters)
    {
        $parameterName = $this->facetConfigTransfer->getParameterName();

        $result = [
            'name' => $parameterName,
            'values' => $this->extractFacetData($aggregations),
        ];

        return $result;
    }

    /**
     * @param array $aggregation
     *
     * @return array
     */
    protected function extractFacetData(array $aggregation)
    {
        $facetValues = [];
        foreach ($aggregation['buckets'] as $bucket) {
            $facetValues[$bucket['key']] = $bucket['doc_count'];
        }

        return $facetValues;
    }

}
