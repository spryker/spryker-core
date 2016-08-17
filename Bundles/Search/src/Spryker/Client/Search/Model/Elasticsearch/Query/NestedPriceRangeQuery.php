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
     * @param array|string $rangeValues
     * @param \Spryker\Client\Search\Model\Elasticsearch\Query\QueryBuilderInterface $queryBuilder
     * @param \Spryker\Shared\Library\Currency\CurrencyManager $currencyManager
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer, $rangeValues, QueryBuilderInterface $queryBuilder, CurrencyManager $currencyManager)
    {
        $this->currencyManager = $currencyManager;

        parent::__construct($facetConfigTransfer, $rangeValues, $queryBuilder);
    }

    /**
     * @param array|string $rangeValues
     *
     * @return void
     */
    protected function setMinMaxValues($rangeValues)
    {
        parent::setMinMaxValues($rangeValues);

        $this->minValue = $this->currencyManager->convertDecimalToCent($this->minValue);
        $this->maxValue = $this->currencyManager->convertDecimalToCent($this->maxValue);
    }

}
