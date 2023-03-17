<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Generated\Shared\Transfer\RangeSearchResultTransfer;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToMoneyClientInterface;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

class PriceRangeExtractor extends RangeExtractor
{
    /**
     * @var \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToMoneyClientInterface
     */
    protected $moneyClient;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToMoneyClientInterface $moneyClient
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer, SearchHttpToMoneyClientInterface $moneyClient)
    {
        parent::__construct($facetConfigTransfer);

        $this->moneyClient = $moneyClient;
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
        $requestParameters = $this->convertFromFloatToIntegerRequestParametersPriceRange($requestParameters);

        $activeRange = $this->getActiveRangeData(
            $requestParameters,
            $aggregations[static::KEY_FROM],
            $aggregations[static::KEY_TO],
        );

        $rangeResultTransfer
            ->setMin($aggregations[static::KEY_FROM])
            ->setMax($aggregations[static::KEY_TO])
            ->setActiveMin($activeRange[static::KEY_FROM])
            ->setActiveMax($activeRange[static::KEY_TO]);

        return $rangeResultTransfer;
    }

    /**
     * @param array<string, mixed> $requestParameters
     *
     * @return array<string, mixed>
     */
    protected function convertFromFloatToIntegerRequestParametersPriceRange(array $requestParameters): array
    {
        $parameterName = $this->facetConfigTransfer->getParameterName();

        if (!isset($requestParameters[$parameterName])) {
            return $requestParameters;
        }

        $priceRange = $requestParameters[$parameterName];

        $requestParameters[$parameterName] = array_map([$this, 'convertFromFloatToInteger'], $priceRange);

        return $requestParameters;
    }

    /**
     * @param float|int|null $value
     *
     * @return int|null
     */
    protected function convertFromFloatToInteger(mixed $value): ?int
    {
        if ($value !== null) {
            return (int)$this->moneyClient->fromFloat((float)$value)->requireAmount()->getAmount();
        }

        return null;
    }
}
