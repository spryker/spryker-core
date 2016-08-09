<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Query;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Shared\Library\Currency\CurrencyManager;

class NestedPriceRangeQuery extends NestedRangeQuery
{

    /**
     * @var \Spryker\Shared\Library\Currency\CurrencyManager
     */
    protected $currencyManager;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param mixed $rangeValues
     * @param \Spryker\Client\Search\Model\Elasticsearch\Query\QueryBuilderInterface $queryBuilder
     * @param \Spryker\Shared\Library\Currency\CurrencyManager $currencyManager
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer, $rangeValues, QueryBuilderInterface $queryBuilder, CurrencyManager $currencyManager)
    {
        parent::__construct($facetConfigTransfer, $rangeValues, $queryBuilder);

        $this->currencyManager = $currencyManager;
    }

    /**
     * @return array
     */
    protected function getMinMaxValue()
    {
        list($minValue, $maxValue) = parent::getMinMaxValue();

        $minValue = $this->currencyManager->convertDecimalToCent($minValue);
        $maxValue = $this->currencyManager->convertDecimalToCent($maxValue);

        return [$minValue, $maxValue];
    }

}
