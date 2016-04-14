<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor;

class RangeExtractor extends FacetExtractor
{

    /**
     * @param array $aggregations
     * @param array $requestParameters
     *
     * @return array
     */
    public function extractDataFromAggregations(array $aggregations, array $requestParameters)
    {
        $result = parent::extractDataFromAggregations($aggregations, $requestParameters);

        $parameterName = $this->facetConfigTransfer->getParameterName();
        $fieldName = $this->facetConfigTransfer->getFieldName();

        $result['rangeValues'] = $this->extractRangeData($aggregations, $parameterName, $fieldName);

        return $result;
    }

    /**
     * @param array $aggregation
     * @param string $parameterName
     * @param string $fieldName
     *
     * @return array
     */
    protected function extractRangeData(array $aggregation, $parameterName, $fieldName)
    {
        $ranges = [];

        foreach ($aggregation[$fieldName . '-name']['buckets'] as $nameBucket) {
            if ($nameBucket['key'] !== $parameterName) {
                continue;
            }

            if (isset($nameBucket[$fieldName . '-min'])) {
                $ranges['min'] = $nameBucket[$fieldName . '-min']['value'];
            }
            if (isset($nameBucket[$fieldName . '-max'])) {
                $ranges['max'] = $nameBucket[$fieldName . '-max']['value'];
            }

            break;
        }

        return $ranges;
    }

}
