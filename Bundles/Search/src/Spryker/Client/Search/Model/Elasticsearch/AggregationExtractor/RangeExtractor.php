<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Generated\Shared\Transfer\RangeSearchResultTransfer;

class RangeExtractor implements AggregationExtractorInterface
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
     * @return \Spryker\Shared\Transfer\TransferInterface
     */
    public function extractDataFromAggregations(array $aggregations, array $requestParameters)
    {
        $parameterName = $this->facetConfigTransfer->getParameterName();
        $fieldName = $this->facetConfigTransfer->getFieldName();

        list($min, $max) = $this->extractRangeData($aggregations, $parameterName, $fieldName);

        $rangeResultTransfer = new RangeSearchResultTransfer();
        $rangeResultTransfer
            ->setName($parameterName)
            ->setMin($min)
            ->setMax($max);

        return $rangeResultTransfer;
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
        foreach ($aggregation[$fieldName . '-name']['buckets'] as $nameBucket) {
            if ($nameBucket['key'] !== $parameterName) {
                continue;
            }

            if (isset($nameBucket[$fieldName . '-stats'])) {
                return [
                    $nameBucket[$fieldName . '-stats']['min'],
                    $nameBucket[$fieldName . '-stats']['max']
                ];
            }
        }

        return [null, null];
    }

}
