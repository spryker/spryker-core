<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Generated\Shared\Search\ServicePointIndexMap;
use Generated\Shared\Transfer\ServicePointSearchTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\ServicePointSearch\ServicePointSearchFactory getFactory()
 */
class ServicePointAddressRelationExcludeServicePointQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @var string
     */
    protected const PARAMETER_EXCLUDE_ADDRESS_RELATION = 'excludeAddressRelation';

    /**
     * @var string
     */
    protected const QUERY_PARAM_SOURCE = '_source';

    /**
     * @var string
     */
    protected const KEY_INCLUDES = 'includes';

    /**
     * @var string
     */
    protected const KEY_EXCLUDES = 'excludes';

    /**
     * {@inheritDoc}
     * - Excludes service point address relation from query if `excludeAddressRelation` request parameter is provided.
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
        $this->addAddressRelationExcludeToQuery($searchQuery->getSearchQuery(), $requestParameters);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     * @param array<string, mixed> $requestParameters
     *
     * @return void
     */
    protected function addAddressRelationExcludeToQuery(Query $query, array $requestParameters = []): void
    {
        $excludeAddressRelation = $requestParameters[static::PARAMETER_EXCLUDE_ADDRESS_RELATION] ?? false;
        if (!$excludeAddressRelation) {
            return;
        }

        $query->setSource([
            static::KEY_INCLUDES => ServicePointIndexMap::SEARCH_RESULT_DATA,
            static::KEY_EXCLUDES => $this->getExcludesData($query),
        ]);
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return array<int, mixed>
     */
    protected function getExcludesData(Query $query): array
    {
        $excludesData = [sprintf('%s.%s', ServicePointIndexMap::SEARCH_RESULT_DATA, ServicePointSearchTransfer::ADDRESS)];
        if (!$query->hasParam(static::QUERY_PARAM_SOURCE)) {
            return $excludesData;
        }

        $sourceExcludesData = $query->getParam(static::QUERY_PARAM_SOURCE)[static::KEY_EXCLUDES] ?? [];

        return array_merge(
            (array)$sourceExcludesData,
            $excludesData,
        );
    }
}
