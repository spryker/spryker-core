<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantCategorySearch\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
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
    protected const PARAMETER_CATEGORY_KEYS = 'category-keys';

    /**
     * @uses \Spryker\Zed\MerchantCategorySearch\Communication\Plugin\MerchantSearch\MerchantCategoryMerchantSearchDataExpanderPlugin::CATEGORY_KEYS
     */
    protected const CATEGORY_KEYS = 'category-keys';

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
        $this->addMerchantCategoryKeyFilterToQuery($searchQuery->getSearchQuery(), $requestParameters);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     * @param array $requestParameters
     *
     * @return void
     */
    protected function addMerchantCategoryKeyFilterToQuery(Query $query, array $requestParameters = []): void
    {
        $boolQuery = $this->getBoolQuery($query);

        $categoryKeys = $requestParameters[static::PARAMETER_CATEGORY_KEYS] ?? [];

        if ($categoryKeys) {
            $boolQuery->addMust($this->createCategoriesTermQuery($categoryKeys));
        }
    }

    /**
     * @param string[] $categoryKeys
     *
     * @return \Elastica\Query\Terms
     */
    protected function createCategoriesTermQuery(array $categoryKeys): Terms
    {
        return new Terms(static::CATEGORY_KEYS, $categoryKeys);
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
