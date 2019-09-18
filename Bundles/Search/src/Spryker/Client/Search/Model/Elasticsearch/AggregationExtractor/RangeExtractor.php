<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Generated\Shared\Transfer\RangeSearchResultTransfer;
use Spryker\Client\Search\Model\Elasticsearch\Aggregation\NumericFacetAggregation;

class RangeExtractor extends AbstractAggregationExtractor implements AggregationExtractorInterface
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
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function extractDataFromAggregations(array $aggregations, array $requestParameters)
    {
        $rangeResultTransfer = new RangeSearchResultTransfer();
        $rangeResultTransfer
            ->setName($this->facetConfigTransfer->getName())
            ->setConfig(clone $this->facetConfigTransfer);

        $rangeResultTransfer = $this->setRangeResultValues($rangeResultTransfer, $aggregations, $requestParameters);

        return $rangeResultTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RangeSearchResultTransfer $rangeResultTransfer
     * @param array $aggregations
     * @param array $requestParameters
     *
     * @return \Generated\Shared\Transfer\RangeSearchResultTransfer
     */
    protected function setRangeResultValues(RangeSearchResultTransfer $rangeResultTransfer, array $aggregations, array $requestParameters)
    {
        [$min, $max] = $this->extractRangeData($aggregations);
        [$activeMin, $activeMax] = $this->getActiveRangeData($requestParameters, $min, $max);

        $rangeResultTransfer
            ->setMin((int)$min)
            ->setMax((int)$max)
            ->setActiveMin($this->resolveMin((int)$min, (int)$activeMin))
            ->setActiveMax($this->resolveMax((int)$max, (int)$activeMax));

        return $rangeResultTransfer;
    }

    /**
     * Resolves the aggregation range minimum.
     *
     * @param int $facetMin
     * @param int $selectedMin
     *
     * @return int
     */
    protected function resolveMin(int $facetMin, int $selectedMin): int
    {
        return max($facetMin, $selectedMin);
    }

    /**
     * Resolves the aggregation range maximum.
     *
     * @param int $facetMax
     * @param int $selectedMax
     *
     * @return int
     */
    protected function resolveMax(int $facetMax, int $selectedMax): int
    {
        return min($facetMax, $selectedMax);
    }

    /**
     * @param array $requestParameters
     * @param float $min
     * @param float $max
     *
     * @return array
     */
    protected function getActiveRangeData(array $requestParameters, $min, $max)
    {
        $parameterName = $this->facetConfigTransfer->getParameterName();

        $activeMin = (isset($requestParameters[$parameterName]['min']) ? $requestParameters[$parameterName]['min'] : $min);
        $activeMax = (isset($requestParameters[$parameterName]['max']) ? $requestParameters[$parameterName]['max'] : $max);

        return [$activeMin, $activeMax];
    }

    /**
     * @param array $aggregation
     *
     * @return array
     */
    protected function extractRangeData(array $aggregation)
    {
        $name = $this->facetConfigTransfer->getName();
        $fieldName = $this->getNestedFieldName($this->facetConfigTransfer);

        $nameFieldName = $fieldName . NumericFacetAggregation::NAME_SUFFIX;
        $statsFieldName = $fieldName . NumericFacetAggregation::STATS_SUFFIX;

        if (isset($aggregation[$nameFieldName][$statsFieldName])) {
            return [
                $aggregation[$nameFieldName][$statsFieldName]['min'],
                $aggregation[$nameFieldName][$statsFieldName]['max'],
            ];
        }

        foreach ($aggregation[$nameFieldName]['buckets'] as $nameBucket) {
            if ($nameBucket['key'] !== $name) {
                continue;
            }

            if (isset($nameBucket[$statsFieldName])) {
                return [
                    $nameBucket[$statsFieldName]['min'],
                    $nameBucket[$statsFieldName]['max'],
                ];
            }
        }

        return [null, null];
    }
}
