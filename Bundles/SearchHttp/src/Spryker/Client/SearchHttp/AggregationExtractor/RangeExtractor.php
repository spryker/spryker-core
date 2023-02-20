<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Generated\Shared\Transfer\RangeSearchResultTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

class RangeExtractor implements AggregationExtractorInterface
{
    /**
     * @var string
     */
    protected const KEY_FROM = 'from';

    /**
     * @var string
     */
    protected const KEY_TO = 'to';

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
        $rangeData = $this->extractRangeData($aggregations);
        $activeRangeData = $this->getActiveRangeData($requestParameters, $rangeData[static::KEY_FROM], $rangeData[static::KEY_TO]);

        $rangeResultTransfer
            ->setMin((int)$rangeData[static::KEY_FROM])
            ->setMax((int)$rangeData[static::KEY_TO])
            ->setActiveMin((int)$activeRangeData[static::KEY_FROM])
            ->setActiveMax((int)$activeRangeData[static::KEY_TO]);

        return $rangeResultTransfer;
    }

    /**
     * @param array<string, mixed> $requestParameters
     * @param float|null $min
     * @param float|null $max
     *
     * @return array<string, mixed>
     */
    protected function getActiveRangeData(array $requestParameters, ?float $min, ?float $max): array
    {
        $parameterName = $this->facetConfigTransfer->getParameterName();

        $activeMin = $requestParameters[$parameterName][static::KEY_FROM] ?? $min;
        $activeMax = $requestParameters[$parameterName][static::KEY_TO] ?? $max;

        return [static::KEY_FROM => $activeMin, static::KEY_TO => $activeMax];
    }

    /**
     * @param array<string, mixed> $aggregation
     *
     * @return array<string, mixed>
     */
    protected function extractRangeData(array $aggregation): array
    {
        return [
            static::KEY_FROM => $aggregation[static::KEY_FROM] ?: null,
            static::KEY_TO => $aggregation[static::KEY_TO] ?: null,
        ];
    }
}
