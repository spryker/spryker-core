<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Terms;
use Generated\Shared\Search\ServicePointIndexMap;
use InvalidArgumentException;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\ServicePointSearch\ServicePointSearchFactory getFactory()
 */
class ServiceTypesServicePointSearchQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @var string
     */
    protected const PARAMETER_SERVICE_TYPES = 'serviceTypes';

    /**
     * {@inheritDoc}
     * - Expands query with service types filter.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        $this->addServiceTypesFilterToQuery($searchQuery->getSearchQuery(), $requestParameters);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     * @param array<string, mixed> $requestParameters
     *
     * @return void
     */
    protected function addServiceTypesFilterToQuery(Query $query, array $requestParameters = []): void
    {
        $serviceTypes = $requestParameters[static::PARAMETER_SERVICE_TYPES] ?? [];
        if (!$serviceTypes) {
            return;
        }

        $filterQuery = (new Terms(ServicePointIndexMap::SERVICE_TYPES, $serviceTypes));

        $this->getBoolQuery($query)->addFilter($filterQuery);
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
                'Service types query expander available only with %s, got: %s',
                BoolQuery::class,
                is_object($boolQuery) ? get_class($boolQuery) : gettype($boolQuery),
            ));
        }

        return $boolQuery;
    }
}
