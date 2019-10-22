<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\SearchElasticsearch\Dependency\Client\SearchElasticsearchToMoneyClientInterface;

class PriceRangeExtractor extends RangeExtractor
{
    /**
     * @var \Spryker\Client\SearchElasticsearch\Dependency\Client\SearchElasticsearchToMoneyClientInterface
     */
    protected $moneyClient;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param \Spryker\Client\SearchElasticsearch\Dependency\Client\SearchElasticsearchToMoneyClientInterface $moneyClient
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer, SearchElasticsearchToMoneyClientInterface $moneyClient)
    {
        parent::__construct($facetConfigTransfer);

        $this->moneyClient = $moneyClient;
    }

    /**
     * @param array $requestParameters
     * @param float|null $min
     * @param float|null $max
     *
     * @return (int|float|null)[]
     */
    protected function getActiveRangeData(array $requestParameters, ?float $min, ?float $max): array
    {
        [$activeMin, $activeMax] = $this->getActiveRangeParameters($requestParameters);

        return [
            $activeMin !== null ? $this->convertFromFloatToInteger($activeMin) : $min,
            $activeMax !== null ? $this->convertFromFloatToInteger($activeMax) : $max,
        ];
    }

    /**
     * @param array $requestParameters
     *
     * @return (float|null)[]
     */
    protected function getActiveRangeParameters(array $requestParameters): array
    {
        $parameterName = $this->facetConfigTransfer->getParameterName();

        $activeMin = null;
        if (!empty($requestParameters[$parameterName]['min'])) {
            $activeMin = (float)$requestParameters[$parameterName]['min'];
        }

        $activeMax = null;
        if (!empty($requestParameters[$parameterName]['max'])) {
            $activeMax = (float)$requestParameters[$parameterName]['max'];
        }

        return [$activeMin, $activeMax];
    }

    /**
     * @param float $value
     *
     * @return int
     */
    protected function convertFromFloatToInteger(float $value): int
    {
        $moneyTransfer = $this->moneyClient->fromFloat((float)$value);

        return (int)$moneyTransfer->requireAmount()->getAmount();
    }
}
