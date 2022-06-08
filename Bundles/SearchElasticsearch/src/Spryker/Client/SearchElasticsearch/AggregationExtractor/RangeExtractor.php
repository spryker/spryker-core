<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Generated\Shared\Transfer\RangeSearchResultTransfer;
use Spryker\Client\SearchElasticsearch\Aggregation\NumericFacetAggregation;
use Spryker\Client\SearchExtension\Dependency\Plugin\FacetSearchResultValueTransformerPluginInterface;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

class RangeExtractor extends AbstractAggregationExtractor implements AggregationExtractorInterface
{
    /**
     * @var \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected $facetConfigTransfer;

    /**
     * @var \Spryker\Client\SearchExtension\Dependency\Plugin\FacetSearchResultValueTransformerPluginInterface|null
     */
    protected $valueTransformerPlugin;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\FacetSearchResultValueTransformerPluginInterface|null $valueTransformerPlugin
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer, ?FacetSearchResultValueTransformerPluginInterface $valueTransformerPlugin = null)
    {
        $this->facetConfigTransfer = $facetConfigTransfer;
        $this->valueTransformerPlugin = $valueTransformerPlugin;
    }

    /**
     * @param array<string, mixed> $aggregations
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function extractDataFromAggregations(array $aggregations, array $requestParameters): TransferInterface
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
     * @param array<string, mixed> $aggregations
     * @param array<string, mixed> $requestParameters
     *
     * @return \Generated\Shared\Transfer\RangeSearchResultTransfer
     */
    protected function setRangeResultValues(
        RangeSearchResultTransfer $rangeResultTransfer,
        array $aggregations,
        array $requestParameters
    ): RangeSearchResultTransfer {
        [$min, $max] = $this->extractRangeData($aggregations);
        [$activeMin, $activeMax] = $this->getActiveRangeData($requestParameters, $min, $max);

        $rangeResultTransfer
            ->setMin((int)$min)
            ->setMax((int)$max)
            ->setActiveMin((int)$activeMin)
            ->setActiveMax((int)$activeMax);

        return $rangeResultTransfer;
    }

    /**
     * @param array<string, mixed> $requestParameters
     * @param float|null $min
     * @param float|null $max
     *
     * @return array<int|float|null>
     */
    protected function getActiveRangeData(array $requestParameters, ?float $min, ?float $max): array
    {
        $parameterName = $this->facetConfigTransfer->getParameterName();

        $activeMin = $requestParameters[$parameterName]['min'] ?? $min;
        $activeMax = $requestParameters[$parameterName]['max'] ?? $max;

        return [$activeMin, $activeMax];
    }

    /**
     * @param array $aggregation
     *
     * @return array<int|float|null>
     */
    protected function extractRangeData(array $aggregation)
    {
        $name = $this->facetConfigTransfer->getName();
        $fieldName = $this->getNestedFieldName($this->facetConfigTransfer);

        $nameFieldName = $fieldName . NumericFacetAggregation::NAME_SUFFIX;
        $statsFieldName = $fieldName . NumericFacetAggregation::STATS_SUFFIX;

        if (isset($aggregation[$nameFieldName][$statsFieldName])) {
            ['min' => $min, 'max' => $max] = $aggregation[$nameFieldName][$statsFieldName];
            if ($this->valueTransformerPlugin) {
                ['min' => $min, 'max' => $max] = $this->valueTransformerPlugin->transformForDisplay($aggregation[$nameFieldName][$statsFieldName]);
            }

            return [
                $min,
                $max,
            ];
        }

        foreach ($aggregation[$nameFieldName]['buckets'] as $nameBucket) {
            if ($nameBucket['key'] !== $name) {
                continue;
            }

            if (isset($nameBucket[$statsFieldName])) {
                ['min' => $min, 'max' => $max] = $nameBucket[$statsFieldName];
                if ($this->valueTransformerPlugin) {
                    ['min' => $min, 'max' => $max] = $this->valueTransformerPlugin->transformForDisplay($nameBucket[$statsFieldName]);
                }

                return [
                    $min,
                    $max,
                ];
            }
        }

        return [null, null];
    }
}
