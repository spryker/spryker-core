<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Plugin\QueryExpander;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use InvalidArgumentException;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory getFactory()
 */
class FuzzyQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @var string
     */
    public const FUZZINESS_AUTO = 'AUTO';

    /**
     * @var string
     */
    protected const MUST_KEY = 'must';

    /**
     * {@inheritDoc}
     * - Sets `Fuzziness=AUTO` to any high level multi match query
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<mixed> $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        $query = $searchQuery->getSearchQuery();
        $boolQuery = $this->getBoolQuery($query);
        $multiMatchQuery = $this->getMultiMatchQuery($boolQuery);

        if ($multiMatchQuery) {
            $multiMatchQuery->setFuzziness(static::FUZZINESS_AUTO);
        }

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query\BoolQuery $boolQuery
     *
     * @return \Elastica\Query\MultiMatch|null
     */
    protected function getMultiMatchQuery(BoolQuery $boolQuery): ?MultiMatch
    {
        if ($boolQuery->hasParam(static::MUST_KEY)) {
            $multiMatchQuery = $boolQuery->getParam(static::MUST_KEY)[0] ?? null;
            if ($multiMatchQuery instanceof MultiMatch) {
                return $multiMatchQuery;
            }
        }

        return null;
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
                'Fuzzy query expander available only with  %s, got: %s',
                BoolQuery::class,
                get_class($boolQuery)
            ));
        }

        return $boolQuery;
    }
}
