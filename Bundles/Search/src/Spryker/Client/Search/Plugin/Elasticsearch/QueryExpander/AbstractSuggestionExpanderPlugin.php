<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Elastica\Suggest\AbstractSuggest;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Exception\MissingSuggestionQueryException;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
abstract class AbstractSuggestionExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = [])
    {
        $this->setCompletionTerm($searchQuery->getSearchQuery(), $requestParameters);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     * @param array $requestParameters
     *
     * @return void
     */
    protected function setCompletionTerm(Query $query, array $requestParameters = [])
    {
        $this->assertQueryHasSuggestion($query);

        $this->addSuggestion($query, $this->createCompletion($query,$requestParameters));
    }

    /**
     * @param \Elastica\Query $query
     *
     * @throws \Spryker\Client\Search\Exception\MissingSuggestionQueryException
     *
     * @return void
     */
    protected function assertQueryHasSuggestion(Query $query)
    {
        if (!$query->hasParam('suggest')) {
            throw new MissingSuggestionQueryException(
                'The base query to be extended needs to have "suggest" parameter set. Use `$query->setSuggest()`.'
            );
        }
    }

    /**
     * @param \Elastica\Query $query
     * @param \Elastica\Suggest\AbstractSuggest $suggest
     *
     * @return void
     */
    protected function addSuggestion(Query $query, AbstractSuggest $suggest)
    {
        /** @var \Elastica\Suggest $suggestion */
        $suggestion = $query->getParam('suggest');

        $suggestion->addSuggestion($suggest);
    }

    /**
     * @param \Elastica\Query $query
     * @param array $requestParameters
     *
     * @return \Elastica\Suggest\AbstractSuggest
     */
    abstract protected function createCompletion(Query $query, array $requestParameters = []);

}
