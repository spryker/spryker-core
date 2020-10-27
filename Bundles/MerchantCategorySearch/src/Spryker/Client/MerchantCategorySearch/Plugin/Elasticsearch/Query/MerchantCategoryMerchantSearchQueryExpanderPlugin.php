<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantCategorySearch\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\Terms;
use InvalidArgumentException;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\MerchantSearch\MerchantSearchFactory getFactory()
 */
class MerchantCategoryMerchantSearchQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    protected const PARAMETER_CATEGORY_IDS = 'category-ids';

    /**
     * @uses \Spryker\Zed\MerchantCategorySearch\Communication\Plugin\MerchantSearch\MerchantCategoryMerchantSearchDataExpanderPlugin::CATEGORY_IDS
     */
    protected const CATEGORY_IDS = 'category-ids';

    /**
     * {@inheritDoc}
     * - Adds filter by merchant category id to query.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        $this->addMerchantCategoryIdFilterToQuery($searchQuery->getSearchQuery(), $requestParameters);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     * @param array $requestParameters
     *
     * @return void
     */
    protected function addMerchantCategoryIdFilterToQuery(Query $query, array $requestParameters = []): void
    {
        $boolQuery = $this->getBoolQuery($query);

        $categoryIds = $requestParameters[static::PARAMETER_CATEGORY_IDS] ?? [];

        if ($categoryIds) {
            $boolQuery->addMust($this->createCategoriesTermQuery($categoryIds));
        }
    }

    /**
     * @param string[] $categoryIds
     *
     * @return \Elastica\Query\Terms
     */
    protected function createCategoriesTermQuery(array $categoryIds): Terms
    {
        return new Terms(static::CATEGORY_IDS, $categoryIds);
    }

    /**
     * @param \Elastica\Query $query
     *
     * @throws \InvalidArgumentException
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function getBoolQuery(Query $query): BoolQuery
    {
        $boolQuery = $query->getQuery();
        if (!$boolQuery instanceof BoolQuery) {
            throw new InvalidArgumentException(sprintf(
                'Merchant Category query expander available only with %s, got: %s',
                BoolQuery::class,
                get_class($boolQuery)
            ));
        }

        return $boolQuery;
    }
}
