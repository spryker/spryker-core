<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;

class FacetExtractor implements AggregationExtractorInterface
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
            'values' => $this->extractFacetData($aggregations, $parameterName, $fieldName),
        ];

        if (isset($requestParameters[$parameterName])) {
            $result['activeValue'] = $requestParameters[$parameterName];
        }

        return $result;
    }

    /**
     * @param array $aggregation
     * @param string $parameterName
     * @param string $fieldName
     *
     * @return array
     */
    protected function extractFacetData(array $aggregation, $parameterName, $fieldName)
    {
        $facetValues = [];
        foreach ($aggregation[$fieldName . '-name']['buckets'] as $nameBucket) {
            if ($nameBucket['key'] !== $parameterName) {
                continue;
            }

            foreach ($nameBucket[$fieldName . '-value']['buckets'] as $valueBucket) {
                $facetValues[$valueBucket['key']] = $valueBucket['doc_count'];
            }

            break;
        }

        return $facetValues;
    }

}
