<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;

class PriceRangeExtractor extends RangeExtractor
{
    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface $moneyPlugin
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer, MoneyPluginInterface $moneyPlugin)
    {
        parent::__construct($facetConfigTransfer);

        $this->moneyPlugin = $moneyPlugin;
    }

    /**
     * @param array $aggregations
     * @param array $requestParameters
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function extractDataFromAggregations(array $aggregations, array $requestParameters): TransferInterface
    {
        /** @var \Generated\Shared\Transfer\RangeSearchResultTransfer $rangeResultTransfer */
        $rangeResultTransfer = parent::extractDataFromAggregations($aggregations, $requestParameters);

        return $rangeResultTransfer;
    }

    /**
     * @param array $requestParameters
     * @param float $min
     * @param float $max
     *
     * @return int[]
     */
    protected function getActiveRangeData(array $requestParameters, $min, $max)
    {
        $parameterName = $this->facetConfigTransfer->getParameterName();

        $activeMin = (isset($requestParameters[$parameterName]['min']) ? (float)$requestParameters[$parameterName]['min'] : null);
        $activeMax = (isset($requestParameters[$parameterName]['max']) ? (float)$requestParameters[$parameterName]['max'] : null);

        return [
            $activeMin !== null ? $this->moneyPlugin->convertDecimalToInteger($activeMin) : $min,
            $activeMax !== null ? $this->moneyPlugin->convertDecimalToInteger($activeMax) : $max,
        ];
    }
}
