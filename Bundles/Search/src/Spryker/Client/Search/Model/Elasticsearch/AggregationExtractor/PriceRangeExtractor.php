<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Shared\Library\Currency\CurrencyManager;

class PriceRangeExtractor extends RangeExtractor
{

    /**
     * @var \Spryker\Shared\Library\Currency\CurrencyManager
     */
    protected $currencyManager;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param \Spryker\Shared\Library\Currency\CurrencyManager $currencyManager
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer, CurrencyManager $currencyManager)
    {
        parent::__construct($facetConfigTransfer);

        $this->currencyManager = $currencyManager;
    }

    /**
     * @param array $aggregations
     * @param array $requestParameters
     *
     * @return \Spryker\Shared\Transfer\TransferInterface
     */
    public function extractDataFromAggregations(array $aggregations, array $requestParameters)
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
     * @return array
     */
    protected function getActiveRangeData(array $requestParameters, $min, $max)
    {
        $parameterName = $this->facetConfigTransfer->getParameterName();

        $activeMin = (isset($requestParameters[$parameterName]['min']) ? $requestParameters[$parameterName]['min'] : null);
        $activeMax = (isset($requestParameters[$parameterName]['max']) ? $requestParameters[$parameterName]['max'] : null);

        return [
            $activeMin !== null ? $this->currencyManager->convertDecimalToCent($activeMin) : $min,
            $activeMax !== null ? $this->currencyManager->convertDecimalToCent($activeMax) : $max,
        ];
    }

}
