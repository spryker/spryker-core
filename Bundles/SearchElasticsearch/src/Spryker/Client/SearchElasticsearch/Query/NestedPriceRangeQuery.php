<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Query;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\SearchElasticsearch\Dependency\Client\SearchElasticsearchToMoneyClientInterface;

class NestedPriceRangeQuery extends NestedRangeQuery
{
    /**
     * @var \Spryker\Client\SearchElasticsearch\Dependency\Client\SearchElasticsearchToMoneyClientInterface
     */
    protected $moneyClient;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param array|string $rangeValues
     * @param \Spryker\Client\SearchElasticsearch\Query\QueryBuilderInterface $queryBuilder
     * @param \Spryker\Client\SearchElasticsearch\Dependency\Client\SearchElasticsearchToMoneyClientInterface $moneyClient
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer, $rangeValues, QueryBuilderInterface $queryBuilder, SearchElasticsearchToMoneyClientInterface $moneyClient)
    {
        $this->moneyClient = $moneyClient;

        parent::__construct($facetConfigTransfer, $rangeValues, $queryBuilder);
    }

    /**
     * @param array|string $rangeValues
     *
     * @return void
     */
    protected function setMinMaxValues($rangeValues): void
    {
        parent::setMinMaxValues($rangeValues);

        if ($this->minValue !== null) {
            $this->minValue = (string)$this->convertFromFloatToInteger((float)$this->minValue);
        }

        if ($this->maxValue !== null) {
            $this->maxValue = (string)$this->convertFromFloatToInteger((float)$this->maxValue);
        }
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
