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
        $fieldName = $this->facetConfigTransfer->getFieldName();

        $result = [
            'name' => $parameterName,
            'values' => $this->extractFacetData($aggregations, $fieldName),
        ];

        return $result;
    }

    /**
     * @param array $aggregation
     * @param string $fieldName
     *
     * @return array
     */
    protected function extractFacetData(array $aggregation, $fieldName)
    {
        $facetValues = [];
        // TODO: use the commented code for mixed aggregation filtering or remove it if not needed
        foreach ($aggregation/*[$fieldName][$fieldName]*/['buckets'] as $bucket) {
            $facetValues[$bucket['key']] = $bucket['doc_count'];
        }

        return $facetValues;
    }

}
